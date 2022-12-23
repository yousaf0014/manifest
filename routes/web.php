<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
	

Route::group(['middleware'=>['auth']],function(){
	Route::get('/readfile/{name}','HomeController@readFile');	
});

Route::group(['middleware' =>['admin']], function (){
	Route::get('/notifications','HomeController@notification');
	Route::get('/notifications_count','HomeController@notificationCount');
	Route::get('/list_notifications','HomeController@list_notifications');
	Route::get('/list_meditations/','HomeController@list_meditations');
	Route::get('/list_affirmations','HomeController@list_affirmations');

	Route::post('/meditation_approve/{meditation}','HomeController@meditation_approve');
	Route::get('/meditation_decline/{meditation}','HomeController@meditation_decline');
	Route::get('/notification_detail/{type}/{id}','HomeController@notification_detail');
	
	Route::get('/adminprofile', 'Admin\UserController@profile');
	Route::post('/adminupdateprofile', 'Admin\UserController@updateprofile');

	Route::get('/users', 'Admin\UserController@index');
	Route::get('/appusers', 'Admin\UserController@appusers');
	Route::get('/users/create', 'Admin\UserController@create');
	Route::post('/users/store', 'Admin\UserController@store');
	Route::get('/users/show/{user}', 'Admin\UserController@show');
	Route::get('/users/showAppUser/{user}', 'Admin\UserController@showAppUser');
	Route::post('/users/update/{user}', 'Admin\UserController@update');
	Route::get('/users/edit/{user}', 'Admin\UserController@edit');	
	Route::get('/users/delete/{user}', 'Admin\UserController@delete');

	Route::get('/topics', 'Admin\TopicsController@index');
	Route::get('/topics/create', 'Admin\TopicsController@create');
	Route::post('/topics/store', 'Admin\TopicsController@store');
	Route::get('/topics/show/{topic}', 'Admin\TopicsController@show');
	Route::post('/topics/update/{topic}', 'Admin\TopicsController@update');
	Route::get('/topics/edit/{topic}', 'Admin\TopicsController@edit');	
	Route::get('/topics/delete/{topic}', 'Admin\TopicsController@delete');

	Route::get('/categories', 'Admin\CategoriesController@index');
	Route::get('/categories/create', 'Admin\CategoriesController@create');
	Route::post('/categories/store', 'Admin\CategoriesController@store');
	Route::get('/categories/show/{category}', 'Admin\CategoriesController@show');
	Route::post('/categories/update/{category}', 'Admin\CategoriesController@update');
	Route::get('/categories/edit/{category}', 'Admin\CategoriesController@edit');	
	Route::get('/categories/delete/{category}', 'Admin\CategoriesController@delete');

	Route::get('comments/{meditation}','Admin\CommentsController@index');
	Route::get('comments/delete/{meditation}/{pivotID}','Admin\CommentsController@delete');

	Route::get('ratings/{meditation}','Admin\CommentsController@viewratings');
	Route::get('ratings/delete/{meditation}/{pivotID}','Admin\CommentsController@delete_rating');
	
});
Route::group(['middleware' =>['guide']], function (){
	
	Route::get('/followers', 'Guide\UserController@followers');
	Route::get('/profile', 'Guide\UserController@profile');
	Route::post('/updateprofile', 'Guide\UserController@updateprofile');
	
	Route::get('/meditations', 'Guide\MeditationsController@index');
	Route::get('/meditations/create', 'Guide\MeditationsController@create');
	Route::post('/meditations/store', 'Guide\MeditationsController@store');
	Route::get('/meditations/show/{md}', 'Guide\MeditationsController@show');
	Route::post('/meditations/update/{md}', 'Guide\MeditationsController@update');
	Route::get('/meditations/edit/{md}', 'Guide\MeditationsController@edit');	
	Route::get('/meditations/delete/{md}', 'Guide\MeditationsController@delete');

	Route::get('/affirmations', 'Guide\AffirmationsController@index');
	Route::get('/affirmations/create', 'Guide\AffirmationsController@create');
	Route::post('/affirmations/store', 'Guide\AffirmationsController@store');
	Route::get('/affirmations/show/{md}', 'Guide\AffirmationsController@show');
	Route::post('/affirmations/update/{md}', 'Guide\AffirmationsController@update');
	Route::get('/affirmations/edit/{md}', 'Guide\AffirmationsController@edit');	
	Route::get('/affirmations/delete/{md}', 'Guide\AffirmationsController@delete');
});
Route::get('/home', 'HomeController@index')->name('home');
