<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Caimari\LaraFlex\Models\Member;
use Caimari\LaraFlex\Models\MemberType;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();

        return view('laraflex::admin.members.index', compact('members'));
    }

    
    public function create()
    {
        $memberTypes = MemberType::all(); // Obtén los tipos de miembros
    
        return view('laraflex::admin.members.create', compact('memberTypes'));
    }
    

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:members',
        'password' => 'required|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $member = new Member();
    $member->name = $request->input('name');
    $member->email = $request->input('email');
    $member->password = bcrypt($request->input('password'));
    $member->save();

    // Guarda la relación con los tipos de miembros seleccionados
    $member->memberTypes()->attach($request->input('member_types'));

    return redirect()->route('panel.members.index')->with('success', 'Member created successfully.');
}


public function edit(Member $member)
{
    $memberTypes = MemberType::all();
    
    return view('laraflex::admin.members.edit', compact('member', 'memberTypes'));
}



public function update(Request $request, Member $member)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => ['required', 'email', Rule::unique('members')->ignore($member->id)],
        'password' => ['nullable', 'min:6', 'confirmed'],
        'status' => 'required|in:0,1', // Validar el campo "status" como obligatorio y permitir solo los valores 0 y 1
    ]);

    $validator->after(function ($validator) use ($request) {
        if ($request->filled('password') && empty($request->input('password_confirmation'))) {
            $validator->errors()->add('password_confirmation', 'The password confirmation field is required.');
        }
    });

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $member->name = $request->input('name');
    $member->email = $request->input('email');
    if ($request->filled('password')) {
        $member->password = bcrypt($request->input('password'));
    }
    $member->status = $request->input('status'); // Actualizar el campo "status"

    $member->save();

    // Guarda la relación con los tipos de miembros seleccionados
    $member->memberTypes()->sync($request->input('member_types'));

    return redirect()->route('panel.members.edit', $member)->with('success', 'Profile updated successfully.');
}

    

            public function destroy(Member $member)
        {
            // Eliminar el miembro
            $member->delete();

            // Redireccionar a la página de lista de miembros con un mensaje de éxito
            return redirect()->route('panel.members.index')->with('success', 'Member deleted successfully.');
        }


}
