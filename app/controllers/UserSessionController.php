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
							'test' => $vars,
							'session_exercise' => $exercise,
							'session_level' => $session_level
						];
			break;
			case 'eye-speed':
				$view = 'dynamic.eyespeedtest';
				$args = [
							'session_exercise' => $exercise,
							'session_level' => $session_level
						];
			break;
			case 'comprehension':
				$vars = $handler->getComprehensionExercise($exercise);
				$view = 'dynamic.comprehensiontest';
				$args = [
							'test' => $vars,
							'session_exercise' => $exercise,
							'session_level' => $session_level
						];
			break;
		}


		return View::make($view, $args);
	}
}
