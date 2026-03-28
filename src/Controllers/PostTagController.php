<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Caimari\LaraFlex\Models\SitePostTag;

class PostTagController extends Controller
{

        public function index()
    {
        $tags = SitePostTag::all();
        return view('laraflex::admin.post-tags.index', compact('tags'));
    }

    public function create()
    {
        return view('laraflex::admin.post-tags.create');
    }

    public function edit($id)
    {
        $tag = SitePostTag::findOrFail($id);
        return view('laraflex::admin.post-tags.edit', compact('tag'));
    }

        public function destroy($id)
    {
        $tag = SitePostTag::findOrFail($id);
        $tag->delete();

        return redirect()->route('post.tags.index')->with('success', 'Tag deleted successfully.');
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|unique:site_post_tags',
                'slug' => 'required|string|unique:site_post_tags',
                'status' => 'required|in:0,1',
            ], [
                'title.required' => 'The title is required.',
                'title.unique' => 'A tag with this title already exists.',
                'slug.required' => 'The slug is required.',
                'slug.unique' => 'A tag with this slug already exists.',
                'status.required' => 'The status is required.',
                'status.in' => 'Invalid status value.',
            ]);

            $tag = new SitePostTag();
            $tag->title = $request->input('title');
            $tag->slug = $request->input('slug');
            $tag->status = $request->input('status');

            $tag->save();

            return redirect()->route('post.tags.index')->with('success', 'Tag created successfully.');

        } catch (ValidationException $e) {
            return redirect()->route('post.tags.create')->withErrors($e->errors());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|unique:site_post_tags,title,' . $id . ',id',
                'slug' => 'required|string|unique:site_post_tags,slug,' . $id . ',id',
                'status' => 'required|in:0,1',
            ], [
                'title.required' => 'The title is required.',
                'title.unique' => 'A tag with this title already exists.',
                'slug.required' => 'The slug is required.',
                'slug.unique' => 'A tag with this slug already exists.',
                'status.required' => 'The status is required.',
                'status.in' => 'Invalid status value.',
            ]);

            $tag = SitePostTag::findOrFail($id);
            $tag->title = $request->input('title');
            $tag->slug = $request->input('slug');
            $tag->status = $request->input('status');

            $tag->save();

            return redirect()->route('post.tags.index')->with('success', 'Tag updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->route('post.tags.edit', $id)->withErrors($e->errors());
        }
    }

    // Otras funciones del controlador...
}
