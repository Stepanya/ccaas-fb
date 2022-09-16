<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\FbWebhook;
use App\Models\FbPage;
use App\Models\FbLbcRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

Route::get('/', [PagesController::class, 'index']);
Route::get('dashboard', [PagesController::class, 'dashboard']);
Route::post('search-entries', [PagesController::class, 'getFilteredEntries']);
Route::get('failed-entries', [PagesController::class, 'failedEntries']);
Route::post('results', [PagesController::class, 'getFailedEntries']);

Auth::routes([
    'register' => true,
    'reset' => false,
    'verify' => false,
]);

Route::get('/home', [PagesController::class, 'home'])->name('home');

// Route::get('test', function() {
//     return view('test');
// });
//
// Route::post('test-result', function(Request $request) {
//     $track_and_trace_arr = ['follow up', 'follow-up', 'pa check', 'pacheck', 'pa-check'];
//
//     $str = Str::lower($request->input('str'));
//     $string_chk = Str::of($str)->contains($track_and_trace_arr);
//
//     if ($string_chk) {
//         return "String contains keywords.";
//     }
//
//     return "No keywords matched words inside the string.";
// });
