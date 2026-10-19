<?php

namespace Caimari\LaraFlex\Themes\Limpa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\FileViewFinder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Caimari\LaraFlex\Controllers\SiteMenuController;
use Caimari\LaraFlex\Controllers\ViewController;
use Caimari\LaraFlex\Models\GeneralSettings;
use Caimari\LaraFlex\Models\Theme;
use Caimari\LaraFlex\Models\SitePage;
use Caimari\LaraFlex\Models\SitePost;
use Caimari\LaraFlex\Models\SitePostCategory;
use Caimari\LaraFlex\Models\SitePostTag;
use Caimari\LaraFlex\Models\SiteView;


class LimpaServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'limpa');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'limpa');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    
    // Recupera la configuración del tema activo
   // $activeTheme = Theme::where('active', 1)->first();
   // $settings = GeneralSettings::firstOrFail();

   if (Schema::hasTable('site_themes')) {
   $activeTheme = Theme::where('active', 1)->first();
} else {
        // manejo de la situación cuando la tabla no existe
    // puedes asignar un valor predeterminado a $activeTheme aquí
    $activeTheme = null;
}

   $settings = null;

   try {
    $settings = GeneralSettings::firstOrFail();
} catch (\Exception $e) {
    // Maneja el caso en el que no hay registros o la tabla no existe
    // Por ejemplo, puedes establecer valores predeterminados o mostrar un mensaje de error
}

    // Verifica si se encontró la configuración del tema activo y los ajustes generales
    //NEW
    if ($activeTheme && $settings) {

    // Primero verificamos si los campos existen en la tabla site_themes si no! los captamos de la tabla site_settings
    $site_description = (!empty($activeTheme->site_description)) ? $activeTheme->site_description : $settings->site_description;
    $site_title = (!empty($activeTheme->site_title)) ? $activeTheme->site_title : $settings->site_title;
    $site_url = (!empty($activeTheme->site_url)) ? $activeTheme->site_url : $settings->site_url;
    $site_email = (!empty($activeTheme->site_email)) ? $activeTheme->site_email : $settings->site_email;  
    $site_phone = (!empty($activeTheme->site_phone)) ? $activeTheme->site_phone : $settings->site_phone;  
    $logo = (!empty($activeTheme->logo)) ? $activeTheme->logo : $settings->logo;
    
    $facebook = (!empty($activeTheme->facebook)) ? $activeTheme->facebook : $settings->facebook; 
    $twitter = (!empty($activeTheme->twitter)) ? $activeTheme->twitter : $settings->twitter; 
    $linkedin = (!empty($activeTheme->linkedin)) ? $activeTheme->linkedin : $settings->site_phone; 
    $google_plus = (!empty($activeTheme->google_plus)) ? $activeTheme->google_plus : $settings->google_plus; 
    $github = (!empty($activeTheme->github)) ? $activeTheme->github : $settings->github; 
    $pinterest = (!empty($activeTheme->pinterest)) ? $activeTheme->pinterest : $settings->pinterest; 
    $instagram = (!empty($activeTheme->instagram)) ? $activeTheme->instagram : $settings->instagram; 
    $youtube = (!empty($activeTheme->youtube)) ? $activeTheme->youtube : $settings->youtube; 
    $vimeo = (!empty($activeTheme->vimeo)) ? $activeTheme->vimeo : $settings->vimeo; 
    $rss = (!empty($activeTheme->rss)) ? $activeTheme->rss : $settings->rss; 

        if ($activeTheme) {
            // Establece la configuración del tema en la configuración global
            config([
                'theme.site_title' => $site_title,
                'theme.site_url' => $site_url,
                'theme.site_description' => $site_description,
                'theme.site_email' => $site_email,
                'theme.site_phone' => $site_phone,
                'theme.logo' => $activeTheme->logo,
                'theme.site_name' => $activeTheme->site_name,
                'theme.facebook'    => $activeTheme->facebook,
                'theme.twitter' => $activeTheme->twitter,
                'theme.linkedin'    => $activeTheme->linkedin,
                'theme.google_plus' => $activeTheme->google_plus,
                'theme.github'  => $activeTheme->github,
                'theme.pinterest'   => $activeTheme->pinterest,
                'theme.youtube'   => $activeTheme->youtube,
                'theme.vimeo'   => $activeTheme->vimeo,
                'theme.instagram'   => $activeTheme->instagram,
                'theme.rss' => $activeTheme->rss,
                'theme.breadcrumb_active' => $activeTheme->breadcrumb_active,
                'theme.header_sub_bar_active' => $activeTheme->header_sub_bar_active,
                'theme.main_content_active' => $activeTheme->main_content_active,
                'theme.footer_active' => $activeTheme->footer_active,
                'theme.footer_2_active' => $activeTheme->footer_2_active,
                'theme.menu_locations' => $activeTheme->menu_locations,
                'theme.footer.copyright_active' => $activeTheme->footer_copyright_active,
                'theme.title_active' => $activeTheme->title_active,
                'theme.header_image_active' => $activeTheme->header_image_active,
                'theme.sidebar_post_cat_active' => $activeTheme->sidebar_post_cat_active,
                'theme.sidebar_post_tab_active' => $activeTheme->sidebar_post_tab_active,
                'theme.sidebar_text_widget_active' => $activeTheme->sidebar_text_widget_active,
                'theme.sidebar_search_active' => $activeTheme->sidebar_search_active,
            ]);
        }
    
        // General Settings
        View::composer('*', function ($view) {
            static $cachedSettings = false;
            if ($cachedSettings !== false) {
                $view->with('settings', $cachedSettings);
                return;
            }
            try {
                $settings = GeneralSettings::firstOrFail();
                $cachedSettings = $settings;
                $view->with('settings', $settings);
            } catch (\Exception $e) {
                // Manejar el caso en el que no hay registros o la tabla no existe
                // Por ejemplo, puedes establecer valores predeterminados o mostrar un mensaje de error
                $cachedSettings = null;
                $view->with('settings', null);
            }
        });


        // Posts
        View::composer('*', function ($view) {
            // Se dispara por cada vista: se calcula una sola vez por petición.
            static $cachedData = null;
            if ($cachedData !== null) {
                $view->with($cachedData);
                return;
            }
            // Vistas totales/únicas calculadas en la propia consulta (sin N+1).
            $posts = SitePost::withSum('views as totalViews', 'views')
                ->withCount('views as uniqueViews')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $PostTotalViews = 0;
            $PostUniqueViews = 0;
            $totalViews = 0;
            $uniqueViews = 0;
            $postsCount = $posts->count();
        
            $recentPosts = SitePost::orderBy('created_at', 'desc')->take(4)->get();

            $postsWithImage = $posts->filter(function ($post) {
                return isset($post->image) && !empty($post->image);
            });
        
            $mostViewedPosts = SitePost::withCount([
                'views as total_views' => function ($query) {
                    $query->select(DB::raw("SUM(views) as total_views"));
                }
            ])->withSum('views as totalViews', 'views')
              ->withCount('views as uniqueViews')
              ->orderByDesc('total_views')->take(4)->get();
        
            $popularPosts = SitePost::withCount([
                'views as total_views' => function ($query) {
                    $query->select(DB::raw("SUM(views) as total_views"));
                }
            ])->orderByDesc('total_views')->take(3)->get();
        
            $firstThreePostsWithImage = SitePost::withSum('views as totalViews', 'views')
                                                 ->withCount('views as uniqueViews')
                                                 ->whereNotNull('image')
                                                 ->where('image', '<>', '')
                                                 ->inRandomOrder()
                                                 ->take(3)
                                                 ->get();
        
            // Aquí añadimos las vistas totales y las vistas únicas a cada post
            foreach($posts as $post) {
                $post->totalViews = (int) $post->totalViews;
                $post->uniqueViews = (int) $post->uniqueViews;
                $PostTotalViews += $post->totalViews;
                $PostUniqueViews += $post->uniqueViews;
            }
        
            // Y también para los post más vistos y los primeros tres post con imagen
            foreach($mostViewedPosts as $post) {
                $post->totalViews = (int) $post->totalViews;
                $post->uniqueViews = (int) $post->uniqueViews;
            }
        
            foreach($firstThreePostsWithImage as $post) {
                $post->totalViews = (int) $post->totalViews;
                $post->uniqueViews = (int) $post->uniqueViews;
            }
        
            // Pasamos las variables a las vistas
            $cachedData = [
                'posts' => $posts,
                'postsCount' => $postsCount,
                'postsWithImage' => $postsWithImage,
                'mostViewedPosts' => $mostViewedPosts,
                'firstThreePosts' => $firstThreePostsWithImage,
                'PostTotalViews' => $PostTotalViews,
                'PostUniqueViews' => $PostUniqueViews,
                'totalViews' => $totalViews,
                'uniqueViews' => $uniqueViews,
                'recentPosts' => $recentPosts,
                'popularPosts' => $popularPosts,
            ];
            $view->with($cachedData);
        });
        

                // Pages
                View::composer('*', function ($view) {
                    static $cachedPages = null;
                    if ($cachedPages !== null) {
                        $view->with($cachedPages);
                        return;
                    }
                    $pages = SitePage::withSum('views as totalViews', 'views')->withCount('views as uniqueViews')->orderBy('created_at', 'desc')->paginate(10); // Ajusta el número de elementos por página según tus necesidades

                    $pageTotalViews = 0;
                    $pageUniqueViews = 0;

                    foreach ($pages as $page) {
                        $page->totalViews = (int) $page->totalViews;
                        $page->uniqueViews = (int) $page->uniqueViews;
                        $pageTotalViews += $page->totalViews;
                        $pageUniqueViews += $page->uniqueViews;
                    }

                    $cachedPages = [
                        'pages' => $pages,
                        'pageTotalViews' => $pageTotalViews,
                        'pageUniqueViews' => $pageUniqueViews,
                    ];
                    $view->with($cachedPages);
                });



  
                    // Categories
                    View::composer('*', function ($view) {
                        static $categories = null;
                        $categories = $categories ?? SitePostCategory::withCount('posts')->get();
                        $view->with('categories', $categories);
                    });
                
                    // Tags
                    View::composer('*', function ($view) {
                        static $tags = null;
                        $tags = $tags ?? SitePostTag::all();
                        $view->with('tags', $tags);
                    });
                
                    // Menu
                    View::composer('*', function ($view) {
                        static $public_menu = null;
                        if ($public_menu === null) {
                            $menuController = new SiteMenuController();
                            $public_menu = $menuController->showMenu();
                        }
                        $view->with('public_menu', $public_menu);
                    });
                

                    $page = SitePage::first();
                    view()->share('page', $page);

                    $post = SitePost::first();
                    view()->share('post', $post);

                    $category = SitePostCategory::first();
                    view()->share('category', $category);

                    $tag = SitePostTag::first();
                    view()->share('tag', $tag);
                
                    $previousPost = SitePost::orderBy('id', 'desc')->first();
                    View::share('previousPost', $previousPost);

                    $nextPost = SitePost::orderBy('id', 'asc')->first();
                    View::share('nextPost', $nextPost);

                
                    // NEW
                } else {
                //NEW
                }        

    }
}