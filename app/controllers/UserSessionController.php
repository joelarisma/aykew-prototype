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

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function sessionDashboard()
	{
		$handler = new UserSessionHandler;

		$vars = $handler->newSession();

		 $level = $section = $this->getLevelForSession($vars['current_session']);

		if(is_numeric($vars))
			return Redirect::to('/session/' . $vars);

		return View::make('dynamic.dashboard', [
					'package' 	=> null,
		            'session' 	=> $vars['current_session'],
		            'section' 	=> $section,
		            'level' 	=> $level,
		            'unlocks' 	=> [],
		            'speeds' 	=> [],
		            'day' 		=> true,
		            'tip' 		=> "",
		            'todo'		=> true, //proceed
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
				$vars = $handler->getReadingExercise($exercise);
				$view = 'dynamic.readingspeedtest';
				$args = [
							'test' 				=> $vars,
							'session_exercise' 	=> $exercise,
							'session_level' 	=> $session_level
						];
			break;
			case 'eye-speed':
				$view = 'dynamic.eyespeedtest';
				$args = [
							'session_exercise'	=> $exercise,
							'session_level' 	=> $session_level
						];
			break;
			case 'comprehension':
				//check if user hasn't answered the questions otherwise generate the questions
				//from the previous reading material
				if($vars = $handler->doComprehensionQuestions($exercise)) {

					$view = 'dynamic.comprehensionquestiontest';
					$args = [
								'questionData'		=> $vars['questions'],
								'wpm' 				=> $vars['wpm'],
								'session_exercise'	=> $exercise,
								'test_id'			=> $vars['test_id'],
								'session_level' 	=> $session_level
							];
				} else {

					$vars = $handler->getComprehensionExercise($exercise);
					$view = 'dynamic.comprehensiontest';
					$args = [
								'test'				=> $vars,
								'session_exercise'	=> $exercise,
								'session_level'		=> $session_level
							];
				}
			break;
			case 'eye-exercise':
				$vars = $handler->getEyeExercises($exercise);
				$view = 'dynamic.exercise';
				$args = [
							'ex'			=> json_encode($vars['exercise']),
							'slow_wpm'		=> $vars['wpmspeeds']['slow_wpm'],
			                'medium_wpm'	=> $vars['wpmspeeds']['medium_wpm'],
			                'fast_wpm'		=> $vars['wpmspeeds']['fast_wpm'],
			                'superfast_wpm' => $vars['wpmspeeds']['superfast_wpm'],
			                'words'			=> $vars['wpmspeeds']['words'],
			                'session_exercise'	=> $exercise,
							'session_level'		=> $session_level
						];
			break;
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
    public function getLevelForSession($session)
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
