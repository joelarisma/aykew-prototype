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

Route::post('/course/exercise-text', ['uses' => 'ContentController@exerciseText']);
Route::post('/course/exercise-images', ['uses' => 'ContentController@exerciseImages']);
Route::post('/course/exercise-image-list', ['uses' => 'ContentController@exerciseImageList']);

Route::get('/session/new', 'UserSessionController@sessionDashboard');

Route::post('/session/{session_level}', 'UserSessionController@session');
Route::get('/session/{session_level}', 'UserSessionController@session');

Route::get('/reports/{type}/{no}/{user_id?}', 'UserSessionController@sessionReport');

Route::post('/exercise/done', function() {
	$handler = new UserSessionHandler;

	$handler->submitExercise();
});