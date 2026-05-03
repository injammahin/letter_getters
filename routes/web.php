<?php

use App\Http\Controllers\Admin\AdminParentChildLinkController;
use App\Http\Controllers\Admin\AdminSupportTicketController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ChildAvatarController;
use App\Http\Controllers\Admin\HabitantThemeController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\SubscriptionPlanController;

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ParentApprovalController;
use App\Http\Controllers\Auth\VerifyEmailController;

use App\Http\Controllers\Child\ChildHabitantController;
use App\Http\Controllers\Child\ChildNotificationController;
use App\Http\Controllers\Child\ChildPlanController;
use App\Http\Controllers\Child\ChildProfileController;
use App\Http\Controllers\Child\HabitantVoiceController;

use App\Http\Controllers\SupportTicketController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Email Verification and Parent Approval
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Public Support
|--------------------------------------------------------------------------
*/

Route::get('/support', [SupportTicketController::class, 'create'])
    ->name('support');

Route::post('/support', [SupportTicketController::class, 'store'])
    ->name('support.store');

/*
|--------------------------------------------------------------------------
| Authenticated Area
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Main Role Redirect Dashboard
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Simple Role Dashboards
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/adult/dashboard', function () {
        return view('adult.dashboard');
    })->name('adult.dashboard');

    Route::get('/parent/dashboard', function () {
        return view('parent.dashboard');
    })->name('parent.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Child Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('child')->name('child.')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Child Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/complete-profile', [ChildProfileController::class, 'edit'])
            ->name('profile.complete');

        Route::post('/complete-profile', [ChildProfileController::class, 'update'])
            ->name('profile.store');

        /*
        |--------------------------------------------------------------------------
        | Child Notifications
        |--------------------------------------------------------------------------
        */

        Route::get('/notifications/poll', [ChildNotificationController::class, 'poll'])
            ->name('notifications.poll');

        Route::post('/notifications/pen-pals/read', [ChildNotificationController::class, 'markPenPalAsRead'])
            ->name('notifications.pen-pals.read');

        /*
        |--------------------------------------------------------------------------
        | Habitant Voice
        |--------------------------------------------------------------------------
        */

        Route::post('/habitant/voice/speak', [HabitantVoiceController::class, 'speak'])
            ->name('habitant.voice.speak')
            ->middleware('throttle:30,1');

        /*
        |--------------------------------------------------------------------------
        | Habitant Base Routes
        |--------------------------------------------------------------------------
        */

        Route::get('/habitant', [ChildHabitantController::class, 'index'])
            ->name('habitant');

        Route::post('/habitant/assets/{asset}/purchase', [ChildHabitantController::class, 'purchase'])
            ->name('habitant.assets.purchase');

        Route::post('/habitant/assets/{asset}/activate', [ChildHabitantController::class, 'activate'])
            ->name('habitant.assets.activate');

        Route::post('/habitant/layout/save', [ChildHabitantController::class, 'saveLayout'])
            ->name('habitant.layout.save');

        Route::post('/habitant/feed', [ChildHabitantController::class, 'feed'])
            ->name('habitant.feed');

        Route::post('/habitant/play', [ChildHabitantController::class, 'play'])
            ->name('habitant.play');

        Route::post('/habitant/guide/complete', [ChildHabitantController::class, 'completeGuide'])
            ->name('habitant.guide.complete');

        /*
        |--------------------------------------------------------------------------
        | Child Profile Completed Routes
        |--------------------------------------------------------------------------
        */

        Route::middleware(['child.profile.completed'])->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Child\ChildDashboardController::class, 'index'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | Child Premium Plans and Stripe Checkout
            |--------------------------------------------------------------------------
            */

            Route::get('/plans', [ChildPlanController::class, 'index'])
                ->name('plans');

            Route::get('/plans/checkout/success', [ChildPlanController::class, 'success'])
                ->name('plans.checkout.success');

            Route::get('/plans/checkout/cancel', [ChildPlanController::class, 'cancel'])
                ->name('plans.checkout.cancel');

            Route::post('/plans/{subscriptionPlan}/checkout', [ChildPlanController::class, 'checkout'])
                ->name('plans.checkout');

            /*
            |--------------------------------------------------------------------------
            | Child Pen Pals
            |--------------------------------------------------------------------------
            */

            Route::get('/pen-pals', [\App\Http\Controllers\Child\ChildPenPalController::class, 'index'])
                ->name('pen-pals');

            Route::get('/pen-pals/{penPal}/habitant', [\App\Http\Controllers\Child\ChildPenPalController::class, 'visitHabitant'])
                ->name('pen-pals.habitant');

            Route::post('/pen-pals/{targetUser}/request', [\App\Http\Controllers\Child\ChildPenPalController::class, 'sendRequest'])
                ->name('pen-pals.request');

            Route::patch('/pen-pals/requests/{childMatchRequest}/accept', [\App\Http\Controllers\Child\ChildPenPalController::class, 'acceptRequest'])
                ->name('pen-pals.requests.accept');

            Route::patch('/pen-pals/requests/{childMatchRequest}/decline', [\App\Http\Controllers\Child\ChildPenPalController::class, 'declineRequest'])
                ->name('pen-pals.requests.decline');

            Route::delete('/pen-pals/requests/{childMatchRequest}/cancel', [\App\Http\Controllers\Child\ChildPenPalController::class, 'cancelRequest'])
                ->name('pen-pals.requests.cancel');

            /*
            |--------------------------------------------------------------------------
            | Child Chat
            |--------------------------------------------------------------------------
            */

            Route::get('/messages/{penPal}', [\App\Http\Controllers\Child\ChildChatController::class, 'show'])
                ->name('messages.chat');

            Route::post('/messages/{penPal}', [\App\Http\Controllers\Child\ChildChatController::class, 'store'])
                ->name('messages.send');

            /*
            |--------------------------------------------------------------------------
            | Child Letters
            |--------------------------------------------------------------------------
            */

            Route::get('/letters', [\App\Http\Controllers\Child\ChildLetterController::class, 'index'])
                ->name('letters');

            Route::get('/letters/{penPal}/create', [\App\Http\Controllers\Child\ChildLetterController::class, 'create'])
                ->name('letters.create');

            Route::post('/letters/{penPal}', [\App\Http\Controllers\Child\ChildLetterController::class, 'store'])
                ->name('letters.store');

            Route::get('/letters/view/{childLetter}', [\App\Http\Controllers\Child\ChildLetterController::class, 'show'])
                ->name('letters.show');

            /*
            |--------------------------------------------------------------------------
            | Child Shop
            |--------------------------------------------------------------------------
            */

            Route::get('/shop', [\App\Http\Controllers\Child\ChildShopController::class, 'index'])
                ->name('shop');

            Route::get('/shop/products/{product}', [\App\Http\Controllers\Child\ChildShopController::class, 'show'])
                ->name('store.products.show');

            Route::get('/cart', [\App\Http\Controllers\Child\ChildCartController::class, 'index'])
                ->name('store.cart.index');

            Route::post('/cart/{product}', [\App\Http\Controllers\Child\ChildCartController::class, 'store'])
                ->name('store.cart.store');

            Route::patch('/cart/{cartItem}', [\App\Http\Controllers\Child\ChildCartController::class, 'update'])
                ->name('store.cart.update');

            Route::delete('/cart/{cartItem}', [\App\Http\Controllers\Child\ChildCartController::class, 'destroy'])
                ->name('store.cart.destroy');

            Route::get('/checkout', [\App\Http\Controllers\Child\ChildCheckoutController::class, 'create'])
                ->name('store.checkout');

            Route::post('/checkout', [\App\Http\Controllers\Child\ChildCheckoutController::class, 'store'])
                ->name('store.checkout.store');

            Route::get('/orders', [\App\Http\Controllers\Child\ChildOrderController::class, 'index'])
                ->name('store.orders.index');

            Route::get('/orders/{order}', [\App\Http\Controllers\Child\ChildOrderController::class, 'show'])
                ->name('store.orders.show');

            /*
            |--------------------------------------------------------------------------
            | Child Static Pages
            |--------------------------------------------------------------------------
            */

            Route::view('/rewards', 'child.rewards')
                ->name('rewards');

            Route::view('/printables', 'child.printables')
                ->name('printables');

            Route::view('/safety', 'child.safety')
                ->name('safety');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->name('admin.')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Subscription Plans
        |--------------------------------------------------------------------------
        */

        Route::resource('subscription-plans', SubscriptionPlanController::class);

        /*
        |--------------------------------------------------------------------------
        | Interest and Avatar Management
        |--------------------------------------------------------------------------
        */

        Route::resource('interests', InterestController::class);

        Route::resource('child-avatars', ChildAvatarController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/children', [AdminUserController::class, 'children'])
            ->name('users.children');

        Route::get('/users/parents', [AdminUserController::class, 'parents'])
            ->name('users.parents');

        Route::get('/users/adults', [AdminUserController::class, 'adults'])
            ->name('users.adults');

        Route::patch('/users/{user}/suspend', [AdminUserController::class, 'suspend'])
            ->name('users.suspend');

        Route::get('/moderation/suspended-accounts', [AdminUserController::class, 'suspendedAccounts'])
            ->name('moderation.suspended-accounts');

        Route::patch('/moderation/users/{user}/activate', [AdminUserController::class, 'activate'])
            ->name('moderation.users.activate');

        /*
        |--------------------------------------------------------------------------
        | Parent Child Links
        |--------------------------------------------------------------------------
        */

        Route::get('/parents-children', [AdminParentChildLinkController::class, 'index'])
            ->name('parents-children.index');

        Route::delete('/parents-children/{parentChildLink}', [AdminParentChildLinkController::class, 'destroy'])
            ->name('parents-children.destroy');

        /*
        |--------------------------------------------------------------------------
        | Support Tickets
        |--------------------------------------------------------------------------
        */

        Route::get('/support-tickets', [AdminSupportTicketController::class, 'index'])
            ->name('support-tickets.index');

        Route::get('/support-tickets/{supportTicket}', [AdminSupportTicketController::class, 'show'])
            ->name('support-tickets.show');

        Route::post('/support-tickets/{supportTicket}/reply', [AdminSupportTicketController::class, 'reply'])
            ->name('support-tickets.reply');

        Route::patch('/support-tickets/{supportTicket}/status', [AdminSupportTicketController::class, 'updateStatus'])
            ->name('support-tickets.update-status');

        /*
        |--------------------------------------------------------------------------
        | Admin Match Management
        |--------------------------------------------------------------------------
        */

        Route::get('/matches/pending', [\App\Http\Controllers\Admin\AdminChildMatchController::class, 'pending'])
            ->name('matches.pending');

        Route::get('/matches/approved', [\App\Http\Controllers\Admin\AdminChildMatchController::class, 'approved'])
            ->name('matches.approved');

        Route::patch('/matches/requests/{childMatchRequest}/approve', [\App\Http\Controllers\Admin\AdminChildMatchController::class, 'approve'])
            ->name('matches.approve');

        Route::patch('/matches/requests/{childMatchRequest}/reject', [\App\Http\Controllers\Admin\AdminChildMatchController::class, 'reject'])
            ->name('matches.reject');

        Route::patch('/matches/{childMatch}/remove', [\App\Http\Controllers\Admin\AdminChildMatchController::class, 'remove'])
            ->name('matches.remove');

        /*
        |--------------------------------------------------------------------------
        | Admin Letter Processing
        |--------------------------------------------------------------------------
        */

        Route::get('/mail/letters/pending', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'pending'])
            ->name('child-letters.pending');

        Route::get('/mail/letters/approved', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'approved'])
            ->name('child-letters.approved');

        Route::get('/mail/letters/rejected', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'rejected'])
            ->name('child-letters.rejected');

        Route::patch('/mail/letters/scan-all/submitted', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'scanAllSubmitted'])
            ->name('child-letters.scan-all');

        Route::patch('/mail/letters/approve-all/clean-submitted', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'approveAllCleanSubmitted'])
            ->name('child-letters.approve-all-clean');

        Route::get('/mail/letters/{childLetter}', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'show'])
            ->name('child-letters.show');

        Route::patch('/mail/letters/{childLetter}/scan', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'scan'])
            ->name('child-letters.scan');

        Route::patch('/mail/letters/{childLetter}/approve', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'approve'])
            ->name('child-letters.approve');

        Route::patch('/mail/letters/{childLetter}/force-approve', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'forceApprove'])
            ->name('child-letters.force-approve');

        Route::patch('/mail/letters/{childLetter}/reject', [\App\Http\Controllers\Admin\AdminChildLetterController::class, 'reject'])
            ->name('child-letters.reject');

        /*
        |--------------------------------------------------------------------------
        | Admin Store Categories
        |--------------------------------------------------------------------------
        */

        Route::get('/store/categories', [\App\Http\Controllers\Admin\AdminProductCategoryController::class, 'index'])
            ->name('store.categories.index');

        Route::post('/store/categories', [\App\Http\Controllers\Admin\AdminProductCategoryController::class, 'store'])
            ->name('store.categories.store');

        Route::get('/store/categories/{category}/edit', [\App\Http\Controllers\Admin\AdminProductCategoryController::class, 'edit'])
            ->name('store.categories.edit');

        Route::patch('/store/categories/{category}', [\App\Http\Controllers\Admin\AdminProductCategoryController::class, 'update'])
            ->name('store.categories.update');

        Route::delete('/store/categories/{category}', [\App\Http\Controllers\Admin\AdminProductCategoryController::class, 'destroy'])
            ->name('store.categories.destroy');

        /*
        |--------------------------------------------------------------------------
        | Admin Store Products
        |--------------------------------------------------------------------------
        */

        Route::get('/store/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'index'])
            ->name('store.products.index');

        Route::get('/store/products/create', [\App\Http\Controllers\Admin\AdminProductController::class, 'create'])
            ->name('store.products.create');

        Route::post('/store/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'store'])
            ->name('store.products.store');

        Route::get('/store/products/{product}/edit', [\App\Http\Controllers\Admin\AdminProductController::class, 'edit'])
            ->name('store.products.edit');

        Route::patch('/store/products/{product}', [\App\Http\Controllers\Admin\AdminProductController::class, 'update'])
            ->name('store.products.update');

        Route::delete('/store/products/{product}', [\App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])
            ->name('store.products.destroy');

        /*
        |--------------------------------------------------------------------------
        | Admin Store Orders and Inventory
        |--------------------------------------------------------------------------
        */

        Route::get('/store/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])
            ->name('store.orders.index');

        Route::get('/store/orders/{order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])
            ->name('store.orders.show');

        Route::patch('/store/orders/{order}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])
            ->name('store.orders.update-status');

        Route::patch('/store/orders/{order}/shipment', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateShipment'])
            ->name('store.orders.update-shipment');

        Route::get('/store/shipping', [\App\Http\Controllers\Admin\AdminShipmentController::class, 'index'])
            ->name('store.shipping.index');

        Route::get('/store/inventory', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'index'])
            ->name('store.inventory.index');

        Route::patch('/store/inventory/{product}/adjust', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'adjust'])
            ->name('store.inventory.adjust');

        /*
        |--------------------------------------------------------------------------
        | Admin Habitant Themes
        |--------------------------------------------------------------------------
        */

        Route::get('/habitants', [HabitantThemeController::class, 'index'])
            ->name('habitants.index');

        Route::get('/habitants/create', [HabitantThemeController::class, 'create'])
            ->name('habitants.create');

        Route::post('/habitants', [HabitantThemeController::class, 'store'])
            ->name('habitants.store');

        Route::get('/habitants/{habitant}/edit', [HabitantThemeController::class, 'edit'])
            ->name('habitants.edit');

        Route::put('/habitants/{habitant}', [HabitantThemeController::class, 'update'])
            ->name('habitants.update');

        Route::delete('/habitants/{habitant}', [HabitantThemeController::class, 'destroy'])
            ->name('habitants.destroy');

        Route::post('/habitants/{habitant}/assets', [HabitantThemeController::class, 'storeAsset'])
            ->name('habitants.assets.store');

        Route::put('/habitants/{habitant}/assets/{asset}', [HabitantThemeController::class, 'updateAsset'])
            ->name('habitants.assets.update');

        Route::delete('/habitants/{habitant}/assets/{asset}', [HabitantThemeController::class, 'destroyAsset'])
            ->name('habitants.assets.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';