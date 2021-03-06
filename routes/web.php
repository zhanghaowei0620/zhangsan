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

Route::get('/', function () {
    return view('welcome');
});
Route::any('accessToken','Weixin\WeixinController@accessToken');
Route::any('userInfo','Weixin\WeixinController@userInfo');
Route::any('addcreate','Weixin\WeixinController@addcreate');
Route::any('openiddo','Weixin\WeixinController@openiddo');

//登陆
Route::any('login','Weixin\IndexController@login');
Route::any('loginDo','Weixin\IndexController@loginDo');