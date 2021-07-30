<?php

use App\Http\Controllers\Api\VerificationCodesController;
use Illuminate\Support\Facades\Route;

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


// 获取短信验证码
Route::post('verificationCodes', [VerificationCodesController::class, 'store'])->name('api.verificationCodes.store');


