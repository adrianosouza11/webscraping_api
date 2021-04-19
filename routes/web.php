<?php

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

Route::group(['prefix'=>'api/v1'], function(){

    Route::group(['prefix'=>'extraction'], function (){
        Route::get('scraping', 'Extraction\WebScrapingController@doScraping');
    });
});

