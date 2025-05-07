<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserFileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\FormAttachmentController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\LocalFormController;
use App\Http\Controllers\Member\SignatureController;
use App\Http\Controllers\UserProfilePhotoController;
use App\Http\Controllers\Member\OverseasFormController;
use App\Http\Controllers\Member\TravelFormExportController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth.member')->group(function () {

    
    Route::middleware(['auth.member'])->group(function () {Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');});
    Route::get('/travel-requests/create', [TravelRequestController::class, 'create'])->name('travel-requests.create');
    Route::post('/travel-requests', [TravelRequestController::class, 'store'])->name('travel-requests.store');

    Route::get('/travel-requests/{id}/edit', [TravelRequestController::class, 'edit'])->name('travel-requests.edit');
    Route::post('/travel-requests/{id}/update', [TravelRequestController::class, 'update'])->name('travel-requests.update');
    Route::delete('/travel-requests/{id}', [TravelRequestController::class, 'destroy'])->name('travel-requests.destroy');


    
    // Local Form
    Route::get('/local-forms/{id}/fill', [LocalFormController::class, 'edit'])->name('member.local-forms.edit');
    Route::post('/local-forms/{id}/update', [LocalFormController::class, 'update'])->name('member.local-forms.update');
    
    // Overseas Form
    Route::get('/Overseas-forms/{id}/fill', [OverseasFormController::class, 'edit'])->name('member.Overseas-forms.edit');
    Route::post('/Overseas-forms/{id}/update', [OverseasFormController::class, 'update'])->name('member.Overseas-forms.update');

    Route::patch('/travel-forms/local/{id}/cancel', [LocalFormController::class, 'cancel'])->name('member.local-forms.cancel');
    Route::patch('/travel-forms/Overseas/{id}/cancel', [OverseasFormController::class, 'cancel'])->name('member.Overseas-forms.cancel');

    Route::post('/attachments/upload', [FormAttachmentController::class, 'store'])->name('attachments.upload');
    Route::get('/attachments/{id}/download', [FormAttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{id}/delete', [FormAttachmentController::class, 'destroy'])->name('attachments.delete');

    Route::get('/local-forms/{id}/view', [LocalFormController::class, 'show'])->name('member.local-forms.show');
    Route::get('/overseas-forms/{id}/view', [OverseasFormController::class, 'show'])->name('member.Overseas-forms.show');


    Route::get('/travel-requests/all', [TravelRequestController::class, 'index'])->name('member.travel-requests.index');
    Route::get('/local-forms/all', [LocalFormController::class, 'all'])->name('member.local-forms.all');
    Route::get('/overseas-forms/all', [OverseasFormController::class, 'all'])->name('member.Overseas-forms.all');

    Route::get('/travel-requests/{id}', [TravelRequestController::class, 'show'])->name('travel-requests.show');

    //files and photo
    Route::post('/upload/profile-photo', [UserProfilePhotoController::class, 'update'])->name('profile-photo.update');
    Route::post('/upload/file', [UserFileController::class, 'store'])->name('user-file.store');
    Route::delete('/delete/file/{id}', [UserFileController::class, 'destroy'])->name('user-file.destroy');

    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/user-files/{id}/download', [UserFileController::class, 'download'])->name('user-file.download');

    Route::get('/account/signature', [SignatureController::class, 'showForm'])->name('member.signature.form');
    Route::post('/account/signature', [SignatureController::class, 'upload'])->name('member.signature.upload');

    Route::get('/local-forms/{id}/export', [TravelFormExportController::class, 'exportLocal'])->name('admin.local-forms.export');
    Route::get('/overseas-forms/{id}/export', [TravelFormExportController::class, 'exportOverseas'])->name('admin.overseas-forms.export');


    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
    
        return back();
    })->name('notifications.read');

});