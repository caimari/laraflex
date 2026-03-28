<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Caimari\LaraFlex\Models\MemberType;

class MemberTypeController extends Controller
{
    public function index()
    {
        $memberTypes = MemberType::all();

        return view('laraflex::admin.member-types.index', compact('memberTypes'));
    }

    public function create()
    {
        return view('laraflex::admin.member-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:member_types',
            'status' => 'required|boolean',
        ]);
    
        MemberType::create([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ]);
    
        return redirect()->route('panel.members.type.index')->with('success', 'Member type created successfully.');
    }
    
    public function edit(MemberType $type)
    {
        return view('laraflex::admin.member-types.edit', compact('type'));
    }
     
    public function update(Request $request, MemberType $type)
    {
        $request->validate([
            'name' => 'required|unique:member_types,name,' . $type->id,
            'status' => 'required|in:0,1',
        ]);
    
        $type->update([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ]);
    
        return redirect()->route('panel.members.type.index')->with('success', 'Member type updated successfully.');
    }
    
    

    public function destroy(MemberType $type)
    {
        $type->delete();
    
        return redirect()->route('panel.members.type.index')->with('success', 'Member type deleted successfully.');
    }
    
}
