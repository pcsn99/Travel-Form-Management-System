<?php

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Route;
use App\Exports\LocalTravelFormExport;
use App\Exports\OverseasTravelFormExport;

use App\Http\Controllers\UserFileController;
use App\Http\Controllers\LocalFormController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OverseasFormController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\FormAttachmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SignatureController;
use App\Http\Controllers\LocalFormQuestionController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\AdminTravelRequestController;
use App\Http\Controllers\OverseasFormQuestionController;
use App\Http\Controllers\Admin\CommunityMemberController;
use App\Http\Controllers\TravelRequestQuestionController;
use App\Http\Controllers\Admin\TravelFormExportController;

//test
Route::get('/test-image-export', [\App\Http\Controllers\TestImageExportController::class, 'export']);

Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth.admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


    Route::resource('travel-request-questions', TravelRequestQuestionController::class)->except(['show']);
    Route::get('/user-file/download/{id}', [\App\Http\Controllers\UserFileController::class, 'download'])->name('user-file.download');


    Route::get('/travel-requests', [TravelRequestController::class, 'index'])->name('travel-requests.index');
    Route::get('/travel-requests/{id}', [TravelRequestController::class, 'show'])->name('travel-requests.show');
    Route::post('/travel-requests/{id}/approve', [TravelRequestController::class, 'approve'])->name('travel-requests.approve');
    Route::post('/travel-requests/{id}/reject', [TravelRequestController::class, 'reject'])->name('travel-requests.reject');
    Route::post('/travel-requests/{id}/reset', [TravelRequestController::class, 'resetStatus'])->name('travel-requests.reset');


    Route::get('/local-forms', [LocalFormController::class, 'index'])->name('local-forms.index');
    Route::get('/local-forms/{id}', [LocalFormController::class, 'show'])->name('local-forms.show');
    Route::post('/local-forms/{id}/approve', [LocalFormController::class, 'approve'])->name('local-forms.approve');
    Route::post('/local-forms/{id}/reject', [LocalFormController::class, 'reject'])->name('local-forms.reject');

    Route::get('/Overseas-forms', [OverseasFormController::class, 'index'])->name('Overseas-forms.index');
    Route::get('/Overseas-forms/{id}', [OverseasFormController::class, 'show'])->name('Overseas-forms.show');
    Route::post('/Overseas-forms/{id}/approve', [OverseasFormController::class, 'approve'])->name('Overseas-forms.approve');
    Route::post('/Overseas-forms/{id}/reject', [OverseasFormController::class, 'reject'])->name('Overseas-forms.reject');

    Route::resource('local-form-questions', LocalFormQuestionController::class)->except(['show']);
    Route::resource('Overseas-form-questions', OverseasFormQuestionController::class)->except(['show']);

    Route::post('local-form-questions/{id}/move/{direction}', [LocalFormQuestionController::class, 'reorder'])->name('local-form-questions.move');
    Route::post('Overseas-form-questions/{id}/move/{direction}', [OverseasFormQuestionController::class, 'reorder'])->name('Overseas-form-questions.move');


    Route::get('/admin-travel-requests/search', [AdminTravelRequestController::class, 'search'])->name('admin-travel-requests.search');
    Route::get('/admin-travel-requests/find', [AdminTravelRequestController::class, 'findUser'])->name('admin-travel-requests.find');
    Route::get('/admin-travel-requests/create/{userId}', [AdminTravelRequestController::class, 'createForm'])->name('admin-travel-requests.create');
    Route::post('/admin-travel-requests/store/{userId}', [AdminTravelRequestController::class, 'store'])->name('admin-travel-requests.store');
 

    Route::get('/local-forms/{id}/edit', [LocalFormController::class, 'edit'])->name('local-forms.edit');
    Route::post('/local-forms/{id}/update', [LocalFormController::class, 'update'])->name('local-forms.update');

    Route::get('/Overseas-forms/{id}/edit', [OverseasFormController::class, 'edit'])->name('Overseas-forms.edit');
    Route::post('/Overseas-forms/{id}/update', [OverseasFormController::class, 'update'])->name('Overseas-forms.update');

    Route::post('/Overseas-forms/{id}/reset', [OverseasFormController::class, 'resetStatus'])->name('Overseas-forms.reset');
    Route::post('/local-forms/{id}/reset', [App\Http\Controllers\LocalFormController::class, 'resetStatus'])->name('local-forms.reset');

    Route::post('/attachments/upload', [FormAttachmentController::class, 'store'])->name('attachments.upload');
    Route::get('/attachments/download/{id}', [FormAttachmentController::class, 'download'])->name('attachments.download');

    Route::get('/user-files/{id}/download', [UserFileController::class, 'download'])->name('user-file.download');

    Route::get('/admin/upload-signature', [SignatureController::class, 'showForm'])->name('admin.upload.signature.form');
    Route::post('/admin/upload-signature', [SignatureController::class, 'upload'])->name('admin.upload.signature');

    Route::delete('/attachments/delete/{id}', [FormAttachmentController::class, 'destroy'])->name('attachments.delete');

    Route::get('/admin/community-members', [CommunityMemberController::class, 'index'])->name('admin.members.index');
    Route::get('/admin/community-members/{id}', [CommunityMemberController::class, 'show'])->name('admin.members.show');
    Route::get('/admin/community-members/{id}/history', [CommunityMemberController::class, 'history'])->name('admin.members.history');

    
    Route::get('/local-forms/{id}/export', [TravelFormExportController::class, 'exportLocal'])->name('admin.local-forms.export');
    Route::get('/overseas-forms/{id}/export', [TravelFormExportController::class, 'exportOverseas'])->name('admin.overseas-forms.export');

    Route::get('/admin-accounts', [AdminAccountController::class, 'index'])->name('admin-accounts.index');
    Route::get('/admin-accounts/create', [AdminAccountController::class, 'create'])->name('admin-accounts.create');
    Route::post('/admin-accounts', [AdminAccountController::class, 'store'])->name('admin-accounts.store');
    Route::get('/admin-accounts/{id}/edit', [AdminAccountController::class, 'edit'])->name('admin-accounts.edit');
    Route::put('/admin-accounts/{id}', [AdminAccountController::class, 'update'])->name('admin-accounts.update');
    Route::delete('/admin-accounts/{id}', [AdminAccountController::class, 'destroy'])->name('admin-accounts.destroy');

    




    
    //old export routes (UNUSED)
    Route::get('/local-forms/{id}/export', function ($id) {
        $form = \App\Models\LocalTravelForm::findOrFail($id);
        return Excel::download(new LocalTravelFormExport($form), 'local-travel-form.xlsx');
    })->name('local-forms.export');

    Route::get('/Overseas-forms/{id}/export', function ($id) {
        $form = \App\Models\OverseasTravelForm::findOrFail($id);
        return Excel::download(new OverseasTravelFormExport($form), 'Overseas-travel-form.xlsx');
    })->name('Overseas-forms.export');

    //calendar
    Route::get('/admin/travel-calendar/details/{date}', [DashboardController::class, 'calendarDetails']);


    Route::post('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
    
        return back();
    })->name('notifications.read');

});

