<?php

namespace Caimari\LaraFlex\Controllers;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Caimari\LaraFlex\Models\SitePostCategory;


class PostCategoryController extends Controller
{
    public function index()
    {
       
        $categories = SitePostCategory::all();

        return view('laraflex::admin.post-categories.index', compact('categories'));
    }

    public function create()
    {
        

        return view('laraflex::admin.post-categories.create');
    }

    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'title' => 'required|string|unique:site_post_categories',
            'slug' => 'required|string|unique:site_post_categories',
        ], [
            'title.required' => 'The title is required.',
            'title.unique' => 'A category with this title already exists.',
            'slug.required' => 'The slug is required.',
            'slug.unique' => 'A category with this slug already exists.',
        ]);

        $category = new SitePostCategory();
        $category->title = $request->input('title');
        $category->slug = $request->input('slug');
        $category->status = $request->input('status');

        $category->save();

        return redirect()->route('post-categories.index')->with('success', 'Post category created successfully.');

    } catch (ValidationException $e) {
        return redirect()->route('post-categories.create')->withErrors($e->errors());
    }
}

    
    public function edit($id)
    {
        
        $category = SitePostCategory::findOrFail($id);

        return view('admin.post-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|unique:site_post_categories,title,' . $id . ',id',
                'slug' => 'required|string|unique:site_post_categories,slug,' . $id . ',id',
            ], [
                'title.required' => 'The title is required.',
                'title.unique' => 'A category with this title already exists.',
                'slug.required' => 'The slug is required.',
                'slug.unique' => 'A category with this slug already exists.',                
            ]);
        
            $category = SitePostCategory::findOrFail($id);
            $category->title = $request->input('title');
            $category->slug = $request->input('slug');
            $category->status = $request->input('status');
        
            $category->save();
        
            return redirect()->route('post-categories.index')->with('success', 'Post category updated successfully.');
        
        } catch (ValidationException $e) {
            return redirect()->route('post-categories.edit', $id)->withErrors($e->errors());

        }
    }
    

    public function destroy($id)
    {
   

        $category = SitePostCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('post-categories.index')->with('success', 'Post category successfully removed.');
    }
}
