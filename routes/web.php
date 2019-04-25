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

Route::get('/', 'StatusesController@index')->name('home');

Auth::routes();

Route::get('/user/{id?}', 'PagesController@user')->name('user')->middleware('auth');
Route::get('/friend-requests', 'FriendRequestsController@index')->name('friend-requests')->middleware('auth');
Route::get('/settings', 'PagesController@settings')->name('settings')->middleware('auth');
Route::get('/friends', 'FriendshipsController@index')->name('friends')->middleware('auth');
Route::delete('/friends/remove', 'FriendshipsController@destroy')->name('remove-friend')->middleware('auth');
Route::get('/my-friends', 'PagesController@friends')->name('my-friends')->middleware('auth');
Route::get('/notifications', 'PagesController@notifications')->name('notifications')->middleware('auth');
Route::get('/search/{term}', 'PagesController@search')->name('search')->middleware('auth');
Route::get('/user/{id}/more-info', 'PagesController@userMoreInfo')->name('user-more-info')->middleware('auth');
Route::get('status/{id}', 'PagesController@status')->name('status')->middleware('auth');
Route::get('/user/{id}/photos', 'PagesController@userPhotos')->name('photos')->middleware('auth');
Route::get('/friend-requests/count', 'FriendRequestsController@count')->name('count')->middleware('auth');
Route::post('/friend-requests/accept', 'FriendRequestsController@accept')->name('accept-friend-request')->middleware('auth');
Route::delete('/friend-requests/decline', 'FriendRequestsController@decline')->name('decline-friend-request')->middleware('auth');
Route::post('/status/create', 'StatusesController@store')->name('create-status')->middleware('auth');
Route::delete('/status/delete', 'StatusesController@destroy')->name('delete-status')->middleware('auth');
Route::put('/status/edit', 'StatusesController@update')->name('update-status')->middleware('auth');
Route::post('/status/like', 'StatusLikesController@store')->name('like-status')->middleware('auth');
Route::delete('status/unlike', 'StatusLikesController@destroy')->name('unlike-status')->middleware('auth');
Route::post('/status/comment/add', 'StatusCommentsController@store')->name('add-comment')->middleware('auth');
Route::put('/status/comment/edit', 'StatusCommentsController@update')->name('update-comment')->middleware('auth');
Route::delete('/status/comment/delete', 'StatusCommentsController@destroy')->name('delete-comment')->middleware('auth');
Route::post('/status/comment/like', 'CommentLikesController@store')->name('like-comment')->middleware('auth');
Route::delete('/status/comment/unlike', 'CommentLikesController@destroy')->name('unlike-comment')->middleware('auth');

Route::redirect('/home', '/');