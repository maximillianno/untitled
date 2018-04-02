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
Route::resource('/articles', 'ArticlesController', [
    'parameters' => [
        'articles' => 'alias']]);
//страницы категорий
Route::get('articles/cat/{cat_alias?}', ['uses' => 'ArticlesController@index', 'as' => 'articlesCat'])->where('cat_alias','[\w-]+');
//маршрут сохранения комментария
Route::resource('comment', 'CommentController', ['only' => ['store']]);
//для контактов
Route::match( ['get', 'post'],'contacts',['uses' => 'ContactController@index', 'as' => 'contacts']);
