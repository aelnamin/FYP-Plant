<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return Admin::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        return Admin::create($validated);
    }

    public function show($id)
    {
        return Admin::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:admins,email,' . $id,
            'password' => 'sometimes|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $admin->update($validated);
        return $admin;
    }

    public function destroy($id)
    {
        return Admin::destroy($id);
    }
}
