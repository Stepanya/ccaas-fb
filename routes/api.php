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
    // Webhook
    // Route::get('webhook', [Sandbox\FacebookPageAPIController::class, 'receiveDataFromWebhook']);
    Route::post('webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromWebhook']);
    // Route::get('test-webhook', [Sandbox\FacebookPageAPIController::class, 'receiveDataFromTestWebhook']);
    Route::post('test-webhook', [Sandbox\LBC\FbPageController::class, 'receiveDataFromTestWebhook']);

    // VANAD Test Endpoints
    Route::post('authenticate', [Sandbox\LBC\FbPageController::class, 'authAPITest']);
    Route::post('process', [Sandbox\LBC\FbPageController::class, 'processAPITest']);

    Route::get('/', [Sandbox\LBC\FbPageController::class, 'showPagePosts']);
    Route::get('/post/{post_id}', [Sandbox\LBC\FbPageController::class, 'showPostAndMainComments']);
    Route::get('/comments/{comment_id}', [Sandbox\LBC\FbPageController::class, 'showCommentReplies']);
    Route::post('comment', [Sandbox\LBC\FbPageController::class, 'createCommentReply']);    
});

// V1
Route::prefix('v1')->group(function () {
    Route::post('comment', [Sandbox\V1\FbPageController::class, 'createCommentReply']);    
});
