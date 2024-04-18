<?php
use App\Http\Controllers\VideoModelController;
use Illuminate\Support\Facades\Route;





Route::middleware(["auth:sanctum"])->prefix("v1")->group(
    function(){
    Route::post("/create-video", [VideoModelController::class, "store"]);
    }
);
