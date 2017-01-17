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

use GuzzleHttp\Client;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '3',
        'redirect_uri' => 'http://client.dev:8082/auth/callback',//Cal fer quadrar a la BD la url del client amb aquesta
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://localhost:8080/oauth/authorize?'.$query);
});

Route::get('/redirect_implicit', function () {
    $query = http_build_query([
        'client_id' => '3',
        'redirect_uri' => 'http://client.dev:8082/auth/callback',//Cal fer quadrar a la BD la url del client amb aquesta
        'response_type' => 'token', //implicit
        'scope' => '',
    ]);

    return redirect('http://localhost:8080/oauth/authorize?'.$query);
});

Route::get('/auth/callback', function () {
    $http = new Client;

    $response = $http->post('http://localhost:8080/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '3',
            'client_secret' => 'qOxBZ59sHBYEGMYmomgIuSl7NANRBIIwbnjBanKL',
            'redirect_uri' => 'http://client.dev:8082/auth/callback',
            'code' => Request::input('code'),
        ],
    ]);

    $json=json_decode((string) $response->getBody(), true);


});

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes
    Route::get('tasks', 'TasksController@index')->name('tasks');


});
