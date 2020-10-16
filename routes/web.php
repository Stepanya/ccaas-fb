<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookWebhookController;   
use Illuminate\Support\Facades\Storage;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('webhook', [FacebookWebhookController::class, 'receiveDataFromWebhook']);
// Route::post('webhook', [FacebookWebhookController::class, 'receiveDataFromWebhook']);

// Route::get('/test', function() {
//     $json = Storage::get('public/comments.json');
//     $comments = json_decode($json);
    
//     foreach ($comments as $comment) {
//         foreach ($comment->entry as $entry) {
//             foreach ($entry->changes as $change) {
//                 if ($change->value->item == 'comment') {
//                     echo $change->value->message.'<br>';
//                 }
//             }   
//         }
//     }
// });