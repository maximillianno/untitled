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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();
Route::get('logout','Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

//главная
Route::resource('/', 'IndexController', [
    'only' => ['index'],
    'names' => ['index' => 'homes'],
]);
//страницы портфолио
Route::resource('/portfolios', 'PortfolioController', [
    'parameters' => [
        'portfolios' => 'alias']]);
//страницы статей
Route::resource('articles', 'ArticlesController', [
    'parameters' => [
        'articles' => 'alias']]);
//страницы категорий
Route::get('articles/cat/{cat_alias?}', ['uses' => 'ArticlesController@index', 'as' => 'articlesCat'])->where('cat_alias','[\w-]+');
//маршрут сохранения комментария
Route::resource('comment', 'CommentController', ['only' => ['store']]);
//для контактов
Route::match( ['get', 'post'],'contacts',['uses' => 'ContactController@index', 'as' => 'contacts']);
//Route::auth();
//Группа маршрутов админки
//Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function (){
//    Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'admin_index']);
//    Route::resource('articles', 'Admin\ArticlesController');
//});
Route::name('admin.')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'admin_index']);
    Route::resource('articles', 'Admin\ArticlesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('menus', 'Admin\MenusController');
});