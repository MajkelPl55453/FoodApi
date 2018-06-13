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

Route::any('/', 'HomeController@index');

//Metody GET
Route::get('/api/getRecipe/{id}', 'ApiController@getGetRecipe');
Route::get('/api/getCategories', 'ApiController@getGetCategoryTree');
Route::get('/api/getProductsList', 'ApiController@getGetProductsList');
Route::get('/api/getRecipesNameList', 'ApiController@getGetRecipesNameList');
Route::get('/api/getStoreCart/{userSession}', 'ApiController@getGetStoreCart');
Route::get('/api/getRecipesList/{category}/{limit}/{offset}', 'ApiController@getGetRecipesList');
Route::get('/api/getRecipesListByPopularity/{limit}/{offset}', 'ApiController@getGetRecipesListByPopularity');

// Metody PUT
Route::put('/api/addToCart/{userid}/{id}/{ammount}', 'ApiController@putAddToCart');
Route::put('/api/addToFavourites/{userid}/{id}', 'ApiController@putAddToFavourites');

// Metody POST
Route::post('/api/login', 'ApiController@postLogin');
Route::post('/api/register', 'ApiController@postRegister');
Route::get('/api/getRecipesListByIds/{limit}/{offset}', 'ApiController@getGetRecipesListByIds');

// Metody DELETE
Route::delete('/api/removeFromCart/{userid}/{id}', 'ApiController@deleteRemoveFromCart');
Route::delete('/api/removeFromFavourites/{userid}/{id}', 'ApiController@deleteRemoveFromFavourites');