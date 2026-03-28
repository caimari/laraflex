<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Caimari\LaraFlex\Models\Theme;

class ThemeOptionsController extends Controller
{

    public function index()
    {
        $activeTheme = Theme::where('active', 1)->first();
 

        if (!$activeTheme) {
            return redirect()->route('home')->with('error', 'No active theme found');
        }

         $dynamicBtnOptions = json_decode($activeTheme->theme_dynamic_btn_options, true);

        
        // Decode theme options JSON if it exists
        $themeOptions = !empty($activeTheme->theme_options) ? json_decode($activeTheme->theme_options, true) : [];
    
        $options = [
                
                'name' => $activeTheme->name,
                'version' => $activeTheme->version,
                'site_title' => $activeTheme->site_title,
                'site_url' => $activeTheme->site_url,
                'site_description' => $activeTheme->site_description,
                'site_keywords' => $activeTheme->site_keywords,
                'site_email' => $activeTheme->site_email,
                'facebook' => $activeTheme->facebook,
                'twitter' => $activeTheme->twitter,
                'linkedin' => $activeTheme->linkedin,
                'google_plus' => $activeTheme->google_plus,
                'github' => $activeTheme->github,
                'pinterest' => $activeTheme->pinterest,
                'instagram' => $activeTheme->instagram,
                'rss' => $activeTheme->rss,
                'youtube' => $activeTheme->youtube,
                'vimeo' => $activeTheme->vimeo,
                'tiktok' => $activeTheme->tiktok,
                'snapchat' => $activeTheme->snapchat,
                'reddit' => $activeTheme->reddit,
                'telegram' => $activeTheme->telegram,
                'whatsapp' => $activeTheme->whatsapp,
                'site_phone' => $activeTheme->site_phone,
                'service_provider' => $activeTheme->service_provider,
                'description' => $activeTheme->description,
                'menu_locations' => $activeTheme->menu_locations,
                'theme_options' => $activeTheme->theme_options,
                'provider' => $activeTheme->provider,
                'site_name' => $activeTheme->site_name,
                'logo' => $activeTheme->logo,
                'logo_2' => $activeTheme->logo_2,
                'favicon' => $activeTheme->favicon,
                'title_active' => $activeTheme->title_active,
                'header_image_active' => $activeTheme->header_image_active,
                'header_sub_bar_active' => $activeTheme->header_sub_bar_active,
                'breadcrumb_active' => $activeTheme->breadcrumb_active,
                'main_content_active' => $activeTheme->main_content_active,
                'footer_active' => $activeTheme->footer_active,
                'footer_2_active' => $activeTheme->footer_2_active,
                'footer_copyright_active' => $activeTheme->footer_copyright_active,
                'created_at' => $activeTheme->created_at,
                'updated_at' => $activeTheme->updated_at,
                'color_default' => $activeTheme->color_default,
                'color_default_2' => $activeTheme->color_default_2,
                'sub_bar_color' => $activeTheme->sub_bar_color,
                'sub_bar_text_color' => $activeTheme->sub_bar_text_color,
                'header_logo_color' => $activeTheme->header_logo_color,
                'navbar_background_color' => $activeTheme->navbar_background_color,
                'navbar_text_color' => $activeTheme->navbar_text_color,
                'footer_background_color' => $activeTheme->footer_background_color,
                'footer_text_color' => $activeTheme->footer_text_color,
                'custom_css' => $activeTheme->custom_css,
                'custom_js' => $activeTheme->custom_js,
                'captcha' => $activeTheme->captcha,
                'site_company' => $activeTheme->site_company,
                'country' => $activeTheme->country,
                'address' => $activeTheme->address,
                'city' => $activeTheme->city,
                'zip' => $activeTheme->zip,
                'google_analytics' => $activeTheme->google_analytics,
                'google_adsense' => $activeTheme->google_adsense,
                'currency_symbol' => $activeTheme->currency_symbol,
                'date_format' => $activeTheme->date_format,
                'registration_active' => $activeTheme->registration_active,
                'account_verification' => $activeTheme->account_verification,
                'requests_verify_account' => $activeTheme->requests_verify_account,
                'limit_categories' => $activeTheme->limit_categories,
                'disable_login_register_email' => $activeTheme->disable_login_register_email,
                'disable_contact' => $activeTheme->disable_contact,
                'status_pwa' => $activeTheme->status_pwa,
                'sidebar_post_cat_active' => $activeTheme->sidebar_post_cat_active, 
                'sidebar_post_tab_active' => $activeTheme->sidebar_post_tab_active,
                'sidebar_text_widget_active' => $activeTheme->sidebar_text_widget_active, 
                'sidebar_search_active' => $activeTheme->sidebar_search_active, 
            ];
        
        return view('laraflex::admin.theme-options.index', compact('options', 'dynamicBtnOptions'));
    }
    

            public function update(Request $request)
            {
                $activeTheme = Theme::where('active', 1)->first();
                
                $activeTheme->name = $request->input('name');              
                $activeTheme->version = $request->input('version');
                $activeTheme->site_title = $request->input('site_title');
                $activeTheme->site_url = $request->input('site_url');
                $activeTheme->site_description = $request->input('site_description');
                $activeTheme->site_keywords = $request->input('site_keywords');
                $activeTheme->site_email = $request->input('site_email');
                $activeTheme->facebook = $request->input('facebook');
                $activeTheme->twitter = $request->input('twitter');
                $activeTheme->linkedin = $request->input('linkedin');
                $activeTheme->google_plus = $request->input('google_plus');
                $activeTheme->github = $request->input('github');
                $activeTheme->pinterest = $request->input('pinterest');
                $activeTheme->instagram = $request->input('instagram');
                $activeTheme->rss = $request->input('rss');
                $activeTheme->youtube = $request->input('youtube');
                $activeTheme->vimeo = $request->input('vimeo');
                $activeTheme->tiktok = $request->input('tiktok');
                $activeTheme->snapchat = $request->input('snapchat');
                $activeTheme->reddit = $request->input('reddit');
                $activeTheme->telegram = $request->input('telegram');
                $activeTheme->whatsapp = $request->input('whatsapp');
                $activeTheme->site_phone = $request->input('site_phone');
                $activeTheme->service_provider = $request->input('service_provider');
               // $activeTheme->active = $request->input('active');
                $activeTheme->description = $request->input('description');
             //   $activeTheme->menu_locations = $request->input('menu_locations');
             //   $activeTheme->theme_options = $request->input('theme_options');
             //   $activeTheme->provider = $request->input('provider');
                $activeTheme->site_name = $request->input('site_name');
                $activeTheme->logo = $request->input('logo');
                $activeTheme->logo_2 = $request->input('logo_2');
                $activeTheme->favicon = $request->input('favicon');
               // $activeTheme->title_active = $request->input('title_active');
               //   $activeTheme->header_image_active = $request->input('header_image_active');
               //   $activeTheme->header_sub_bar_active = $request->input('header_sub_bar_active');
               //   $activeTheme->breadcrumb_active = $request->input('breadcrumb_active');
              //    $activeTheme->main_content_active = $request->input('main_content_active');
               //   $activeTheme->footer_active = $request->input('footer_active');
                $activeTheme->footer_2_active = $request->input('footer_2_active');
              //    $activeTheme->footer_copyright_active = $request->input('footer_copyright_active');
             //   $activeTheme->created_at = $request->input('created_at');
                $activeTheme->updated_at = $request->input('updated_at');
                $activeTheme->color_default = $request->input('color_default');
                $activeTheme->color_default_2 = $request->input('color_default_2');
                $activeTheme->sub_bar_color = $request->input('sub_bar_color');
                $activeTheme->sub_bar_text_color = $request->input('sub_bar_text_color');
                $activeTheme->header_logo_color = $request->input('header_logo_color');
                
                $activeTheme->navbar_background_color = $request->input('navbar_background_color');
                $activeTheme->navbar_text_color = $request->input('navbar_text_color');
                $activeTheme->footer_background_color = $request->input('footer_background_color');
                $activeTheme->footer_text_color = $request->input('footer_text_color');
                $activeTheme->custom_css = $request->input('custom_css');
                $activeTheme->custom_js = $request->input('custom_js');
                //  $activeTheme->captcha = $request->input('captcha');
                $activeTheme->site_company = $request->input('site_company');
                $activeTheme->country = $request->input('country');
                $activeTheme->address = $request->input('address');
                $activeTheme->city = $request->input('city');
                $activeTheme->zip = $request->input('zip');
                $activeTheme->google_analytics = $request->input('google_analytics');
                $activeTheme->google_adsense = $request->input('google_adsense');
                $activeTheme->currency_symbol = $request->input('currency_symbol');
                $activeTheme->date_format = $request->input('date_format');
               //   $activeTheme->registration_active = $request->input('registration_active');
               //   $activeTheme->account_verification = $request->input('account_verification');
               //   $activeTheme->requests_verify_account = $request->input('requests_verify_account');
               //   $activeTheme->limit_categories = $request->input('limit_categories');
               //   $activeTheme->disable_login_register_email = $request->input('disable_login_register_email');
              //    $activeTheme->disable_contact = $request->input('disable_contact');
              //    $activeTheme->status_pwa = $request->input('status_pwa');
              $activeTheme->sidebar_post_cat_active = $request->input('sidebar_post_cat_active');
              $activeTheme->sidebar_post_tab_active = $request->input('sidebar_post_tab_active');
              $activeTheme->sidebar_text_widget_active = $request->input('sidebar_text_widget_active');
              $activeTheme->sidebar_search_active = $request->input('sidebar_search_active');
                
                $activeTheme->save();
                
                return redirect()->route('theme.options')->with('success', 'Theme options updated successfully.');
            }


}
