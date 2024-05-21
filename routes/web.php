<?php

use App\Http\Controllers\Admin\BinController;
use App\Http\Controllers\Admin\ClusterController;
use App\Http\Controllers\Admin\DocTypeController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\FileController as MemberFileController;
use App\Http\Controllers\Member\FolderController as MemberFolderController;
use App\Http\Controllers\ProjectController;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('/file-management', '/file-management/dashboards');
    Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:superadmin|admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('project')->name('projects.')->group(function () {
            Route::get('', [FolderController::class, 'index'])->name('index');
            Route::get('create', [FolderController::class, 'create'])->name('create');
            Route::post('', [FolderController::class, 'store'])->name('store');
            Route::get('show/{folder:slug}', [FolderController::class, 'show'])->name('show');
            Route::get('edit/{folder:slug}', [FolderController::class, 'edit'])->name('edit');
            Route::put('{folder}', [FolderController::class, 'update'])->name('update');
        });

        Route::prefix('file')->name('files.')->group(function () {
            Route::get('', [FileController::class, 'index'])->name('index');
            Route::post('/upload/{folder}', [FileController::class, 'upload'])->name('upload');
        });

        Route::prefix('user')->name('users.')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('index');
            Route::get('/baru', [UserController::class, 'baru'])->name('baru');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
        });

        Route::prefix('bin')->name('bins.')->group(function () {
            Route::get('', [BinController::class, 'index'])->name('index');
            Route::get('project', [BinController::class, 'project'])->name('project');
        });

        Route::prefix('setting')->name('settings.')->group(function () {
            Route::prefix('cluster')->name('clusters.')->group(function () {
                Route::get('', [ClusterController::class, 'index'])->name('index');
            });
            Route::prefix('doc-type')->name('docTypes.')->group(function () {
                Route::get('', [DocTypeController::class, 'index'])->name('index');
            });
        });

        Route::get('/account-settings', [UserController::class, 'accountSettings'])->name('account-settings');
        Route::post('/update-account', [UserController::class, 'updateAccount'])->name('update-account');
    });

    Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::prefix('file')->name('files.')->group(function () {
            Route::get('', [MemberFileController::class, 'index'])->name('index');
            Route::post('/upload/{folder}', [MemberFileController::class, 'upload'])->name('upload');
        });

        Route::prefix('project')->name('projects.')->group(function () {
            Route::get('', [MemberFolderController::class, 'index'])->name('index');
            Route::get('show/{folder:slug}', [MemberFolderController::class, 'show'])->name('show');
        });
    });

    Route::prefix('project')->name('projects.')->group(function () {
        Route::get('/public', [ProjectController::class, 'index'])->name('public');
        Route::get('show/{folder:slug}', [ProjectController::class, 'show'])->name('public.show');
    });
});
