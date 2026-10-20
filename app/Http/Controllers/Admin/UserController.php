<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(): View
    {
        $users = User::query()
            ->with('roles')
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%')->orWhere('email', 'like', '%'.request('q').'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User, 'roles' => Role::orderBy('name')->get()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['email_verified_at'] = now();

        $user = User::create($data);

        // Users are never allowed to grant themselves roles through this
        // form: role assignment on creation is restricted to roles the
        // creating (already-authorized) administrator selects explicitly.
        if ($request->user()->hasRole('Super Administrator') && $request->filled('role')) {
            $user->syncRoles([$request->input('role')]);
        }

        ActivityLogger::log('created', $user, "Created user account for {$user->email}");

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $user->update($data);

        ActivityLogger::log('updated', $user, "Updated user account for {$user->email}");

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function updateRoles(Request $request, User $user): RedirectResponse
    {
        $this->authorize('manageRoles', $user);

        $data = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        ActivityLogger::log('updated', $user, "Changed role assignment for {$user->email}");

        return back()->with('success', 'Roles updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        ActivityLogger::log('deleted', null, "Deleted user account for {$user->email}");

        return redirect()->route('admin.users.index')->with('success', 'User account deactivated.');
    }
}
