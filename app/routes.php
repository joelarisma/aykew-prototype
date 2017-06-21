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

Route::post('/session/{session_level}', 'UserSessionController@session');
Route::get('/session/{session_level}', 'UserSessionController@session');


Route::post('/exercise/done', function() {
	$handler = new UserSessionHandler;

	$handler->submitExercise();
});