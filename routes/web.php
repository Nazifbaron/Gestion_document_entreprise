<?php

use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\CategoryController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard',['user'=>$user]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('utilisateurs', UserController::class);
        Route::resource('roles', RoleController::class); 
        Route::resource('permissions', PermissionController::class);
        Route::resource('folders', FolderController::class);
    });

    Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('documents', DocumentController::class)->except(['show']);
        Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])
        ->name('documents.preview');
        Route::get('documents/{document}/telecharger', [DocumentController::class, 'telecharger'])
        ->name('documents.telecharger');
        Route::get('documents/statistiques', [DocumentController::class, 'statistiques'])
        ->name('documents.stats');
        Route::get('documents/export/pdf', [DocumentController::class, 'exportPdf'])->name('documents.export.pdf');
        Route::get('documents/stats-mois', [DocumentController::class, 'statsMois'])->name('documents.stats.mois');
        Route::get('documents/versions/{version}/download', [DocumentController::class, 'downloadVersion'])->name('documents.versions.download');
        Route::post(' documents/versions/{version}/restore', [DocumentController::class, 'restoreVersion'])->name('documents.versions.restore');
        Route::delete('/documents/versions/{version}', [DocumentController::class, 'deleteVersion'])->name('documents.versions.destroy');
        Route::post('/documents/partager', [DocumentController::class, 'share'])->name('documents.share');
        Route::get('/documents/partager', [DocumentController::class, 'shareMultiple'])->name('documents.shareMultiple');
        Route::post('/documents/partager-multiple', [DocumentController::class, 'partagerMultiple'])->name('documents.partager.multiple');
        Route::patch('documents/{document}/archiver', [DocumentController::class, 'archiver'])->name('documents.archive');
        Route::patch('documents/{document}/restaurer', [DocumentController::class, 'restaurer'])->name('documents.restore');
        Route::post('/notifications/{id}/read', function ($id, Request $request) {
            $notification = $request->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            return back();
        })->name('notifications.read');
        Route::post('/notifications/read-all', function (Request $request) {
            $request->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.readAll');
    });

    Route::middleware(['auth', 'role:admin|responsable'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('documents/historique', [DocumentController::class, 'historique'])->name('documents.historique');

        Route::get('/documents/validation', [DocumentController::class, 'validationIndex'])->name('documents.validation.index');
        Route::patch('/documents/{document}/valider', [DocumentController::class, 'valider'])->name('documents.valider');
        Route::patch('/documents/{document}/rejeter', [DocumentController::class, 'rejeter'])->name('documents.rejeter');
        

        Route::resource('categories', CategoryController::class)->names('categories')->except(['show']);

     });

   

  

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    

  