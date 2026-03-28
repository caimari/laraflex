<?php

namespace Caimari\LaraFlex;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;
use Caimari\LaraFlex\Database\Platforms\CustomMySqlPlatform;
use Caimari\LaraFlex\Middleware\CookieConsent;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

use Caimari\LaraFlex\Exceptions\AdminsHandler;
use Caimari\LaraFlex\Middleware\AdminsRedirectIfAuthenticated;
use Caimari\LaraFlex\Middleware\AdminsAuthenticate;


use Caimari\LaraFlex\Models\Admin;


class LaraFlexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        View::composer([
            'layouts.app', 
            'laraflex::layouts.app', 
            'laraflex::admin.login', 
            'laraflex::admin.register', 
            'laraflex::admin.profile', 
            'laraflex::partials.*', 
            'plexus::app'
        ], function ($view) {
            $view->with('errors', session()->get('errors') ? session()->get('errors') : new ViewErrorBag);
        });

        // Option A - publish migrations with: php artisan vendor:publish --tag=laraflex-migrations
        //$this->publishes([
        //    __DIR__
        //    .'/../migrations' => database_path('migrations'),
       // ], 'laraflex-migrations');

        // Option B - publish migrations with: php artisan migrate
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        // Publicar los seeders: php artisan vendor:publish --tag=laraflex-seeders
        $this->publishes([
            __DIR__.'/../seeders' => database_path('seeders')
        ], 'laraflex-seeders');

        // Publicar las imágenes: php artisan vendor:publish --tag=laraflex-images
            $this->publishes([
                __DIR__.'/../images' => storage_path('app/public/default')
            ], 'laraflex-images');


        // Para trabajar con tablas con el paquete doctrine/dbal
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        
        // Middleware 
        $this->app['router']->aliasMiddleware('cookie-consent', CookieConsent::class);
      //  $router->aliasMiddleware('guest', AdminsRedirectIfAuthenticated::class);
      //  $router->aliasMiddleware('auth', AdminsAuthenticate::class);

       // $this->loadViewsFrom(__DIR__ . '/resources/views', 'wmenu');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laraflex');
        
        if (Schema::hasTable('site_themes')) {
            // Carga todas las vistas de los temas existentes en la base de datos
            $themes = DB::table('site_themes')->get();


            foreach ($themes as $theme) {
                if (is_dir(base_path('vendor/caimari/laraflex/themes/' . $theme->name . '/src/resources/views'))) {
                    $this->loadViewsFrom(base_path('vendor/caimari/laraflex/themes/' . $theme->name . '/src/resources/views'), $theme->name);
                }
            }


 // Reordena las vistas para que siempre busque primero en las vistas del tema activo
    $this->app->bind('view.finder', function ($app) use ($themes) {
        $paths = $app['config']['view.paths'];

        // Primero añade las vistas del tema activo
        foreach ($themes as $theme) {
            if ($theme->active == 1) {
                array_unshift($paths, base_path('vendor/caimari/laraflex/themes/' . $theme->name . '/src/resources/views'));
            }
        }

            // Después añade las vistas del adminpanel
            array_unshift($paths, __DIR__.'/resources/views');
            array_unshift($paths, __DIR__.'/resources/views/errors');
    
            return new FileViewFinder($app['files'], $paths);
        });
    }

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app['config']->set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'admins',
        ]);
    
        $this->app['config']->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]);
        
        $this->app->bind('admins', function ($app) {
            return new Admins;
        });

        // Admin Exceptin Handler
        $this->app->singleton(ExceptionHandler::class, AdminsHandler::class);

                // Configuración de reinicio de contraseña para el guard "admin"
                $this->app['config']->set('auth.passwords.admins', [
                    'provider' => 'admins',
                    'table' => 'password_resets',
                    'expire' => 60,
                    'throttle' => 60,
                ]);
    }

    }