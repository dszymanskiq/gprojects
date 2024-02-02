<?php
use \App\Http\Controllers\Student\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
