<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('thread', 'ThreadController');

Route::post('/thread/{thread}/reply', 'ReplyController@store')->name('thread.add-reply');
