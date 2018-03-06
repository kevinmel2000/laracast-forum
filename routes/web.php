<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/thread', 'ThreadController@index');
Route::get('/thread/{thread}', 'ThreadController@show');
Route::post('/thread/{thread}/reply', 'ReplyController@store');
