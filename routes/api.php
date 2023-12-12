<?php

use Illuminate\Http\Request;

// use Telegram;
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

/* Telegram Bot -  Carefull With this code , Thank you :) */

Route::post('/bot/getupdates', function() {
    $updates = Telegram::getUpdates();
    return (json_encode($updates));
});

Route::post('bot/sendmessage', function() {
    Telegram::sendMessage([
        'chat_id' => 'RECIPIENT_CHAT_ID',
        'text' => 'Hello world!'
    ]);
    return;
});

/* End Telegram Bot route */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
