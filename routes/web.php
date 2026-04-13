<?php

use App\Http\Controllers\Admin\AdminParentChildLinkController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ChildAvatarController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ParentApprovalController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Child\ChildProfileController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Admin\AdminSupportTicketController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/how-it-works', function () {
    return view('pages.how-it-works');
})->name('how-it-works');

Route::get('/pathways', function () {
    return view('pages.pathways');
})->name('pathways');

Route::get('/safety', function () {
    return view('pages.safety');
})->name('safety');

Route::get('/subscription', function () {
    return view('pages.subscription');
})->name('subscription');

Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/verify-email/resend', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('verification.send');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/register/pending', function () {
    return view('auth.register-pending');
})->name('register.pending');

Route::get('/parent/approve/{token}', [ParentApprovalController::class, 'show'])
    ->name('parent.approval.show');

Route::post('/parent/approve/{token}', [ParentApprovalController::class, 'store'])
    ->name('parent.approval.store');

Route::get('/support', [SupportTicketController::class, 'create'])->name('support');
Route::post('/support', [SupportTicketController::class, 'store'])->name('support.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        return match ($user->role) {
            'superadmin', 'admin' => redirect()->route('admin.dashboard'),
            'adult' => redirect()->route('adult.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'child' => $user->hasCompletedChildProfile()
                ? redirect()->route('child.dashboard')
                : redirect()->route('child.profile.complete'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

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

            Route::view('/pen-pals', 'child.pen-pals')->name('pen-pals');
            Route::view('/letters', 'child.letters')->name('letters');
            Route::view('/rewards', 'child.rewards')->name('rewards');
            Route::view('/printables', 'child.printables')->name('printables');
            Route::view('/safety', 'child.safety')->name('safety');
        });
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('interests', InterestController::class);
        Route::resource('child-avatars', ChildAvatarController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/children', [AdminUserController::class, 'children'])->name('users.children');
        Route::get('/users/parents', [AdminUserController::class, 'parents'])->name('users.parents');
        Route::get('/users/adults', [AdminUserController::class, 'adults'])->name('users.adults');
        Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');

        Route::get('/moderation/suspended-accounts', [AdminUserController::class, 'suspendedAccounts'])->name('moderation.suspended-accounts');
        Route::patch('/moderation/users/{user}/activate', [AdminUserController::class, 'activate'])->name('moderation.users.activate');

        Route::get('/parents-children', [AdminParentChildLinkController::class, 'index'])->name('parents-children.index');
        Route::delete('/parents-children/{parentChildLink}', [AdminParentChildLinkController::class, 'destroy'])->name('parents-children.destroy');

        // support-ticket
        Route::get('/support-tickets', [AdminSupportTicketController::class, 'index'])->name('support-tickets.index');
        Route::get('/support-tickets/{supportTicket}', [AdminSupportTicketController::class, 'show'])->name('support-tickets.show');
        Route::post('/support-tickets/{supportTicket}/reply', [AdminSupportTicketController::class, 'reply'])->name('support-tickets.reply');
        Route::patch('/support-tickets/{supportTicket}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('support-tickets.update-status');
    });
});

require __DIR__ . '/auth.php';