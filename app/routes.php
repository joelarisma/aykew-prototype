<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/eye-speed', function() {
	return View::make('dynamic.eyespeedtest');
});

Route::get('/session/new', function() {
	$handler = new UserSessionHandler;

	$vars = $handler->newSession();

	if(is_numeric($vars))
		return Redirect::to('/session/' . $vars);

	return View::make('dynamic.index')
				->with($vars);
});

Route::post('/session/{session_level}', function($session_level) {
	$handler = new UserSessionHandler;

	$exercise = $handler->startSession($session_level);

	if($exercise === true)
		return Redirect::to('/session/new');

	switch($exercise->type->type_code)
	{
		case 'pre-test':
		case 'post-test':
			$vars = $handler->getReadingExercise($exercise);
			return View::make('dynamic.readingspeedtest', ['test' => $vars]);
		break;
		case 'eye-speed':
		break;
	}
});

Route::get('/session/{session_level}', function($session_level) {
	$handler = new UserSessionHandler;

	$exercise = $handler->startSession($session_level);



	if($exercise === true)
		return Redirect::to('/session/new');


	dd($exercise->type);
});

Route::get('/exercise/done', function() {
	$handler = new UserSessionHandler;
	$handler->submitExercise(['test']);
});