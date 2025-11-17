<?php

use App\Http\Controllers\Api\WebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::get('home', [WebsiteController::class, 'index']);
    Route::get('about', [WebsiteController::class, 'about']);
    Route::get('services', [WebsiteController::class, 'services']);
    Route::get('faqs', [WebsiteController::class, 'faqs']);
    Route::get('contact', [WebsiteController::class, 'contact']);
    Route::post('enquiry/store', [WebsiteController::class, 'store_enquiry']);
    Route::post('newsletter/subscribe', [WebsiteController::class, 'subscribe_newsletter']);
});
