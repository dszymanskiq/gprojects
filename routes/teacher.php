<?php
use \App\Http\Controllers\Teacher\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('teacher.dashboard');
