<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $this->authorize('admin');
        
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->authorize('admin');
        
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('admin');
        
        User::create($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $this->authorize('admin');
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->authorize('admin');
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('admin');
        
        $data = $request->validated();
        
        // If password is not provided, remove it from the data
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        $this->authorize('admin');
        
        // Prevent deleting the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Cannot delete the last admin user');
        }
        
        $userName = $user->name;
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', "User {$userName} deleted successfully");
    }

    /**
     * Authorize the action
     */
    protected function authorize($role)
    {
        if (!auth()->user()?->hasRole($role)) {
            abort(403, 'Unauthorized access');
        }
    }
}
