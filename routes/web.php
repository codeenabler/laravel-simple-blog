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

Route::get('/', 'PostsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('posts', 'PostsController@index')->name('posts');
Route::get('posts/create', 'PostsController@create');
Route::get('posts/search', 'SearchController@show');
Route::get('posts/{category}/{post}', 'PostsController@show');
Route::patch('posts/{category}/{post}', 'PostsController@update');
Route::delete('posts/{category}/{post}', 'PostsController@destroy');
Route::post('posts', 'PostsController@store');
Route::get('posts/{category}', 'PostsController@index');

Route::get('/posts/{category}/{post}/comments', 'CommentsController@index');
Route::post('/posts/{category}/{post}/comments', 'CommentsController@store');
Route::patch('/comments/{comment}', 'CommentsController@update');
Route::delete('/comments/{comment}', 'CommentsController@destroy')->name('comments.destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
