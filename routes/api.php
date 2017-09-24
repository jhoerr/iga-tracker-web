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

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/bills', 'BillApiController@bills');
Route::get('/all-bills', 'BillApiController@all');
Route::post('/track', 'TrackingApiController@track');
Route::post('/stop-tracking', 'TrackingApiController@stopTracking');
Route::post('/bills/{id}/toggle-email-subscription/', 'TrackingApiController@toggleEmailSubscription');
Route::post('/bills/{id}/toggle-sms-subscription/', 'TrackingApiController@toggleSmsSubscription');
