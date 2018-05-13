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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['domain' => config('project.api_domain'), 'namespace' => 'Api', 'as' => 'api.'], function(){
    Route::group(['prefix' => 'v1', 'namespace' => 'v1', 'as' => 'v1.'], function(){

        /* 환영 메시지 */
        Route::get('/', ['as' => 'index', 'uses' => 'WelcomeController@index']);

        /* 포럼 API */
        Route::resource('articles', 'ArticlesController');
        Route::get('tags/{slug}/articles', ['as' => 'tags.articles.index', 'uses' => 'ArticlesController@index']);
        Route::get('tags', ['as' => 'tags.index', 'uses' => 'ArticlesController@tags']);

        Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);
        Route::resource('articles.attachments', 'AttachmentsController', ['only' => 'index']);

        /* 토큰 교환 요청(로그인) */
        Route::post('auth/login', [
            'as' => 'sessions.store',
            'uses' => 'SessionsController@store',
        ]);

        Route::resource('signatures', 'SignatureController')->only(['index', 'store', 'show']);
        Route::put('signatures/{signature}/report', 'ReportSignature@update');

    });
});