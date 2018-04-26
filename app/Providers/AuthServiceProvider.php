<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Article' => 'App\Policies\ArticlePolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Правило для доступа к аминке
        \Gate::define('VIEW_ADMIN', function ($user){

            return $user->canDo('VIEW_ADMIN');
        });

        //для доступа к admin.articles
        \Gate::define('VIEW_ADMIN_ARTICLES', function ($user){

            return $user->canDo('VIEW_ADMIN_ARTICLES');
        });

        //VIEW_ADMIN_ARTICLES
    }
}
