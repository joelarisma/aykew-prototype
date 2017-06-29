<?php

class UserSessionController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/
	private $view_folder = '_dynamic';

	public function showWelcome()
	{
		return View::make('hello');
	}
	//improve logic in report;
	//if user request reports for all students in a class
	//if user request reports single student
	//if user request reports single students all levels
	public function sessionReport($type, $no, $user_id = null)
	{
		$handler = new UserSessionHandler;

		$def = is_null($user_id) ? 12345 : $user_id;

		$vars = $handler->_generateReport($type, $no, $def);
		dd($vars);
		return View::make($this->view_folder . '.reports', [
				'reports'	=> $vars['results'],
				'type'		=> $type,
				'no'		=> $no,
				'avg'		=> [
									'wpm'			=> $vars['wpm'], 
									'comprehension' => $vars['comprehension'],
									'ers'			=> $vars['ers']
					]]); 
	}

	public function sessionDashboard()
	{
		$handler = new UserSessionHandler;

		$vars = $handler->newSession();

		if(is_numeric($vars))
			return Redirect::to('/session/' . $vars);

		 $level = $section = $this->getLevelForSession($vars['current_session']);

		return View::make($this->view_folder . '.dashboard', [
					'package' 	=> null,
		            'session' 	=> $vars['current_session'],
		            'section' 	=> $section,
		            'level' 	=> $level,
		            'unlocks' 	=> [],
		            'speeds' 	=> [],
		            'day' 		=> true,
		            'tip' 		=> "",
		            'todo'		=> true, //proceed to next
		            'do_comptest' 	=> false,
		            'do_typetest' 	=> false,
		            'do_twopoint' 	=> false,
		            'do_session' 	=> true,
		            'nextsection' 	=> null,
		            'video' 		=> false,
		            'show_lti_modal' => null
			]);
	}

	public function session($session_level) 
	{
		$view_folder = 'dynamic';

		$handler = new UserSessionHandler;

		if(Request::isMethod('post'))
		{
			$handler->submitExercise(Input::all(), $session_level);

			return Redirect::to('/session/' . $session_level);
		}

		$exercise = $handler->startSession($session_level);
		if($exercise === true)
			return Redirect::to('/session/new');

		switch($exercise->type->type_code)
		{
			case 'pre-test':
			case 'post-test':
				if($exercise->type->type_code == 'pre-test') 
				{
					$vars = $handler->getReadingExercise($exercise);
					$view = $this->view_folder . '.readingspeedtest';
					$args = [
								'test' 				=> $vars,
								'session_exercise' 	=> $exercise,
								'session_level' 	=> $session_level,
								'is_last'			=> $handler->isLast()
							];
				} else {
					if($vars = $handler->doComprehensionQuestions($exercise)) 
					{

						$view = $this->view_folder . '.comprehensionquestiontest';
						$args = [
									'questionData'		=> $vars['questions'],
									'wpm' 				=> $vars['wpm'],
									'session_exercise'	=> $exercise,
									'test_id'			=> $vars['test_id'],
									'session_level' 	=> $session_level,
									'is_last'			=> $handler->isLast()
								];
					} else {
						$vars = $handler->getReadingExercise($exercise);
						$view = $this->view_folder . '.readingspeedtest';
						$args = [
									'test' 				=> $vars,
									'session_exercise' 	=> $exercise,
									'session_level' 	=> $session_level,
									'is_last'			=> $handler->isLast()
								];
					} 
				}
			break;
			case 'eye-speed':
				$view = $this->view_folder . '.eyespeedtest';
				$args = [
							'session_exercise'	=> $exercise,
							'session_level' 	=> $session_level,
							'is_last'			=> $handler->isLast()
						];
			break;
			case 'comprehension':
				//check if user hasn't answered the questions otherwise generate the questions
				//from the previous reading material
				if($vars = $handler->doComprehensionQuestions($exercise)) {

					$view = $this->view_folder . '.comprehensionquestiontest';
					$args = [
								'questionData'		=> $vars['questions'],
								'wpm' 				=> $vars['wpm'],
								'session_exercise'	=> $exercise,
								'test_id'			=> $vars['test_id'],
								'session_level' 	=> $session_level,
								'is_last'			=> $handler->isLast()
							];
				} else {

					$vars = $handler->getComprehensionExercise($exercise);
					$view = $this->view_folder . '.comprehensiontest';
					$args = [
								'test'				=> $vars,
								'session_exercise'	=> $exercise,
								'session_level'		=> $session_level,
								'is_last'			=> $handler->isLast()
							];
				}
			break;
			case 'eye-exercise':
				$vars = $handler->getEyeExercises($exercise);
				$view = $this->view_folder . '.exercise';
				$args = [
							'ex'			=> json_encode($vars['exercise']),
							'slow_wpm'		=> $vars['wpmspeeds']['slow_wpm'],
			                'medium_wpm'	=> $vars['wpmspeeds']['medium_wpm'],
			                'fast_wpm'		=> $vars['wpmspeeds']['fast_wpm'],
			                'superfast_wpm' => $vars['wpmspeeds']['superfast_wpm'],
			                'words'			=> $vars['wpmspeeds']['words'],
			                'session_exercise'	=> $exercise,
							'session_level'		=> $session_level,
							'is_last'			=> $handler->isLast()
						];
			break;
			case 'typing-test':
				$vars = $handler->getReadingExercise($exercise);
				$vars->description = strip_tags(html_entity_decode($vars->description));
				$vars->content = strip_tags(html_entity_decode($vars->content));

				$view = $this->view_folder . '.typingtest';
				$args = [
							'test' 				=> $vars,
							'session_exercise' 	=> $exercise,
							'session_level' 	=> $session_level,
							'last_test'			=> $handler->isLast()
						];
			break;
			default:
		}

		return View::make($view, $args);
	}



	/**
     * Get level for a given session
     *
     * @param object $session CourseSession object
     *
     * @return Section object
     */
    public function getLevelForSession(CourseSession $session)
    {
        $package_levels = Section::where('course_id', '=', $session->course_id)
            ->where('status', '=', 1)
            ->get();

        foreach ($package_levels as $level) {
            $begining_session = $level->begSessions->session;
            $ending_session = $level->endSessions->session;

            if ($begining_session <= $session->session && $ending_session >= $session->session) {
                return $level;
            }
        }

        return null;
    }
}
