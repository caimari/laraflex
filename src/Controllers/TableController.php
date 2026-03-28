<?php
namespace Caimari\LaraFlex\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TableController extends Controller
{



    public function index()
    {
        // Obtén la lista de todas las tablas en la base de datos
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
    
        $tables = [];
        foreach ($tableNames as $tableName) {
            // Obtén la lista de columnas de la tabla
            $columns = Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($tableName);
    
            $tables[$tableName] = $columns;
        }
    
        // Obtén la lista de tablas que tienen un CRUD generado
        $tablesWithCrud = DB::table('admin_generated')->pluck('name')->toArray();
    
        return view('laraflex::admin.tables.index', compact('tables', 'tablesWithCrud'));
    }
    


    /////////////////////// INDEX CRUD //////////////////////////

    public function generateIndex(Request $request)
    {

    // Obtén las tablas y columnas que se seleccionaron
    $tablesAndColumns = $request->input('columns');

    // Verifica que se hayan seleccionado tablas
    if (!$tablesAndColumns) {
        return redirect()->back()->with('error', 'No tables selected.');
    }   
    
    // Itera sobre cada tabla
    foreach ($tablesAndColumns as $table => $columns) {
        if (!empty($columns)) {
            $normalizedTableName = $this->normalizeTableName($table);
            $tableNameForRoute = Str::kebab($normalizedTableName);  // Aquí definimos $tableNameForRoute
            $controllerName = Str::studly($normalizedTableName) . 'Controller';
              $controllerPath = base_path('vendor/caimari/laraflex/src/Controllers/Generated/' . $controllerName . '.php');
    
            // Verifica si el archivo del controlador existe
            if (!File::exists($controllerPath)) {
                // Crea el contenido del controlador desde cero
                $controllerContent = "<?php\n\n";
                $controllerContent .= "namespace Caimari\\LaraFlex\\Controllers\\Generated;\n\n";
                $controllerContent .= "use Illuminate\Http\Request;\n";
                $controllerContent .= "use Illuminate\Support\Facades\DB;\n\n";
                $controllerContent .= "use App\Http\Controllers\Controller;\n\n";
                $controllerContent .= "class " . $controllerName . " extends Controller\n";
                $controllerContent .= "{\n";
            } else {
                // Carga el contenido existente del controlador
                $controllerContent = File::get($controllerPath);
    
                // Busca la función index existente y la elimina
                $patternIndex = '/public function index\\(\\)\\n    \\{.*?\\n    \\}\\n/s';
                if (preg_match($patternIndex, $controllerContent)) {
                    $controllerContent = preg_replace($patternIndex, "", $controllerContent);
                }
    
                // Busca la función destroy existente y la elimina
                $patternDestroy = '/public function destroy\\(\\$id\\)\\n    \\{.*?\\n    \\}\\n/s';
                if (preg_match($patternDestroy, $controllerContent)) {
                    $controllerContent = preg_replace($patternDestroy, "", $controllerContent);
                }
    
                // Encuentra el último cierre de llave } y lo elimina
                $lastBracePosition = strrpos($controllerContent, '}');
                if ($lastBracePosition !== false) {
                    $controllerContent = substr($controllerContent, 0, $lastBracePosition);
                }
            }
    
            // Añade la función index al contenido del controlador
            $controllerContent .= "    public function index()\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        \$items = DB::table('" . $table . "')->get();\n";
            $controllerContent .= "        \$modelName = '" . $table . "';\n";  // Añade esta línea
            $controllerContent .= "        return view('generated." . $tableNameForRoute . ".index', compact('items', 'modelName'));\n";
            $controllerContent .= "    }\n\n";


            // Añade la función destroy al contenido del controlador
            $controllerContent .= "    public function destroy(\$id)\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        DB::table('" . $table . "')->where('id', '=', \$id)->delete();\n";
            $controllerContent .= "        return redirect()->route('crud." . $tableNameForRoute . ".index')->with('success', 'Record deleted successfully');\n";
            $controllerContent .= "    }\n";
    
            // Cierra la clase
            $controllerContent .= "}\n";
    
            // Guarda el archivo del controlador en el directorio correcto
            File::put($controllerPath, $controllerContent);

            // Lee el contenido del controlador
            $controllerContent = File::get($controllerPath);

                // Obtiene la ruta de la plantilla stub
                $stubPath = base_path('vendor/caimari/laraflex/src/resources/stubs/index.stub');

                // Lee el contenido de la plantilla stub
                $stubContent = file_get_contents($stubPath);

                // Verifica si los métodos create, edit y destroy existen en el controlador
                $canCreate = preg_match('/public function create\\(.*\\)\\s*{[^}]*}/s', $controllerContent) ? '@' : '';
                $canEdit = preg_match('/public function edit\\(.*\\)\\s*{[^}]*}/s', $controllerContent) ? '@' : '';
                $canDelete = preg_match('/public function destroy\\(.*\\)\\s*{[^}]*}/s', $controllerContent) ? '@' : '';
                

                // Reemplaza los marcadores de la plantilla con valores dinámicos
                // $modelName = Str::camel($normalizedTableName);
                $modelName = Str::camel($normalizedTableName);
                // Convertir $modelName a kebab-case
                $modelNameKebab = Str::kebab($modelName);

                //$stubContent = str_replace('{$modelName}', $modelName, $stubContent);
                $stubContent = str_replace('{$modelName}', $modelNameKebab, $stubContent);

                if($canCreate == '@') {
                    $stubContent = str_replace('@can_create', '', $stubContent);
                    $stubContent = str_replace('@endcan_create', '', $stubContent);
                } else {
                    $stubContent = str_replace('@can_create', '<!--', $stubContent); // Reemplazado por comentario
                    $stubContent = str_replace('@endcan_create', '-->', $stubContent); // Reemplazado por cierre de comentario
                }

                if($canEdit == '@') {
                    $stubContent = str_replace('@can_edit', '', $stubContent);
                    $stubContent = str_replace('@endcan_edit', '', $stubContent);
                } else {
                    $stubContent = str_replace('@can_edit', '<!--', $stubContent); // Reemplazado por comentario
                    $stubContent = str_replace('@endcan_edit', '-->', $stubContent); // Reemplazado por cierre de comentario
                }

                if($canDelete == '@') {
                    $stubContent = str_replace('@can_delete', '', $stubContent);
                    $stubContent = str_replace('@endcan_delete', '', $stubContent);
                } else {
                    $stubContent = str_replace('@can_delete', '<!--', $stubContent); // Reemplazado por comentario
                    $stubContent = str_replace('@endcan_delete', '-->', $stubContent); // Reemplazado por cierre de comentario
                }


                // Crea los encabezados de las columnas y las celdas de datos
                $columnHeaders = '';
                $columnData = '';
                foreach ($columns as $column) {
                    $columnHeaders .= "<th>{$column}</th>\n";
                    $columnData .= "<td>{{\$item->{$column}}}</td>\n";
                }

                // Reemplaza los marcadores de la plantilla con valores dinámicos
                $stubContent = str_replace('@column_headers', $columnHeaders, $stubContent);
                $stubContent = str_replace('@column_data', $columnData, $stubContent);

                // Guarda la vista generada
                $viewFolderPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $tableNameForRoute);
                $viewFilePath = $viewFolderPath . '/index.blade.php';

                    // Comprueba si la carpeta de la vista existe. Si no es así, la crea.
                    if (!File::exists($viewFolderPath)) {
                        File::makeDirectory($viewFolderPath, 0755, true);
                    }

                    // Guarda el archivo en la carpeta que acabas de asegurarte que existe.
                    File::put($viewFilePath, $stubContent);
                }

                // ROUTES SYSTEM CREATE INDEX
                 
                $routesPath = base_path('vendor/caimari/laraflex/src/routes/generated.php');
                $routesContent = File::get($routesPath);

                // Construye la declaración 'use' para el controlador
                $useStatement = "use Caimari\\LaraFlex\\Controllers\\Generated\\" . $controllerName . ";";

               
                $routeName = 'crud.' . $tableNameForRoute . '.index';

                // Construye las cadenas para las rutas
                $routeIndexString = "\nRoute::get('/" . $tableNameForRoute . "', [" . $controllerName . "::class, 'index'])->name('crud." . $tableNameForRoute . ".index');";
                $routeDestroyString = "\nRoute::delete('/" . $tableNameForRoute . "/{id}', [" . $controllerName . "::class, 'destroy'])->name('crud." . $tableNameForRoute . ".destroy');";

                // Añade las rutas
                $this->addRoutes($useStatement, $routeIndexString, $routeDestroyString);

                DB::table('admin_generated')->updateOrInsert(
                    ['name' => $table], // columnas y valores que deben ser únicos
                    ['route' => $routeName] // columnas y valores que deben ser insertados o actualizados
                );
                
                  // Limpia el caché de vistas y rutas
                    \Artisan::call('cache:clear');
                    \Artisan::call('route:cache');

                return redirect()->back()->with('success', 'Controller(s), views, and routes created successfully.');
               
                }
            }
            



            private function normalizeTableName($name) {
                // Reemplaza los guiones y subrayados con espacios
                $nameWithSpaces = str_replace(['-', '_'], ' ', $name);
            
                // Convierte a snake_case
                $snakeCaseName = Str::snake($nameWithSpaces);
            
                // Reemplaza los guiones bajos por guiones
                $kebabCaseName = str_replace('_', '-', $snakeCaseName);
            
                // Devuelve el nombre normalizado
                return $kebabCaseName;
            }
            




            //////////////////// CRUD CREATE //////////////////////////


            public function generateCreate(Request $request)
            {
                // Obtén las tablas y columnas que se seleccionaron
                $tablesAndColumns = $request->input('columns');
                
                // Obtén los tipos de las columnas seleccionadas
                $columnTypes = $request->input('columnTypes');
            
                // Verifica que se hayan seleccionado tablas
                if (!$tablesAndColumns) {
                    return redirect()->back()->with('error', 'No tables selected.');
                }
    
               // Itera sobre cada tabla
           
            foreach ($tablesAndColumns as $table => $columns) {
            if (!empty($columns)) {
            // Normaliza el nombre de la tabla
            $normalizedTableName = $this->normalizeTableName($table);
            $tableNameForRoute = Str::kebab($this->normalizeTableName($normalizedTableName)); // Utiliza Str::kebab()
            $controllerName = Str::studly($normalizedTableName) . 'Controller';
            $controllerPath = base_path('vendor/caimari/laraflex/src/Controllers/Generated/' . $controllerName . '.php');

                // Verifica si el archivo del controlador existe
                if (!File::exists($controllerPath)) {
                    // Crea el contenido del controlador desde cero
                    $controllerContent = "<?php\n\n";
                    $controllerContent .= "namespace Caimari\\LaraFlex\\Controllers\\Generated;\n\n";
                    $controllerContent .= "use Illuminate\Http\Request;\n";
                    $controllerContent .= "use Illuminate\Support\Facades\DB;\n\n";
                    $controllerContent .= "use App\Http\Controllers\Controller;\n\n";
                    $controllerContent .= "class " . $controllerName . " extends Controller\n";
                    $controllerContent .= "{\n";
                } else {
                    // Carga el contenido existente del controlador
                    $controllerContent = File::get($controllerPath);
    
                    // Busca la función create existente y la elimina
                    $patternCreate = '/public function create\\(\\)\\n    \\{.*?\\n    \\}\\n/s';
                    if (preg_match($patternCreate, $controllerContent)) {
                        $controllerContent = preg_replace($patternCreate, "", $controllerContent);
                    }
    
                    // Busca la función store existente y la elimina
                    $patternStore = '/public function store\\(Request \\$request\\)\\n    \\{.*?\\n    \\}\\n/s';
                    if (preg_match($patternStore, $controllerContent)) {
                        $controllerContent = preg_replace($patternStore, "", $controllerContent);
                    }
    
                    // Encuentra el último cierre de llave } y lo elimina
                    $lastBracePosition = strrpos($controllerContent, '}');
                    if ($lastBracePosition !== false) {
                        $controllerContent = substr($controllerContent, 0, $lastBracePosition);
                    }
                }
    
               
                    // Define los nuevos métodos create y store
                    $newCreateMethod = "    public function create()\n    {\n        return view('generated." . $normalizedTableName . ".create', ['modelName' => '" . $normalizedTableName . "']);\n    }\n";
                    
                    $newStoreMethod = "    public function store(Request \$request)\n    {\n        \$data = \$request->validate([\n";
                    foreach ($columns as $column) {
                        $newStoreMethod .= "            '{$column}' => 'required',\n";
                    }
                    $newStoreMethod .= "        ]);\n\n";
                    $newStoreMethod .= "        DB::table('" . $normalizedTableName . "')->insert(\$data);\n";
                    $newStoreMethod .= "        return redirect()->route('crud." . $tableNameForRoute . ".index')->with('success', 'Record created successfully');\n    }\n";
                
                    // Añade el nuevo método create y store al contenido del controlador
                   $controllerContent .= "\n" . $newCreateMethod . "\n" . $newStoreMethod;

                    // Cierra la clase
                    $controllerContent .= "}\n";

                    // Finalmente, escribe el contenido actualizado en el archivo del controlador
                    File::put($controllerPath, $controllerContent);
   
        

            // Obtiene la ruta de la plantilla stub
            $stubPath = base_path('vendor/caimari/laraflex/src/resources/stubs/create.stub');

            // Lee el contenido de la plantilla stub
            $stubContent = file_get_contents($stubPath);


// Log::info($columnTypes);

// Crea los campos del formulario para cada columna
$formFields = '';
$additionalScripts = '';

foreach ($columns as $column) {
    // Obtiene el tipo de columna para esta columna
    $columnType = $columnTypes[$table][$column];

    if ($columnType == 'ckeditor') {
        $formFields .= <<<EOT
    <div class="form-group">
        <label for="{$column}">{$column}</label>
        <textarea class="form-control" id="{$column}" name="{$column}" required></textarea>
    </div>\n
    EOT;

        $additionalScripts .= <<<EOT
    <script>
        CKEDITOR.replace( '{$column}' );
    </script>\n
    EOT;
    } elseif  ($columnType == 'text') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="text" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'textarea') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <textarea class="form-control" id="{$column}" name="{$column}" required></textarea>
</div>\n
EOT;
    } elseif ($columnType == 'select') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <select class="form-control" id="{$column}" name="{$column}" required>
        <!-- Aquí puedes agregar tus opciones de select -->
    </select>
</div>\n
EOT;
    } elseif ($columnType == 'date') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="date" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'password') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="password" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'radio') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <!-- Aquí puedes agregar tus opciones de radio -->
    <input type="radio" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'checkbox') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="checkbox" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'number') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="number" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
    } elseif ($columnType == 'email') {
        $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="email" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
} elseif ($columnType == 'time') {
    $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="time" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
} elseif ($columnType == 'file') {
    $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="file" class="form-control" id="$column" name="$column" required>
</div>\n
EOT;
}


}

            // Reemplaza los marcadores de la plantilla con valores dinámicos
            //$stubContent = str_replace('$modelName', Str::camel($tableNameForRoute), $stubContent);
            $stubContent = str_replace('$modelName', $tableNameForRoute, $stubContent);

            $stubContent = str_replace('$formFields', $formFields, $stubContent);
            $stubContent = str_replace('$additionalScripts', $additionalScripts, $stubContent);

            // Guarda la vista generada
            //$viewPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $tableNameForRoute . '/create.blade.php');
            $viewPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $this->normalizeTableName($tableNameForRoute) . '/create.blade.php');

            File::put($viewPath, $stubContent);


                /// ROUTES SYSTEM CREATE CRUD
                $tableNameForRoute = $this->normalizeTableName($normalizedTableName);

                // Construye la declaración 'use' para el controlador
                $useStatement = "use Caimari\\LaraFlex\\Controllers\\Generated\\" . $controllerName . ";";
    
                // Construye las cadenas para las rutas
                $routeCreateString = "\nRoute::get('/" . $tableNameForRoute . "/create', [" . $controllerName . "::class, 'create'])->name('crud." . $tableNameForRoute . ".create');";
             // $routeStoreString = "\nRoute::post('/" . $tableNameForRoute . "/store', [" . $controllerName . "::class, 'store'])->name('crud." . $tableNameForRoute . ".store');";
                $routeStoreString = "\nRoute::post('/" . $tableNameForRoute . "/store', [" . $controllerName . "::class, 'store'])->name('crud." . $tableNameForRoute . ".store');";

                // Añade las rutas invocando a la funcion addRoutes
                $this->addRoutes($useStatement, $routeCreateString, $routeStoreString);

                                  // Limpia el caché de vistas y rutas
                                  \Artisan::call('cache:clear');
                                  \Artisan::call('route:cache');
            }
        }
    
        return redirect()->back()->with('success', 'CRUD for create functionality generated successfully.');
    }
    


   //////////////////// CRUD EDIT //////////////////////////

    public function generateEdit(Request $request)
    {
        // Obtén las tablas y columnas que se seleccionaron
        $tablesAndColumns = $request->input('columns');

    // Verifica que se hayan seleccionado tablas
    if (!$tablesAndColumns) {
        return redirect()->back()->with('error', 'No tables selected.');
    }


        // Itera sobre cada tabla
        foreach ($tablesAndColumns as $table => $columns) {
         if (!empty($columns)) {
        $normalizedTableName = $this->normalizeTableName($table);
        $tableNameForRoute = Str::kebab($normalizedTableName);
        $controllerName = Str::studly($normalizedTableName) . 'Controller';
        $controllerPath = base_path('vendor/caimari/laraflex/src/Controllers/Generated/' . $controllerName . '.php');



            // Verifica si el archivo del controlador existe
            if (!File::exists($controllerPath)) {
                // Crea el contenido del controlador desde cero
                $controllerContent = "<?php\n\n";
                $controllerContent .= "namespace Caimari\\LaraFlex\\Controllers\\Generated;\n\n";
                $controllerContent .= "use Illuminate\Http\Request;\n";
                $controllerContent .= "use Illuminate\Support\Facades\DB;\n\n";
                $controllerContent .= "use App\Http\Controllers\Controller;\n\n";
                $controllerContent .= "class " . $controllerName . " extends Controller\n";
                $controllerContent .= "{\n";
            } else {
                // Carga el contenido existente del controlador
                $controllerContent = File::get($controllerPath);
                
                // Busca la función edit existente y la elimina
                $patternEdit = '/public function edit\\(\\$id\\)\\n    \\{.*?\\n    \\}\\n/s';
                if (preg_match($patternEdit, $controllerContent)) {
                    $controllerContent = preg_replace($patternEdit, "", $controllerContent);
                }

                // Busca la función update existente y la elimina
                $patternUpdate = '/public function update\\(Request \\$request, \\$id\\)\\n    \\{.*?\\n    \\}\\n/s';
                if (preg_match($patternUpdate, $controllerContent)) {
                    $controllerContent = preg_replace($patternUpdate, "", $controllerContent);
                }

                // Encuentra el último cierre de llave } y lo elimina
                $lastBracePosition = strrpos($controllerContent, '}');
                if ($lastBracePosition !== false) {
                    $controllerContent = substr($controllerContent, 0, $lastBracePosition);
                }
            }

                // Old
                // Define los nuevos métodos edit y update
                // $newEditMethod = "    public function edit(\$id)\n    {\n        \$item = DB::table('" . $table . "')->find(\$id);\n        return view('generated." . $table . ".edit', compact('item'));\n    }\n";
               //  $newUpdateMethod = "    public function update(Request \$request, \$id)\n    {\n        \$data = \$request->validate([\n";
               //  foreach ($columns as $column) {
              //       $newUpdateMethod .= "            '{$column}' => 'required',\n";
              //   }
              //   $newUpdateMethod .= "        ]);\n\n";
             //    $newUpdateMethod .= "        DB::table('" . $table . "')->where('id', '=', \$id)->update(\$data);\n";
              //   $newUpdateMethod .= "        return redirect()->route('crud." . $table . ".index')->with('success', 'Record updated successfully');\n    }\n";


            // Define los nuevos métodos edit y update
            $newEditMethod = "    public function edit(\$id)\n    {\n        \$item = DB::table('" . $normalizedTableName . "')->find(\$id);\n        return view('generated." . $tableNameForRoute . ".edit', compact('item'));\n    }\n";
            $newUpdateMethod = "    public function update(Request \$request, \$id)\n    {\n        \$data = \$request->validate([\n";
            foreach ($columns as $column) {
                $newUpdateMethod .= "            '{$column}' => 'required',\n";
            }
            $newUpdateMethod .= "        ]);\n\n";
            $newUpdateMethod .= "        DB::table('" . $normalizedTableName . "')->where('id', '=', \$id)->update(\$data);\n";
            $newUpdateMethod .= "\n        return redirect()->route('crud." . $tableNameForRoute . ".index')->with('success', 'Record updated successfully');\n    }\n";

            // Añade el nuevo método edit y update al contenido del controlador
            $controllerContent .= "\n" . $newEditMethod . "\n" . $newUpdateMethod;

            // Cierra la clase
            $controllerContent .= "}\n";

            // Finalmente, escribe el contenido actualizado en el archivo del controlador
            File::put($controllerPath, $controllerContent);

            // Obtiene la ruta de la plantilla stub
            $stubPath = base_path('vendor/caimari/laraflex/src/resources/stubs/edit.stub');

            // Lee el contenido de la plantilla stub
            $stubContent = file_get_contents($stubPath);


// Crea los campos del formulario para cada columna
$formFields = '';
foreach ($columns as $column) {
    $formFields .= <<<EOT
<div class="form-group">
    <label for="{$column}">{$column}</label>
    <input type="text" class="form-control" id="{$column}" name="{$column}" value="{{\$item->{$column}}}" required>
</div>\n
EOT;
}

                        // Reemplaza los marcadores de la plantilla con valores dinámicos
                        $stubContent = str_replace('$modelName', Str::camel($tableNameForRoute), $stubContent);
                        $stubContent = str_replace('$formFields', $formFields, $stubContent);


                        // Old
                        //$viewPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $this->normalizeTableName($tableNameForRoute) . '/edit.blade.php');
                        // Guarda la vista generada
                        $viewPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $tableNameForRoute . '/edit.blade.php');
                        File::put($viewPath, $stubContent);

/////////////////////////// ROUTES EDIt CRUD SYSTEM ////////////////////////


                // Define el nombre para las rutas
                $tableNameForRoute = $this->normalizeTableName($normalizedTableName);

                // Construye la declaración 'use' para el controlador
                $useStatement = "use Caimari\\LaraFlex\\Controllers\\Generated\\" . $controllerName . ";";

                // Construye las cadenas para las rutas
                $routeEditString = "\nRoute::get('/" . $tableNameForRoute . "/edit/{id}', [" . $controllerName . "::class, 'edit'])->name('crud." . $tableNameForRoute . ".edit');";
                $routeUpdateString = "\nRoute::put('/" . $tableNameForRoute . "/update/{id}', [" . $controllerName . "::class, 'update'])->name('crud." . $tableNameForRoute . ".update');";

                // Añade las rutas invocando a la funcion addRoutes
                $this->addRoutes($useStatement, null, null, null, null, $routeEditString, $routeUpdateString);
                }

                                  // Limpia el caché de vistas y rutas
                                  \Artisan::call('cache:clear');
                                  \Artisan::call('route:cache');

                return redirect()->back()->with('success', 'CRUD for edit functionality generated successfully.');
                }

            }



///////////////////////// DELETE CRUD //////////////////////////////////////


public function deleteCrud($table)
{
    $controllerName = Str::studly($table) . 'Controller';
    $controllerPath = base_path('vendor/caimari/laraflex/src/Controllers/Generated/' . $controllerName . '.php');

    // Borra el archivo del controlador si existe
    if (File::exists($controllerPath)) {
        File::delete($controllerPath);
    }

    // Borra la vista generada
    $tableNameForRoute = Str::kebab($table);
    $viewFolderPath = base_path('vendor/caimari/laraflex/src/resources/views/generated/' . $tableNameForRoute);
    if (File::exists($viewFolderPath)) {
        File::deleteDirectory($viewFolderPath);
    }

        // Borra las rutas del arhivo generated.php desde la funcion deleteRoutes
        $this->deleteRoutes($table, $controllerName);

    // Elimina el registro de la tabla admin_generated
    DB::table('admin_generated')->where('name', $table)->delete();

                  // Limpia el caché de vistas y rutas
                 \Artisan::call('cache:clear');
                 \Artisan::call('route:cache');

    return redirect()->back()->with('success', 'Crud deleted successfully.');
}




public function addRoutes($useStatement, $routeIndexString, $routeDestroyString, $routeCreateString = null, $routeStoreString = null, $routeEditString = null, $routeUpdateString = null)
{
    // Obtén la ruta del archivo de rutas
    $routesPath = base_path('vendor/caimari/laraflex/src/routes/generated.php');

    // Obtiene el contenido actualizado del archivo de rutas
    $routesContent = File::get($routesPath);

    // Agrega la declaración 'use' si no existe
    if (!Str::contains($routesContent, $useStatement)) {
        $routesContent = str_replace("<?php", "<?php\n\n" . $useStatement, $routesContent);
    }

    // Agrega la ruta index si no existe
    if (!Str::contains($routesContent, $routeIndexString)) {
        $routesContent .= $routeIndexString;
    }

    // Agrega la ruta destroy si no existe
    if (!Str::contains($routesContent, $routeDestroyString)) {
        $routesContent .= $routeDestroyString;
    }

    // Agrega la ruta create si no existe y fue proporcionada
    if ($routeCreateString && !Str::contains($routesContent, $routeCreateString)) {
        $routesContent .= $routeCreateString;
    }

    // Agrega la ruta store si no existe y fue proporcionada
    if ($routeStoreString && !Str::contains($routesContent, $routeStoreString)) {
        $routesContent .= $routeStoreString;
    }

    // Agrega la ruta edit si no existe y fue proporcionada
    if ($routeEditString && !Str::contains($routesContent, $routeEditString)) {
        $routesContent .= $routeEditString;
    }

    // Agrega la ruta update si no existe y fue proporcionada
    if ($routeUpdateString && !Str::contains($routesContent, $routeUpdateString)) {
        $routesContent .= $routeUpdateString;
    }

    // Guarda el archivo de rutas con el contenido actualizado
    File::put($routesPath, $routesContent);

}



public function deleteRoutes($table, $controllerName)
{
    $routesPath = base_path('vendor/caimari/laraflex/src/routes/generated.php');
    $routesContent = File::get($routesPath);

    $useStatement = "use Caimari\\LaraFlex\\Controllers\\Generated\\" . $controllerName . ";";

    // Si la declaración "use" existe en el archivo de rutas, elimínala
    if (Str::contains($routesContent, $useStatement)) {
        $routesContent = str_replace($useStatement, "", $routesContent);
    }

    $tableNameForRoute = $this->normalizeTableName($table);

    $routeIndexString = "\nRoute::get('/" . $tableNameForRoute . "', [" . $controllerName . "::class, 'index'])->name('crud." . $tableNameForRoute . ".index');";
    $routeDestroyString = "\nRoute::delete('/" . $tableNameForRoute . "/{id}', [" . $controllerName . "::class, 'destroy'])->name('crud." . $tableNameForRoute . ".destroy');";
    $routeCreateString = "\nRoute::get('/" . $tableNameForRoute . "/create', [" . $controllerName . "::class, 'create'])->name('crud." . $tableNameForRoute . ".create');";
    $routeStoreString = "\nRoute::post('/" . $tableNameForRoute . "/store', [" . $controllerName . "::class, 'store'])->name('crud." . $tableNameForRoute . ".store');";
    $routeEditString = "\nRoute::get('/" . $tableNameForRoute . "/edit/{id}', [" . $controllerName . "::class, 'edit'])->name('crud." . $tableNameForRoute . ".edit');";
    $routeUpdateString = "\nRoute::put('/" . $tableNameForRoute . "/update/{id}', [" . $controllerName . "::class, 'update'])->name('crud." . $tableNameForRoute . ".update');";

    // Debug
   // dd($routeStoreString, $routeEditString, $routeUpdateString);

    if (Str::contains($routesContent, $routeIndexString)) {
        $routesContent = str_replace($routeIndexString, "", $routesContent);
    }

    if (Str::contains($routesContent, $routeDestroyString)) {
        $routesContent = str_replace($routeDestroyString, "", $routesContent);
    }

    if (Str::contains($routesContent, $routeCreateString)) {
        $routesContent = str_replace($routeCreateString, "", $routesContent);
    }

    if (Str::contains($routesContent, $routeStoreString)) {
        $routesContent = str_replace($routeStoreString, "", $routesContent);
    }

    if (Str::contains($routesContent, $routeEditString)) {
        $routesContent = str_replace($routeEditString, "", $routesContent);
    }

    if (Str::contains($routesContent, $routeUpdateString)) {
        $routesContent = str_replace($routeUpdateString, "", $routesContent);
    }

    File::put($routesPath, $routesContent);

    // Verificar si el archivo de rutas está vacío oir algun fallo, vuelve a crear la etiqueta php.
    if (trim(File::get($routesPath)) == '') {
    File::put($routesPath, "<?php\n");
    }

    // Elimina el registro de la tabla 'admin_generated'
    DB::table('admin_generated')->where('name', $table)->delete();


}



//////////////////////////////// SYSTEM CREATE TABLES ////////////////////////////////////////////////
            //////////////////// SQL ////////////////////////////

            public function create()
            {
                return view('admin.tables.create-table');
            }    

            public function store(Request $request)

            {
                $tableName = $request->input('table_name');
                $columns = $request->input('columns');

                $columnNames = $request->input('column_name');
                $columnTypes = $request->input('column_type');

                // Verificar si se enviaron columnas y si la cantidad de nombres y tipos coincide
                if ($columnNames && $columnTypes && count($columnNames) === count($columnTypes)) {
                    // Crear la tabla
                    DB::statement("CREATE TABLE $tableName (id INT AUTO_INCREMENT PRIMARY KEY)");

                    // Agregar las columnas a la tabla
                    foreach ($columnNames as $index => $columnName) {
                        $columnType = $columnTypes[$index];

                        DB::statement("ALTER TABLE $tableName ADD $columnName $columnType");
                    }

                    return redirect()->route('tables.index')->with('success', 'Table created successfully.');
                } else {
                    // Manejar el error si no se enviaron columnas o si la cantidad de nombres y tipos no coincide
                    return redirect()->back()->with('error', 'Invalid column data.');
                }
            }



}