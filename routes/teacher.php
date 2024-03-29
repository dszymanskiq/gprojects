<?php
use \App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ProjectController;
use App\Http\Controllers\Teacher\TaskController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ProjectController::class)->only('create','store','show');
Route::resource('projects.tasks', TaskController::class)->only(['create','edit','store','update','destroy']);
