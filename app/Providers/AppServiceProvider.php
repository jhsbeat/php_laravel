<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(is_api_domain() and request()->getLanguages()){
            $preferred = request()->getPreferredLanguage();
            $locale = str_contains($preferred, 'ko') ? 'ko' : 'en';
            app()->setLocale($locale);
        }
        view()->composer('*', function($view){
           $allTags = \Cache::rememberForever('tags.list', function(){
              return \App\Tag::all();
           });
           $view->with(compact('allTags'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('local')){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        $this->app->singleton('optimus', function(){
            return new \Jenssegers\Optimus\Optimus(env('OPTIMUS_PRIME'), env('OPTIMUS_INVERSE'), env('OPTIMUS_RANDOM'));
        });
    }
}
