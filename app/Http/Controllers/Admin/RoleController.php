<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Only Super Administrators may create roles or change permission grants
 * (see routes/admin.php middleware and the role:Super Administrator check
 * below), preventing any lower-privileged administrator from escalating
 * their own or another account's access.
 */
class RoleController extends Controller
{
    public function index(): View
    {
        $this->authorize('roles.manage');

        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.roles.form', ['role' => new Role, 'permissions' => Permission::orderBy('name')->get(), 'assigned' => []]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        ActivityLogger::log('created', $role, "Created role \"{$role->name}\"");

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role): View
    {
        $this->authorizeSuperAdmin();

        return view('admin.roles.form', [
            'role' => $role,
            'permissions' => Permission::orderBy('name')->get(),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        if ($role->name === 'Super Administrator') {
            return back()->with('error', 'The Super Administrator role always has full access and cannot be modified.');
        }

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        ActivityLogger::log('updated', $role, "Updated permissions for role \"{$role->name}\"");

        return redirect()->route('admin.roles.index')->with('success', 'Role permissions updated.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        if ($role->name === 'Super Administrator' || $role->users()->exists()) {
            return back()->with('error', 'This role cannot be deleted while it is protected or still assigned to users.');
        }

        $name = $role->name;
        $role->delete();

        ActivityLogger::log('deleted', null, "Deleted role \"{$name}\"");

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    private function authorizeSuperAdmin(): void
    {
        abort_unless(request()->user()?->hasRole('Super Administrator'), 403);
    }
}
