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

//Metody GET
Route::get('/api/getRecipe/{id}', 'ApiController@getGetRecipe');
Route::get('/api/getCategories', 'ApiController@getGetCategoryTree');
Route::get('/api/getRecipesList', 'ApiController@getGetRecipesList');
Route::get('/api/getProductsList', 'ApiController@getGetProductsList');
Route::get('/api/getStoreCart/{userSession}', 'ApiController@getGetStoreCart');

// Metody PUT
Route::put('/api/addToCart/{userSession}/{id}/{ammount}', 'ApiController@putAddToCart');
Route::put('/api/addToFavourites/{userSession}/{id}', 'ApiController@putAddToFavourites');

// Metody POST
Route::post('/api/login', 'ApiController@postLogin');
Route::post('/api/postRegister', 'ApiController@postRegister');

// Metody DELETE
Route::delete('/api/removeFromCart/{userSession}/{id}', 'ApiController@deleteRemoveFromCart');
Route::delete('/api/removeFromFavourites/{userSession}/{id}', 'ApiController@deleteRemoveFromFavourites');