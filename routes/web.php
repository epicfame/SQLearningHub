<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;

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

// LOGIN
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/logout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');


// REGISTER
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register/action', [RegisterController::class, 'actionregister'])->name('actionregister');


Route::get('/test', function() {
    return view('test');
});

// practice
Route::prefix('/practice')->middleware(['auth'])->group(function () {
    Route::get('/', [PracticeController::class, 'index']);
    Route::get('/question/{id}', [PracticeController::class, 'practice']);
    Route::get('/question/question/test', [PracticeController::class, 'test']);
    Route::post('/question/compile/{id}', [PracticeController::class, 'compile']);
});

// module
Route::prefix('/module')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\ModuleController::class, 'index']);
    Route::get('/{sqlModule}', [\App\Http\Controllers\ModuleController::class, 'sqlModule']); // pass file name to add module
    Route::get('/data/get-menu', [\App\Http\Controllers\ModuleController::class, 'getMenu']);
});

// contest
Route::prefix('/contest')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\ContestController::class, 'index']);
    Route::get('/{contestId}', [\App\Http\Controllers\ContestController::class, 'getContest']); // pass file name to add module
    Route::get('/{contestId}/data', [\App\Http\Controllers\ContestController::class, 'getContest']); // pass file name to add module
    Route::get('/{contestId}/index/{answerId}', [\App\Http\Controllers\ContestController::class, 'getContestQuestion']); // pass file name to add module
    Route::post('/{contestId}/complie/{answerId}', [\App\Http\Controllers\ContestController::class, 'compile']); // pass file name to add module
    Route::get('/data/contest', [\App\Http\Controllers\ContestController::class, 'getContestData']);
});


Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->middleware('auth');

// Admin
Route::prefix('/admin')->middleware(['auth'])->group(function () {
    Route::prefix('/practice')->group(function () {
        Route::get('/index', [\App\Http\Controllers\AdminController::class, 'indexPractice']);
        Route::prefix('/question')->group(function () {
            Route::get('/add', [\App\Http\Controllers\AdminController::class, 'addPractice']);
            Route::get('/add/{id}/detail', [\App\Http\Controllers\AdminController::class, 'questionDetail']);
            Route::get('/data', [\App\Http\Controllers\AdminController::class, 'getQuestion']);
            Route::post('/data/add', [\App\Http\Controllers\AdminController::class, 'addQuestion']);
        });
        Route::get('/report-practice', [\App\Http\Controllers\AdminController::class, 'reportPractice']);
        Route::get('/report-practice/data', [\App\Http\Controllers\AdminController::class, 'reportPracticeData']);
    });
    Route::prefix('/contest')->group(function () {
        Route::get('/index', [\App\Http\Controllers\AdminController::class, 'indexContest']);
        Route::get('/add', [\App\Http\Controllers\AdminController::class, 'addContest']);
        Route::get('/data', [\App\Http\Controllers\AdminController::class, 'getContest']);
        Route::post('/add', [\App\Http\Controllers\AdminController::class, 'addContestData']);
        Route::get('/{id}/detail', [\App\Http\Controllers\AdminController::class, 'showContestDetail']);
        // Route::get('/report-contest', [\App\Http\Controllers\AdminController::class, 'reportContest']);
        // Route::get('/report-contest/data', [\App\Http\Controllers\AdminController::class, 'reportContestData']);
    });
    Route::get('/index', [\App\Http\Controllers\AdminController::class, 'index']);

});



