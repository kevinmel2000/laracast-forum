<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/thread', 'ThreadController@index');
Route::post('/thread', 'ThreadController@store');
Route::get('/thread/create', 'ThreadController@create');
Route::get('/thread/{channel}/{thread}', 'ThreadController@show');
// Route::resource('thread', 'ThreadController');

Route::post('/thread/{channel}/{thread}/reply', 'ReplyController@store')->name('thread.add-reply');
