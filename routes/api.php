<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// middlewares
use App\Http\Middleware\JwtTokenGuard;

// routes
use App\Http\Controllers\UsersController;


// Auth
Route::post("/auth/login", [UsersController::class, "login"])->name("login");

Route::post("/auth/signup", [UsersController::class, "signup"])->name("signup");

Route::get("/auth/my-profile", [UsersController::class, "getMyProfile"])
    ->name("getMyProfile")
    ->middleware(JwtTokenGuard::class);

Route::get("/auth/users", [UsersController::class, "findAllUsers"])->name("findAllUsers");