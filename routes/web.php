<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/thread', 'ThreadController@index');
Route::get('/thread/create', 'ThreadController@create');
Route::get('/thread/{channel}', 'ThreadController@index');
Route::get('/thread/{channel}/{thread}', 'ThreadController@show');
Route::post('/thread', 'ThreadController@store');
// Route::resource('thread', 'ThreadController');

Route::post('/thread/{channel}/{thread}/reply', 'ReplyController@store')->name('thread.add-reply');

Route::post('/reply/{reply}/favorite', 'FavoriteController@store')->name('reply.favorite');
