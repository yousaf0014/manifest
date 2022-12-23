<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('register', 'API\UserController@register');
Route::post('socailRegister', 'API\UserController@socailRegister');
Route::post('login', 'API\UserController@login');

Route::get('edit/{user}', 'API\UserController@edit');
Route::get('users','API\UserController@users');
Route::Patch('update/{user}', 'API\UserController@update');
Route::delete('delete/{user}','API\UserController@delete');

Route::middleware('auth:api')->group( function () {
	Route::get('/readfile/{name}','HomeController@readFile');
	Route::get('deleteFollowing/{pivotID}', 'API\UserMeditationController@deleteFollowing');
	Route::get('viewFollowing', 'API\UserMeditationController@viewFollowing');
	Route::get('userTopics', 'API\UserMeditationController@viewTopics');
	Route::get('topicsList', 'API\UserMeditationController@topicsList');
	Route::get('usercomment/{meditation}', 'API\UserMeditationController@viewComments');
	Route::get('viewPreference', 'API\UserMeditationController@viewPreference');
	
	Route::get('categories/{topic}', 'API\UserMeditationController@listCategories');
	Route::get('meditation/{meditation}', 'API\UserMeditationController@meditationDetails');
	Route::get('meditations/{topic}', 'API\UserMeditationController@meditations');
	Route::get('affirmation/{meditation}', 'API\UserMeditationController@affirmationsDetails');
	Route::get('categoryMeditations/{category}', 'API\UserMeditationController@categoryMeditations');
	Route::get('userHistory','API\UserMeditationController@getUserMeditationPreferences');

	Route::get('affirmations/{topic}', 'API\UserMeditationController@affirmations');
	Route::get('milestones', 'API\UserMeditationController@milestones');
	Route::get('userReminder','API\UserMeditationController@getReminders');
	
	Route::post('deletepreference/{meditation}', 'API\UserMeditationController@deletepreference');
	Route::post('deleteComment/{meditation}/{povit}', 'API\UserMeditationController@deleteComment');
	Route::post('registorDevice', 'API\UserController@storeDevice');
	Route::post('userTopics', 'API\UserMeditationController@storeTopics');
	Route::post('usercomment/{meditation}', 'API\UserMeditationController@storeComments');
	Route::post('updateComments/{meditation}/{pivot}', 'API\UserMeditationController@updateComments');
	Route::post('unfollow/{user}', 'API\UserMeditationController@unfollow');
	Route::post('userrating/{meditation}', 'API\UserMeditationController@storeRating');
	Route::post('storePreference/{meditation}', 'API\UserMeditationController@storePreference');
	Route::post('userfollow/{user}', 'API\UserMeditationController@userFollow');
	Route::post('verify', 'API\UserController@verify');
	Route::Post('registerEmail', 'API\UserController@verify');
	Route::Post('useLog', 'API\UserMeditationController@useLog');
	Route::Post('userReminder','API\UserMeditationController@saveReminders');
	Route::Post('deleteReminder/{pivotID}','API\UserMeditationController@deleteReminder');
	Route::Post('updateReminders/{pivotID}','API\UserMeditationController@updateReminders');
});