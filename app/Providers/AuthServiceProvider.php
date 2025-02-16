<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
       // \Log::info(message: 'AuthServiceProvider boot()');
        // Gates for Role-Based Access Control (RBAC)
        Gate::define('is-admin', function ($user) {
           // dd($user->role);
            return $user->role === "admin"; // Assuming 'role' is the attribute in the User model
        });

        Gate::define('isInstructor', function (User $user) {
            return $user->role === 'instructor';
        });

        Gate::define('isStudent', function (User $user) {
            return $user->role === 'student';
        });

        // Additional Gates
        Gate::define('manage-courses', function (User $user) {
            return $user->role === 'instructor' || $user->role === 'admin';
        });

        Gate::define('enroll-course', function (User $user) {
            return $user->role === 'student';
        });
    }
}