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

//Route::post('/', function () {
//    $data = 'test';
//    file_put_contents("./sd_notifyUrl_log_Dan.txt", date("Y-m-d H:i:s") . "  " . "异步通知返回报文：" . $data . "\r\n", FILE_APPEND);
//    return view('welcome');
    
//});


Route::view('/welcome', 'welcome');

Route::get('user/{id}', 'UserController@show');

Route::get('myEbankPayTrading', 'myController@myEbankPayTrading')->name('myEbankPayTrading');
Route::get('myMerchantRegister', function(){ return view('myMerchantRegister');});
Route::post('myMerchantRegister', 'myController@myMerchantRegister')->name('myMerchantRegister');
Route::get('myMerchantSearch', 'myController@myMerchantSearch')->name('myMerchantSearch');


Route::get('sandpayMerchantRegister', function(){ return view('sandpayMerchantRegister');});
Route::post('sandpayMerchantRegister', 'myController@sandpayMerchantRegister')->name('sandpayMerchantRegister');
Route::get('sandpayMerchantSearch', 'myController@sandpayMerchantSearch')->name('sandpayMerchantSearch');
Route::post('sandpayEbankpayNotify', 'myController@sandpayEbankpayNotify')->name('sandpayEbankpayNotify');
Route::get('sandpayEbankPayTrading', 'myController@sandpayEbankPayTrading')->name('sandpayEbankPayTrading');


Route::resource('photos', 'PhotoController')->only(['index', 'show']);

Route::any('greeting', function(){
    
    return view('greeting', ['name'=>'James']);
});


/*Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
*/
Route::post('ebankPayNotify', 'myController@ebankPayNotify')->name('ebankPayNotify');

Route::post('epay/eBankAPI', 'myController@eBankAPIHandel')->name('eBankAPIHandel');
Auth::routes();

//Route::post('postverify', array('as'=>'postverify', 'uses'=>'myController@postverify'));

Route::get('epayTest/{mode}', 'myController@epayTest')->name('epayTest');
Route::get('demo', 'myController@demo')->name('demo');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');


Route::get ('testNotify', 'myController@testNotify');
