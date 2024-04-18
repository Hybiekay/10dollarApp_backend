<?php
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;




Route::prefix("v1")->group( function(){
    Route::post('register', [AuthController::class, "register" ]);
    Route::post("/email-verification", [AuthController::class, "verify_otp"]);
    Route::post("/resend-otp", [AuthController::class, "resend_otp"]);
    Route::post("/forget-password", [AuthController::class, "forget_password"]);
    Route::post("/verify-forget-password", [AuthController::class, "verify_forget_password"]);
    Route::post("/login", [AuthController::class, "login"]);

    });


    Route::prefix("v1")->middleware(["auth:sanctum"])->group(function(){
        Route::post("/update-user" , [AuthController::class, "update_user"]);
        Route::post("/update-password" , [AuthController::class, "update_password"]);
        Route::post("/log-out", [AuthController::class,"log_out"]);
    });
