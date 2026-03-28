<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Caimari\LaraFlex\Models\CodeSnippet;
use Caimari\LaraFlex\Models\Version;
use Caimari\LaraFlex\Models\GeneralSettings;

class CodeSnippetsController extends Controller
{
    public function index()
    {
        // Obtener la configuración de la página de inicio
        $setHomePage = GeneralSettings::first();

        $codeSnippets = CodeSnippet::all();
        return view('laraflex::admin.code-snippets.index', compact('codeSnippets','setHomePage'));
    }

    public function create()
    {
        return view('laraflex::admin.code-snippets.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);
    
        CodeSnippet::create($validatedData);
    
        return redirect()->route('panel.code-snippets.index')
            ->with('success', 'Code snippet created successfully.');
    }
    

    public function edit(CodeSnippet $codeSnippet)
    {
        

        // Comprobar si la página es la página de inicio
        $homePageSetting = GeneralSettings::first();
        $isHomePage = $homePageSetting && $homePageSetting->content_id == $codeSnippet->id && $homePageSetting->content_type == 'snippet';
        
        $versions = Version::where('versionable_type', get_class($codeSnippet))
            ->where('versionable_id', $codeSnippet->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('laraflex::admin.code-snippets.edit', compact('codeSnippet', 'versions','isHomePage'));
    }
    

    public function update(Request $request, CodeSnippet $codeSnippet)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'content' => 'nullable',
            'version_name' => 'nullable|string',
        ]);
    
        $codeSnippet->update($validatedData);
    
        // Create a new version after updating the code snippet
        Version::create([
            'versionable_type' => get_class($codeSnippet),
            'versionable_id' => $codeSnippet->id,
            'name' => $request->input('version_name'),
            'data' => [
                'name' => $codeSnippet->name,
                'description' => $codeSnippet->description,
                'content' => $codeSnippet->content
            ]
        ]);
    
        return redirect()->route('panel.code-snippets.edit', $codeSnippet->id)
            ->with('success', 'Code snippet updated successfully.');
    }
    
    
    
    public function revert(Request $request, CodeSnippet $codeSnippet, Version $version)
        {
            $codeSnippet->update($version->data);
            return redirect()->route('panel.code-snippets.edit', $codeSnippet->id)
                ->with('success', 'Code snippet reverted successfully.');
        }



    public function destroy(CodeSnippet $codeSnippet)
    {
        $codeSnippet->delete();

        return redirect()->route('panel.code-snippets.index')
            ->with('success', 'Code snippet deleted successfully.');
    }
}