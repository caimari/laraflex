<?php

namespace Caimari\LaraFlex\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Caimari\LaraFlex\Models\Theme;


class ThemeCreateController extends Controller

{


public function index()
{
    $themes = Theme::all();
    $processedZipPath = '/processed.zip';

    return view('laraflex::admin.themes.create', compact('themes', 'processedZipPath'));
}



    public function process(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'zipFile' => 'required|mimes:zip',
            'newValueLower' => 'required',
            'newValueUpper' => 'required',
        ]);
    
        try {
            // Obtener el archivo zip y los nuevos valores
            $zipFile = $request->file('zipFile');
            $newValueLower = $request->input('newValueLower');
            $newValueUpper = $request->input('newValueUpper');
    
            // Crear una carpeta temporal para extraer los archivos zip
            $tempFolder = public_path('temp_folder');
            if (!File::exists($tempFolder)) {
                File::makeDirectory($tempFolder);
            }
    
            // Extraer el contenido del archivo zip
            $zip = new ZipArchive;
            $zip->open($zipFile->getRealPath());
            $zip->extractTo($tempFolder);
            $zip->close();
    
            // Cambiar el valor en los archivos y renombrar archivos
            $this->processFiles($tempFolder, $request->input('oldValueLower'), $request->input('oldValueUpper'), $newValueLower, $newValueUpper);
    
            // Crear un nuevo archivo zip
            $newZipPath = public_path('processed.zip');
            $zip = new ZipArchive;
            $zip->open($newZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempFolder));
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = str_replace($tempFolder, '', $filePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
    
            // Eliminar la carpeta temporal
            File::deleteDirectory($tempFolder);
    
            // Establecer el mensaje de éxito en la sesión flash
            $request->session()->flash('success', 'Proceso completado con éxito');
    
            // Redirigir a una nueva página después de la descarga del archivo
            return redirect()->route('theme.index');
    
        } catch (\Exception $e) {
            // Eliminar la carpeta temporal en caso de error
            File::deleteDirectory($tempFolder);
    
            // Manejar el error y mostrar un mensaje de estado
            return back()->with('error', 'Ha ocurrido un error durante el procesamiento del archivo: ' . $e->getMessage());
        }
    }
    
    
    private function processFiles($folder, $oldValueLower, $oldValueUpper, $newValueLower, $newValueUpper)
{
    $files = File::allFiles($folder);
    foreach ($files as $file) {
        $contents = file_get_contents($file);
        $newContents = $contents;

        if (str_contains($contents, $oldValueLower)) {
            $newContents = str_replace($oldValueLower, $newValueLower, $contents); // Reemplazar el valor antiguo por el nuevo en minúsculas
            $newContents = str_replace($oldValueUpper, $newValueUpper, $newContents); // Reemplazar el valor antiguo por el nuevo en mayúsculas
        }

        file_put_contents($file, $newContents);

        // Renombrar archivos si es necesario
        if (strpos($file, 'oldName') !== false) {
            $newName = str_replace('oldName', $newValueLower, $file);
            File::move($file, $newName);
        }
    }
}

    


}