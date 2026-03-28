<?php

namespace Caimari\LaraFlex\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Caimari\LaraFlex\Models\User;

class UserController extends Controller
{

public function index()
{
    $users = User::all();

    return view('laraflex::admin.users.index', compact('users'));
}

public function edit()
{
    $user = auth()->user();

    return view('laraflex::admin.users.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        'password' => ['nullable', 'min:6', 'confirmed'],
    ]);

    $validator->after(function ($validator) use ($request, $user) {
        if ($request->filled('password') && empty($request->input('password_confirmation'))) {
            $validator->errors()->add('password_confirmation', 'The password confirmation field is required.');
        }
    });

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user->name = $request->input('name');
    $user->email = $request->input('email');
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    $user->save();

    return redirect()->route('users.edit')->with('success', 'Profile updated successfully.');
}



}