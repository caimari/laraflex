<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Session;

use Caimari\LaraFlex\Models\GeneralSettings;


class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSettings::first();

        if ($settings) {
            // Se encontró un registro en la base de datos
            return view('laraflex::admin.general-settings.index', ['settings' => $settings]);
        } else {
            // No se encontraron registros en la base de datos
            // Maneja el caso cuando no hay registros
        }
    }

    public function update(Request $request)
    {
        $settings = GeneralSettings::first();
        
        if ($settings) {
            // Se encontró un registro en la base de datos
            $request->validate([
                'site_title' => 'required',
                'site_description' => 'required',
            ]);
    
        // Actualizar los valores de los campos en base a los datos del formulario
        $settings->site_title = $request->input('site_title');
        $settings->site_email = $request->input('site_email');
        $settings->site_description = $request->input('site_description');
        $settings->site_phone = $request->input('site_phone');
        $settings->keywords = $request->input('keywords');
        $settings->facebook = $request->input('facebook');
        $settings->twitter = $request->input('twitter');
        $settings->linkedin = $request->input('linkedin');
        $settings->google_plus = $request->input('google_plus');
        $settings->github = $request->input('github');
        $settings->pinterest = $request->input('pinterest');
        $settings->instagram = $request->input('instagram');
        $settings->rss = $request->input('rss');
        $settings->youtube = $request->input('youtube');
        $settings->vimeo = $request->input('vimeo');
        $settings->tiktok = $request->input('tiktok');
        $settings->snapchat = $request->input('snapchat');
        $settings->reddit = $request->input('reddit');
        $settings->telegram = $request->input('telegram');
        $settings->whatsapp = $request->input('whatsapp');

        $settings->use_cookies_page = $request->input('use_cookies_page');
        
    
        $settings->save();
    
        return redirect()->route('admin.general-settings.index')->with('success', 'Settings updated successfully');
    } else {
        // No se encontraron registros en la base de datos
        // Maneja el caso cuando no hay registros
    }
}
                    // Establecer paginas como pagina de inicio
                    public function setHomePage($id)
                    {
                        $homePageSetting = GeneralSettings::first();
                
                        if ($homePageSetting) {
                            // Se encontró un registro en la base de datos
                
                            // Resto del código existente
                            $homePageSetting->content_type = 'page';
                            $homePageSetting->content_id = $id;
                            $homePageSetting->save();
                
                            return response()->json(['status' => 'success']);
                        } else {
                            // No se encontraron registros en la base de datos
                            // Maneja el caso cuando no hay registros
                        }
                    }
                    
                    // Establecer posts como pagina de inicio
                    public function setHomePost($id)
                    {
                        $homePostSetting = GeneralSettings::first();

                        if ($homePostSetting) {
                            // Se encontró un registro en la base de datos

                            // Resto del código existente
                            $homePostSetting->content_type = 'post';
                            $homePostSetting->content_id = $id;
                            $homePostSetting->save();

                            return response()->json(['status' => 'success']);
                        } else {
                            // No se encontraron registros en la base de datos
                            // Maneja el caso cuando no hay registros
                        }
                    }

                    // Establecer conde snippets como pagina de inicio
                    public function setHomeSnippet($id)
                    {
                        $homePageSetting = GeneralSettings::first();
                    
                        if ($homePageSetting) {
                            // Se encontró un registro en la base de datos
                    
                            // Resto del código existente
                            $homePageSetting->content_type = 'snippet';
                            $homePageSetting->content_id = $id;
                            $homePageSetting->save();
                    
                            return response()->json(['status' => 'success']);
                        } else {
                            // No se encontraron registros en la base de datos
                            // Maneja el caso cuando no hay registros
                        }
                    }
                    
                    
}
                    

