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

Route::get('/', 'PagesController@newsFeedIndex')->name('home');

Auth::routes();

Route::get('/user/{id}', 'PagesController@user')->name('user')->middleware('auth');
Route::get('/my-profile', 'PagesController@user')->name('my-profile')->middleware('auth');
Route::post('/add-profile-picture', 'PhotosController@storeProfilePicture')->name('add-profile-picture')->middleware('auth');
Route::put('update-profile-picture', 'PhotosController@changeProfilePicture')->name('update-profile-picture')->middleware('auth');
Route::get('/friend-requests', 'FriendRequestsController@index')->name('friend-requests')->middleware('auth');
Route::get('/settings', 'PagesController@settings')->name('settings')->middleware('auth');
Route::get('user/{id}/friends', 'FriendshipsController@index')->name('friends')->middleware('auth');
Route::delete('/friends/remove', 'FriendshipsController@destroy')->name('remove-friend')->middleware('auth');
Route::get('/friends', 'FriendshipsController@index')->name('my-friends')->middleware('auth');
Route::get('/notifications', 'NotificationsController@index')->name('notifications')->middleware('auth');
Route::get('/search/{term}', 'PagesController@search')->name('search')->middleware('auth');
Route::get('/user/{id}/more-info', 'PagesController@userMoreInfo')->name('user-more-info')->middleware('auth');
Route::get('/my-profile/more-info', 'PagesController@userMoreInfo')->name('my-profile-more-info')->middleware('auth');
Route::get('status/{id}', 'PagesController@status')->name('status')->middleware('auth');
Route::get('photos', 'PhotosController@index')->name('my-photos')->middleware('auth');
Route::get('/user/{id}/photos', 'PhotosController@index')->name('photos')->middleware('auth');
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
Route::post('/status/comment/like', 'StatusCommentLikesController@store')->name('like-comment')->middleware('auth');
Route::delete('/status/comment/unlike', 'CommentLikesController@destroy')->name('unlike-comment')->middleware('auth');
Route::post('photos/add', 'PhotosController@store')->name('add-photos')->middleware('auth');
Route::delete('photo/delete', 'PhotosController@destroy')->name('delete-photo')->middleware('auth');
Route::get('my-profile/more-info/edit/name', 'PagesController@editName')->name('edit-name-page')->middleware('auth');
Route::get('my-profile/more-info/edit/DoB', 'PagesController@editDOB')->name('edit-dob-page')->middleware('auth');
Route::get('my-profile/more-info/edit/home-town', 'PagesController@editHomeTown')->name('edit-home-town-page')->middleware('auth');
Route::get('my-profile/more-info/edit/current-town', 'PagesController@editCurrentTown')->name('edit-current-town-page')->middleware('auth');
Route::get('my-profile/more-info/edit/job', 'PagesController@editJob')->name('edit-job-page')->middleware('auth');
Route::get('my-profile/more-info/edit/school', 'PagesController@editSchool')->name('edit-school-page')->middleware('auth');
Route::get('my-profile/more-info/edit/relationship', 'PagesController@editRelationship')->name('edit-relationship-page')->middleware('auth');
Route::put('/my-profile/more-info/edit/name', 'UsersController@updateName')->name('update-name')->middleware('auth');
Route::put('/my-profile/more-info/edit/dob', 'UsersController@updateDOB')->name('update-DOB')->middleware('auth');
Route::put('/my-profile/more-info/edit/town/{type}', 'UsersController@updateTown')->name('update-town')->middleware('auth');
Route::post('/place-name', 'PlaceNamesController@store')->name('add-place-name')->middleware('auth');
Route::put('my-profile/more-info/edit/school', 'UsersController@updateSchool')->name('update-school')->middleware('auth');
Route::post('/school-name', 'SchoolNamesController@store')->name('add-school-name')->middleware('auth');
Route::put('/my-profile/more-info/edit/job', 'UsersController@updateJob')->name('update-job')->middleware('auth');
Route::post('/employer', 'CompaniesController@store')->name('add-employer')->middleware('auth');
Route::put('/my-profile/more-info/edit/relationship', 'UsersController@updateRelationship')->name('update-relationship')->middleware('auth');
Route::post('/my-profile/more-info/edit/accept-relationship-request', 'RelationshipRequestsController@accept')->name('accept-relationship-request')->middleware('auth');
Route::delete('/my-profile/more-info/edit/decline-relationship-request', 'RelationshipRequestsController@decline')->name('decline-relationship-request')->middleware('auth');
Route::get('photo/{id}', 'PhotosController@show')->name('photo')->middleware('auth');
Route::post('photo/like', 'PhotoLikesController@store')->name('like-photo')->middleware('auth');
Route::delete('photo/unlike', 'PhotoLikesController@destroy')->name('unlike-photo')->middleware('auth');
Route::post('photo/comment/add', 'PhotoCommentsController@store')->name('add-photo-comment')->middleware('auth');
Route::put('photo/comment/edit', 'PhotoCommentsController@update')->name('update-photo-comment')->middleware('auth');
Route::delete('photo/comment/delete', 'PhotoCommentsController@destroy')->name('delete-photo-comment')->middleware('auth');
Route::post('photo/comment/like', 'PhotoCommentLikesController@store')->name('like-photo-comment')->middleware('auth');
Route::delete('photo/comment/unlike', 'PhotoCommentLikesController@destroy')->name('unlike-photo-comment')->middleware('auth');
Route::put('status/privacy', 'StatusesController@changePrivacy')->name('update-status-privacy')->middleware('auth');
Route::put('photo/privacy', 'PhotosController@changePrivacy')->name('update-photo-privacy')->middleware('auth');
Route::put('settings/privacy', 'SettingsController@updatePrivacy')->name('update-privacy-settings')->middleware('auth');

Route::redirect('/home', '/');