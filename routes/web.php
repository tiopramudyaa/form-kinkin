<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SurveyResponseController;
use App\Http\Controllers\SurveyWizardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SurveyWizardController::class, 'intro'])->name('survey.intro');

Route::get('/survey/syarat', [SurveyWizardController::class, 'syarat'])->name('survey.syarat');
Route::post('/survey/syarat', [SurveyWizardController::class, 'syaratStore'])->name('survey.syarat.store');

Route::get('/survey/siap/{level}', [SurveyWizardController::class, 'siap'])
    ->whereNumber('level')
    ->name('survey.siap');

Route::get('/survey/data-diri', [SurveyWizardController::class, 'dataDiri'])->name('survey.data-diri');
Route::post('/survey/data-diri', [SurveyWizardController::class, 'dataDiriStore'])->name('survey.data-diri.store');

Route::get('/survey/pertanyaan/{nomor}', [SurveyWizardController::class, 'question'])
    ->whereNumber('nomor')
    ->name('survey.question');
Route::post('/survey/pertanyaan/{nomor}', [SurveyWizardController::class, 'questionStore'])
    ->whereNumber('nomor')
    ->name('survey.question.store');

Route::get('/survey/wa', [SurveyWizardController::class, 'wa'])->name('survey.wa');
Route::post('/survey/wa', [SurveyWizardController::class, 'waStore'])->name('survey.wa.store');

Route::get('/survey/selesai', [SurveyWizardController::class, 'finish'])->name('survey.finish');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/responses', [SurveyResponseController::class, 'index'])->name('responses.index');
        Route::get('/responses/{surveyResponse}', [SurveyResponseController::class, 'show'])->name('responses.show');
    });
});
