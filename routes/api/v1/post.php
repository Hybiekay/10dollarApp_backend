<?php
use App\Http\Controllers\PostModelController;



Route::middleware(["auth:sanctum"])->group(function () {

    Route::prefix("v1")->group(
        function () {
            Route::post("/post",   [PostModelController::class,"store"]);
        }
        );


});
