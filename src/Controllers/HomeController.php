<?php

namespace Caimari\LaraFlex\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

use Caimari\LaraFlex\Models\GeneralSettings;
use Caimari\LaraFlex\Models\SitePage;
use Caimari\LaraFlex\Models\SitePost;
use Caimari\LaraFlex\Models\CodeSnippet;
use Caimari\LaraFlex\Controllers\ViewController;
use Caimari\LaraFlex\Services\ShortcodeProcessor;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function index()
    {
        // Obtén el buscador de vistas actual
        $finder = view()->getFinder();

        // Obtén las ubicaciones de vistas existentes
        $paths = $finder->getPaths();

        // Verifica que la tabla 'site_themes' exista antes de intentar consultarla
        if (Schema::hasTable('site_themes')) {
        // Obtén el paquete activo de la base de datos
        $activeTheme = DB::table('site_themes')->where('active', 1)->first();

         // Si hay un tema activo, verifica ambas ubicaciones y añade la correcta a las ubicaciones de vistas
        if ($activeTheme) {
        // Comprobar la nueva ubicación primero
        if (is_dir(base_path('vendor/caimari/' . $activeTheme->name . '/src/resources/views'))) {
            array_unshift($paths, base_path('vendor/caimari/' . $activeTheme->name . '/src/resources/views'));
        } 
        // Si el tema no está en la nueva ubicación, comprobar la antigua ubicación
        elseif (is_dir(base_path('vendor/caimari/laraflex/themes/' . $activeTheme->name . '/src/resources/views'))) {
            array_unshift($paths, base_path('vendor/caimari/laraflex/themes/' . $activeTheme->name . '/src/resources/views'));
        }
    }
}
        // Configura el buscador de vistas con las nuevas ubicaciones
        $finder->setPaths($paths);

        // Obtén la configuración de la página principal desde la base de datos.
        $homePageSetting = GeneralSettings::first();

                // Instancia un nuevo ViewController
                $viewController = new ViewController;

                // Instancia un nuevo ShortcodeProcessor
                $shortcodeProcessor = new ShortcodeProcessor();

                // Si no hay ninguna configuración de la página de inicio en la base de datos, manejarlo de alguna manera.
                // Aquí, simplemente asignamos un valor por defecto, pero puedes manejarlo de la manera que prefieras.
                if (!$homePageSetting) {
                    $homePageSetting = new GeneralSettings;
                    $homePageSetting->content_type = 'none';
                } else {
                    // Verificar el tipo de contenido
                    if ($homePageSetting->content_type == 'page') {
                        // Obtener la página específica si esta activa con status 1
                        // $page = SitePage::find($homePageSetting->content_id);
                        $page = SitePage::where('id', $homePageSetting->content_id)
                        ->where('status', 1)
                        ->first();
    
                        if ($page) {
                            // Incrementar las vistas de la página
                            $viewController->incrementViews($page);

                            // Procesa los Shortcodes en la página
                            $page->content = $shortcodeProcessor->parse_shortcodes($page->content);

                            $homePageSetting->content = $page;
                        } else {
                            $homePageSetting->content_type = 'none';
                        }
                    } elseif ($homePageSetting->content_type == 'post') {
                        // Obtener el post específico si esta activo con status 1
                        //$post = SitePost::find($homePageSetting->content_id);
                        $post = SitePost::where('id', $homePageSetting->content_id)
                         ->where('status', 1)
                        ->first();


                        if ($post) {
                            $viewController->incrementViews($post);

                            // Procesa los Shortcodes en el post
                            $post->content = $shortcodeProcessor->parse_shortcodes($post->content);

                            $homePageSetting->content = $post;
                        } else {
                            $homePageSetting->content_type = 'none';
                        }

                    } elseif ($homePageSetting->content_type == 'snippet') {
                        $snippet = CodeSnippet::find($homePageSetting->content_id);
                        // $query = CodeSnippet::where('id', $homePageSetting->content_id)->toSql();
                        //Log::info('Query SQL: ' . $query);

                        if ($snippet) {
                            // Asigna el snippet a la configuración de la página de inicio
                            $homePageSetting->content = $snippet;
                            Log::info('Snippet encontrado: ' . $snippet->id);
                        } else {
                            $homePageSetting->content_type = 'none';
                            Log::warning('Snippet no encontrado para el content_id: ' . $homePageSetting->content_id);
                        }                  

                    }
                }

            // Verifica si la vista 'index' del tema activo existe
            if ($activeTheme && view()->exists($activeTheme->name . '::index')) {
                // Si existe, devuélvela, junto con la configuración de la página de inicio
                return view($activeTheme->name . '::index', compact('homePageSetting'));
                
            }
            // Si no hay tema activo o si no existe la vista 'index' del tema activo, 
            // devuelve la vista por defecto del paquete 'laraflex', junto con la configuración de la página de inicio
            return view('laraflex::index', compact('homePageSetting'));
   
            }

            public function useCookies()
            {
                $activeTheme = null;
                $settings = null;
                
                if (Schema::hasTable('site_themes')) {
                    $activeTheme = DB::table('site_themes')->where('active', 1)->first();
                }
            
                if (Schema::hasTable('general_settings')) {
                    $settings = GeneralSettings::first();
                }
                
                if ($activeTheme && $settings) {
                    return view($activeTheme->name . '::frontend.use-cookies-page', compact('settings'));
                } else {
                    // Puedes redirigir a una página de error o mostrar un mensaje adecuado
                    return "Cookie settings are not available at this time.";
                }
            }
                   
}
