<?php

use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Auth\ParentApprovalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Child\ChildProfileController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register/pending', function () {
    return view('auth.register-pending');
})->name('register.pending');

Route::get('/parent/approve/{token}', [ParentApprovalController::class, 'show'])
    ->name('parent.approval.show');

Route::post('/parent/approve/{token}', [ParentApprovalController::class, 'store'])
    ->name('parent.approval.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/child/dashboard', function () {
        return view('child.dashboard');
    })->name('child.dashboard');

    Route::get('/adult/dashboard', function () {
        return view('adult.dashboard');
    })->name('adult.dashboard');

    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');
    
     Route::prefix('child')->name('child.')->group(function () {
        Route::get('/complete-profile', [ChildProfileController::class, 'edit'])->name('profile.complete');
        Route::post('/complete-profile', [ChildProfileController::class, 'update'])->name('profile.store');

        Route::middleware(['child.profile.completed'])->group(function () {
            Route::get('/dashboard', function () {
                return view('child.dashboard');
            })->name('dashboard');
        });
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('interests', InterestController::class);
    });
});

require __DIR__.'/auth.php';