<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(config('app.name') . ': '. __('Verify Email'))
                ->view(
                    ['main.emails.html.verify-email', 'main.emails.plain.verify-email'], 
                    ['url' => $url]
                );
        });

        Gate::define('auth-check', function (User $user, $checkBoolean) {
            return $checkBoolean;
        });

        Gate::define('is-admin', function (User $user) {
            return $user->group->is_admin == 'Y' ? Response::allow() : Response::denyAsNotFound();
        });
    }
}
