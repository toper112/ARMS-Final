<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

     public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required']);

        Permission::create($validated);

        return to_route('admin.permissions.index')->with('message', 'Permission created.');
    }

    public function edit(Permission $permission)
    {
        $roles = Role::all();
        return view('admin.permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate(['name' => 'required']);
        $permission->update($validated);

        return to_route('admin.permissions.index')->with('message', 'Permission updated.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('message', 'Permission deleted.');
    }

    public function assignRole(Request $request, Permission $permission)
    {
        // Add validation for the 'role' field
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name', // Ensure the role is not empty and exists in the roles table
        ], [
            'role.required' => 'Please select a valid role.', // Custom error message for empty selection
            'role.exists' => 'The selected role does not exist.', // Custom error for invalid roles
        ]);

        // Check if the user already has the assigned role
        if ($permission->hasRole($validated['role'])) {
            return back()->with('message', 'Role already exists for this user.');
        }

        // Assign the role
        $permission->assignRole($validated['role']);

        // Return success message
        return back()->with('message', 'Role assigned successfully.');
    }


    public function removeRole(Permission $permission, Role $role)
    {
        if ($permission->hasRole($role)) {
            $permission->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }
}
