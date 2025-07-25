<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\UserSubscription;

Route::get('/debug-subscriptions', function() {
    $user = auth()->user() ?? User::find(1);
    
    $debug = [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_active' => $user->active,
        'total_subscriptions' => $user->subscriptions()->count(),
        'active_subscriptions' => $user->subscriptions()->active()->count(),
        'has_active_subscription' => $user->hasActiveSubscription(),
        'can_participate' => $user->canParticipateInCompetitions(),
    ];
    
    $subscriptions = UserSubscription::with('user')->get();
    
    return view('debug-subscriptions', compact('debug', 'subscriptions'));
});
