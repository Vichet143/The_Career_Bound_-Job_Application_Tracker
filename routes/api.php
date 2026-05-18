<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\GenerateCvController;

// user registration and login routes
Route::post('/register', [UserController::class, 'store'])->name('api.register');
Route::get('/users', [UserController::class, 'showalluser'])->name('api.users');
Route::post('/login', [UserController::class, 'login'])->name('api.login');
Route::post('/logout', [UserController::class, 'logout'])->name('api.logout');
Route::put('/updateusers', [UserController::class, 'editUser'])->name('api.edituser.query');

// Password reset routes
Route::post('/forgot_password', [ForgotPasswordController::class, 'forgot'])->name('api.forgot_password');
Route::post('/reset_password', [ForgotPasswordController::class, 'reset'])->name('api.reset_password');

Route::post('/template', [TemplateController::class, 'createTemplate'])->name('api.templates.create');
Route::get('/template', [TemplateController::class, 'getTemplates'])->name('api.templates');
Route::put('/updatetemplate', [TemplateController::class, 'updateTemplate'])->name('api.template.update');

// Generate CV routes
Route::apiResource('generatecv', GenerateCvController::class);