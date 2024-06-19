<?php
use \App\Http\Controllers\Student\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
Route::get('/projects/{project}/show', [\App\Http\Controllers\Student\ProjectController::class, 'show'])->name('student.projects.show');
Route::get('/projects/{project}/join', [\App\Http\Controllers\Student\ProjectController::class, 'join'])->name('student.projects.join');
Route::get('/projects/{project}/leave', [\App\Http\Controllers\Student\ProjectController::class, 'leave'])->name('student.projects.leave');
