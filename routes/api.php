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
    // Route::get('lbc/webhook', [Sandbox\FbPageController::class, 'receiveDataFromWebhook']);
    Route::post('lbc/webhook', [Sandbox\FbPageController::class, 'receiveDataFromWebhook']);
    // Route::get('lbc/test-webhook', [Sandbox\FbPageController::class, 'receiveDataFromTestWebhook']);
    Route::post('lbc/test-webhook', [Sandbox\FbPageController::class, 'receiveDataFromTestWebhook']);

    // Twitter Webhook
    Route::get('lbc/twitter/test-webhook', [Sandbox\TwitterFeedController::class, 'receiveDataFromTestWebhook']);

    // VANAD Test Endpoints
    Route::post('authenticate', [Sandbox\FbPageController::class, 'authAPITest']);
    Route::post('process', [Sandbox\FbPageController::class, 'processAPITest']);

    Route::post('comment', [Sandbox\FbPageController::class, 'createCommentReply']);
    Route::post('comment/hide', [Sandbox\FbPageController::class, 'hidePostComment']);    
});

// V1
Route::prefix('v1')->group(function () {
    Route::post('comment', [V1\FbPageController::class, 'createCommentReply']);    
});
