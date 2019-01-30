<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return 'hello';
});

$app->post(
    'auth/login', 
    [
       'uses' => 'AuthController@authenticate'
    ]
);

$app->group(['middleware' => 'jwt.auth', 'namespace' => 'App\Http\Controllers'], function($request) use ($app) {
    $app->get('auth/employee', ['uses' => 'AuthController@employee']);
    $app->post('auth/employee', ['uses' => 'AuthController@editemployee']);
});

$app->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'], function() use ($app) {
    $app->get('employees',  ['uses' => 'EmployeeController@showAllEmployees']);
    $app->get('employees/sellers/all',  ['uses' => 'EmployeeController@showAllSeller']);
    $app->get('positions',  ['uses' => 'PositionController@showAllPositions']);
    $app->get('positions/{id}',  ['uses' => 'PositionController@showPositionById']);
    $app->post('employees', ['uses' => 'EmployeeController@create']);
    $app->post('employees/{id}', ['uses' => 'EmployeeController@updateAuthentication']);
    $app->post('worktimes', ['uses' => 'WorktimeController@create']);
    $app->get('worktimes', ['uses' => 'WorktimeController@get']);
    $app->get('worktimes/owner', ['uses' => 'WorktimeController@getById']);
    $app->post('worktimes/edit/{id}', ['uses' => 'WorktimeController@update']);
    $app->post('worktimes/{id}', ['uses' => 'WorktimeController@delete']);
    $app->post('checkin', ['uses' => 'WorktimeController@checkin']);
    $app->post('checkout', ['uses' => 'WorktimeController@checkout']);
    $app->get('confirmcheckin', ['uses' => 'WorktimeController@confirmCheckin']); //all data
    $app->get('confirmcheckout', ['uses' => 'WorktimeController@confirmCheckout']); // all data
    $app->post('search/checkin', ['uses' => 'WorktimeController@searchConfirmCheckin']);
    $app->post('search/checkout', ['uses' => 'WorktimeController@searchConfirmCheckout']);
    $app->get('workimage/{name}', ['uses' => 'WorktimeController@get_avatar']);
    $app->post('worktimes/savecheckin/{workid}', ['uses' => 'WorktimeController@saveCheckin']);
});

