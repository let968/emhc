<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('index');
});




//Roster routes
Route::get('/roster','RosterController@index');

Route::get('/roster/add','RosterController@form');
Route::get('/roster/add/{id}','RosterController@form');

Route::post('/roster/add','RosterController@addOrUpdate');
Route::put('/roster/add/{id}','RosterController@addOrUpdate');
Route::delete('/roster/delete/{id}','RosterController@softDelete');

//Schedule routes
Route::get('/schedule','ScheduleController@index');

Route::get('/schedule/create','ScheduleController@form');
Route::get('/schedule/create/{id}','ScheduleController@form');

Route::post('/schedule/create','ScheduleController@create');
Route::put('/schedule/update/{id}','ScheduleController@update');

//Lineup routes
Route::get('/lineup/{id}','LineupController@index');

Route::put('/lineup/{id}','LineupController@changePlayerStatus');

//Stats routes

Route::get('/stats/{id}','StatsController@index');

Route::put('/stats/{id}','StatsController@changePlayerStats');


//Authentication
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

//User managament
Route::get('/users','UserController@index');
Route::get('/users/create','UserController@form');
Route::get('/users/reset-password',function(){
    return view('auth/passwords/manual-reset');
})->name('users.reset.password');

Route::post('/users/reset-password','UserController@updatePassword');

Route::post('/users/create','UserController@create');
Route::put('/users/{id}','UserController@update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
