<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

use Caimari\LaraFlex\Controllers\ViewController;

use Caimari\LaraFlex\Models\SitePage;
use Caimari\LaraFlex\Models\SitePost;
use Caimari\LaraFlex\Models\SitePostCategory;
use Caimari\LaraFlex\Models\SitePostTag;
use Caimari\LaraFlex\Models\SitePostTagRelation;
use Caimari\LaraFlex\Models\SiteGallery;
use Caimari\LaraFlex\Models\SiteGalleryImage;
use Caimari\LaraFlex\Models\SiteComment;

class SiteContentController extends Controller
{

    //////////////////////////////////////////////////////////
    //////////////////// Pages ////////////////////////
    ///////////////////////////////////////////////////////

    public function getActiveTheme() 
{
    // Obtener el tema activo de la base de datos
    $activeTheme = DB::table('site_themes')->where('active', 1)->first();

    // Si no hay un tema activo, usar un valor por defecto
    if (!$activeTheme) {
        return 'default';
    }

    return $activeTheme->name;
}
    
    public function showPage($slug)
    {

        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $page = SitePage::where('slug', $slug)->where('status', 1)->firstOrFail();
    
        // Obtener el contenido de la página
        $content = $page->content;
    
        // Crea una instancia del ShortcodeProcessor e invoca el método parse_shortcodes
        $shortcodeProcessor = new \Caimari\LaraFlex\Services\ShortcodeProcessor();
        $content = $shortcodeProcessor->parse_shortcodes($content);
    
        // Asigna el contenido procesado de nuevo a la propiedad content de la página
        $page->content = $content;
    
        // Obtener la página anterior (anterior a la actual)
        $previousPage = SitePage::where('id', '<', $page->id)
            ->orderBy('id', 'desc')
            ->first();
    
        // Obtener la página siguiente (página siguiente a la actual)
        $nextPage = SitePage::where('id', '>', $page->id)
            ->orderBy('id', 'asc')
            ->first();
    
        // Crea una instancia del ViewController e invoca el método incrementViews
        $viewController = new \Caimari\LaraFlex\Controllers\ViewController();
        $viewController->incrementViews($page);
    
        // Número total de vistas (contando múltiples vistas desde la misma IP)
        $pageTotalViews = $page->views->sum('views');
    
        // Número de vistas únicas (contando solo una vez por cada IP)
        $pageUniqueViews = $page->views->count();
    
        return view("{$theme}::frontend.page", compact('page', 'previousPage', 'nextPage', 'pageTotalViews', 'pageUniqueViews'));
    
    }
    
    
    public function listPages(Request $request)
    {
        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $pages = SitePage::where('status', 1)->orderBy('created_at', 'desc')->paginate(10);

        return view("{$theme}::frontend.page-list", compact('pages'));
    }
    
    //////////////////////////////////////////////////////////
    //////////////////// Posts //////////////////////////////
    ////////////////////////////////////////////////////////

    public function showPost($slug)
    {

        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $post = SitePost::where('slug', $slug)->firstOrFail();
    
        // Obtener el contenido del post
        $content = $post->content;
    
        // Procesar el contenido del post para buscar y reemplazar los shortcodes de galería
        $shortcodeProcessor = new \Caimari\LaraFlex\Services\ShortcodeProcessor();
        $content = $shortcodeProcessor->parse_shortcodes($content);
    
        // Asignar el contenido procesado de nuevo a la propiedad content del post
        $post->content = $content;
    
        // Obtener el post anterior (anterior al actual)
        $previousPost = SitePost::where('id', '<', $post->id)
            ->orderBy('id', 'desc')
            ->first();
    
        // Obtener el post siguiente (post siguiente al actual)
        $nextPost = SitePost::where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->first();
    
        // Crea una instancia del ViewController e invoca el método incrementViews
        $viewController = new \Caimari\LaraFlex\Controllers\ViewController();
        $viewController->incrementViews($post);
    
        // Número total de vistas (contando múltiples vistas desde la misma IP)
        $PostTotalViews = $post->views->sum('views');
    
        // Número de vistas únicas (contando solo una vez por cada IP)
        $PostUniqueViews = $post->views->count();
    
        // Obtener los comentarios del post con sus respuestas
        $comments = $post->comments()->with('replies')->get();
    
        return view("{$theme}::frontend.post", compact('post', 'previousPost', 'nextPost', 'PostTotalViews', 'PostUniqueViews', 'comments'));
    }
    
    
    public function listPosts(Request $request)
    {
        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $posts = SitePost::orderBy('created_at', 'desc')->paginate(10);
        
        return view("{$theme}::frontend.post-list", compact('posts'));
    }
    


        //////////////////////////////////////////////////////////
        //////////////////// Categories ////////////////////////
        ///////////////////////////////////////////////////////
    

    public function showCategory($slug)
    {

        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $category = SitePostCategory::where('slug', $slug)->firstOrFail();
        
        // Obtiene todos los posts asociados con esta categoría
        $posts = $category->posts;

        $category->views()->create([
            'ip_address' => request()->ip()  // Aquí se almacena la dirección IP del visitante
        ]);
        
        return view("{$theme}::frontend.category", ['category' => $category, 'posts' => $posts]);
    }
    


    public function listCategories()
    {

        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

        $categories = SitePostCategory::all();
        
        return view("{$theme}::frontend.categories-list", ['categories' => $categories]);
    }


       //////////////////////////////////////////////////////////
        //////////////////// Tags ////////////////////////
        ///////////////////////////////////////////////////////


        
   public function showTag($slug)
   {

        // Obtener el nombre del tema activo
        $theme = $this->getActiveTheme();

       $tag = SitePostTag::where('slug', $slug)->firstOrFail();
   
       // Carga los posts asociados con este tag
       $tag->load('posts');
   
       $ipAddress = request()->ip();
   
       // Busca una vista existente de la misma dirección IP
       $view = $tag->views()->where('ip_address', $ipAddress)->first();
       if($view) {
           // Si existe una vista de esta dirección IP, incrementa el contador de vistas
           $view->increment('views');
           $view->save();

       } else {
           // Si no existe una vista de esta dirección IP, crea una nueva
           $tag->views()->create([
               'ip_address' => $ipAddress,
               'views' => 1  // Inicia el contador de vistas en 1
           ]);
       }
   
       // Retornamos la vista con el tag y sus posts
       return view("{$theme}::frontend.tag", ['tag' => $tag, 'posts' => $tag->posts]);
   }
   

        public function listTags()
        {

            // Obtener el nombre del tema activo
            $theme = $this->getActiveTheme();
            $tags = SitePostTag::all();
        
            return view("{$theme}::frontend.tags-list", ['tags' => $tags]);
        }
        
}
