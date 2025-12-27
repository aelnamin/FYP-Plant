<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Only admins can access this controller
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        // Base query: exclude admins
        $query = User::where('role', '!=', 'admin');

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Order by newest and paginate
        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.users.index', compact('users', 'search', 'role'));
    }


    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,buyer,seller',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,buyer,seller',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * View user reports (example page)
     */
    public function reports(User $user)
    {
        // Collect all report data in a single array
        $reports = [
            'orders' => $user->orders()->latest()->get(),
            'complaints' => $user->complaints()->latest()->get(),
            // You can add more types here in the future
            // 'reviews' => $user->reviews()->latest()->get(),
        ];

        return view('admin.users.reports', compact('user', 'reports'));
    }

}
