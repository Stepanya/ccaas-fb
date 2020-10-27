<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Sandbox;   
// use App\Http\Controllers\Api\V1;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Sandbox
Route::prefix('sandbox')->group(function () {
    // LBC
    // FB Webhook
    // Route::get('lbc/webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromWebhook']);
    Route::post('lbc/webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromWebhook']);
    // Route::get('lbc/test-webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromTestWebhook']);
    Route::post('lbc/test-webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromTestWebhook']);

    // Twitter Webhook
    Route::get('lbc/twitter/test-webhook', [Sandbox\LBC\TwitterFeedController::class, 'receiveDataFromTestWebhook']);
    Route::post('lbc/twitter/test-webhook', [Sandbox\LBC\TwitterFeedController::class, 'receiveDataFromTestWebhook']);

    // Instagram Webhook
    // Route::get('lbc/instagram/test-webhook', [Sandbox\LBC\InstagramFeedController::class, 'receiveDataFromTestWebhook']);
    Route::post('lbc/instagram/test-webhook', [Sandbox\LBC\InstagramFeedController::class, 'receiveDataFromTestWebhook']);

    // VANAD Test Endpoints
    Route::post('authenticate', [Sandbox\LBC\FbPageController::class, 'authAPITest']);
    Route::post('process', [Sandbox\LBC\FbPageController::class, 'processAPITest']);

    Route::post('comment', [Sandbox\LBC\FbPageController::class, 'createCommentReply']);
    Route::post('comment/hide', [Sandbox\LBC\FbPageController::class, 'hidePostComment']);
    
    // V1
    Route::prefix('v1')->group(function () {
        Route::post('comment', [Sandbox\LBC\FbPageController::class, 'createCommentReply']);
        Route::post('comment/hide', [Sandbox\LBC\FbPageController::class, 'hidePostComment']);
    });
});

// V1
Route::prefix('v1')->group(function () {
    Route::post('comment', [V1\LBC\FbPageController::class, 'createCommentReply']);    
});
