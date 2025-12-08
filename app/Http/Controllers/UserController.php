<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET all users
    public function index()
    {
        return User::all();
    }

    // Create user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        return User::create($validated);
    }

    // GET single user
    public function show($id)
    {
        return User::findOrFail($id);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return $user;
    }

    // Delete user
    public function destroy($id)
    {
        return User::destroy($id);
    }
}
