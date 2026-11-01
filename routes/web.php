<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Candidate\DashboardController;
use App\Http\Controllers\Candidate\JobController as CandidateJobController;
use Illuminate\Support\Facades\Route;
use App\Models\JobPost;

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

// Guest routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [HomeController::class, 'jobs'])->name('jobs.index');
Route::get('/search/suggest', [HomeController::class, 'suggest'])->middleware('throttle:60,1')->name('search.suggest');
Route::get('/jobs/{job:slug}', [HomeController::class, 'show'])->name('jobs.show');




Route::middleware(['auth'])->prefix('candidate')->name('candidate.')->group(function () {
    

Route::get('/jobs', [CandidateJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [CandidateJobController::class, 'show'])->name('jobs.show');

    //profile manage routes
    Route::get('/profile', [ProfileController::class, 'candidateProfile'])->name('profile');
    Route::POST('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/delete-profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/job/apply/{job}', [HomeController::class, 'apply'])->name('job.apply');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


 
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications');
 
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('saved');
    Route::post('/saved-jobs/{job}', [SavedJobController::class, 'toggle'])->name('saved.toggle');
 
    Route::get('/job-alerts', [JobAlertController::class, 'index'])->name('alerts');
    Route::post('/job-alerts', [JobAlertController::class, 'store'])->name('alerts.store');
    Route::delete('/job-alerts/{alert}', [JobAlertController::class, 'destroy'])->name('alerts.destroy');
 
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
 
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
 
});
 

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';

require __DIR__.'/employer.php';


