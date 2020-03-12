<?php

use Illuminate\Http\Request;
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

/**
 * Buyer
*/
Route::resource('Buyer', 'Buyer/BuyerController',['only' => ['index','show']]);

/**
 * Seller
*/
Route::resource('Seller', 'Seller/SellerController',['only' => ['index','show']]);

/**
 * Category
*/
Route::resource('Category', 'Category/CategortyController',['only' => ['create','edit']]);

/**
 * Product
*/
Route::resource('Product', 'Product/ProductController',['only' => ['create','edit']]);

/**
 * Transaction
*/
Route::resource('Transaction', 'Transaction/TransactionController',['only' => ['index','show']]);

/**
 * User
*/
Route::resource('User', 'User/UserController',['only' => ['create','edit']]);
