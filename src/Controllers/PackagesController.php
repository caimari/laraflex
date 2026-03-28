<?php

namespace Caimari\LaraFlex\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;


use Caimari\LaraFlex\Models\SitePage;
use Caimari\LaraFlex\Models\GeneralSettings;

class PackagesController extends Controller
{
    
    public function index()
    {
    
        return view('laraflex::admin.packages.index');
    }


    public function composerInstall(Request $request)
    {
        $packageName = $request->input('package_name');

        // Ejecutar Composer con la versión específica de PHP
        $output = shell_exec('/opt/plesk/php/7.4/bin/php /usr/bin/composer require ' . $packageName);

        // Si ocurre un error de PHP, intentar nuevamente con la versión predeterminada de PHP
        if (strpos($output, 'Command not found') !== false) {
            $output = shell_exec('composer require ' . $packageName);
        }

        return response()->json(['result' => $output]);
    }
}
