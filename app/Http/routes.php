<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/', function () {
    return view('auth/login');
});

Route::auth();

Route::get('/home', 'HomeController@index');



Route::get("/mission/create", 'MissionController@create');

Route::post("/mission/publish", 'MissionController@publish');

Route::get("/mission/lobby/{id}", 'MissionController@lobby');

Route::post('/mission/lobby/apply', 'MissionController@apply');

Route::post('/mission/lobby/leave', 'MissionController@leave');

Route::post('/mission/lobby/switch', 'MissionController@switchSlot');

Route::post('/mission/lobby/update', 'MissionController@updateMission');

Route::get("/mission/search", 'MissionController@search');

Route::post("/mission/search", 'MissionController@filter');

Route::get("/mission/mymissions", 'MissionController@myMissions');

Route::get("/profile", "UserController@profile");

Route::post("/updateProfile", "UserController@update");
