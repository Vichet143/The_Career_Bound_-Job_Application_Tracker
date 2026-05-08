<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store'])->name('api.register');
Route::get('/users', [UserController::class, 'showalluser'])->name('api.users');
Route::get('/users/{id}', [UserController::class, 'showuserbyid'])->name('api.user.show');
Route::post('/login', [UserController::class, 'login'])->name('api.login');