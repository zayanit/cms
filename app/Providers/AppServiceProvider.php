<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
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
        $this->loadViewsFrom(public_path(config('theme.themeDir').'/'.config('theme.themes.admin.theme').'/views'), 'admin');
        $this->loadViewsFrom(public_path(config('theme.themeDir').'/'.config('theme.themes.public.theme').'/views'), 'public');

        $this->app->booted(function () {
            $finder = $this->app['view.finder'];
            $finder->setPaths(array_values(array_filter($finder->getPaths(), 'is_dir')));

            foreach ($finder->getHints() as $namespace => $hints) {
                $finder->replaceNamespace($namespace, array_values(array_filter($hints, 'is_dir')));
            }

            foreach (Route::getRoutes() as $route) {
                $name = $route->getName();

                if ($name === null || !str_starts_with($route->uri(), 'api/')) {
                    continue;
                }

                $action = $route->getAction();
                $action['as'] = 'api.'.$name;
                $route->setAction($action);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
