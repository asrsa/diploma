<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //authorization
        $gate->define('isUser', function($user){
            return $user->isUser();
        });

        $gate->define('isAdmin', function($user){
            return $user->isAdmin();
        });

        $gate->define('isAuthor', function($user){
            return $user->isAuthor();
        });

        $gate->define('isLoggedUser', function($user, $comment){
            if($user->id === $comment->user_id)
                return true;
            else if($user->isAdmin())
                return true;
            else
                return false;
        });

        $gate->define('isNotLoggedUser', function($user, $comment) {
            return $user->id !== $comment->user_id;
        });
    }
}
