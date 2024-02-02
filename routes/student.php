<?php
use \App\Http\Controllers\Student\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
Route::get('/projects/{project}/join', [\App\Http\Controllers\Student\ProjectController::class, 'join'])->name('student.projects.join');
