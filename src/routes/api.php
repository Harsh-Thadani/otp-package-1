<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use gl\otp\Http\Controllers\OtpController;

Route::get('otp',function(){
    return 'Hello';
});

Route::post('/sendOtp',[OtpController::class, 'sendOtp'])->name('sendOtp');
Route::post('/resendOtp/{phone}',[OtpController::class, 'resendOtp'])->name('resendOtp');
Route::post('/verifyOtp',[OtpController::class, 'verifyOtp'])->name('verifyOtp');