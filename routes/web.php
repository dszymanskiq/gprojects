<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'joinProject'
])->group(function () {
    Route::get('/dashboard', function () {
        if(auth()->user()?->type === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        elseif(auth()->user()?->type === 'student') {
            return redirect()->route('student.dashboard');
        }
        else {
            return redirect()->redirect('/');
        }
    })->name('dashboard');
});

Route::get('/join/{project:slug}', [\App\Http\Controllers\ProjectController::class, 'join'])->name('join-project');
