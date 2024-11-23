<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index()  {

        $users = User::whereNot('first_name','admin')->get();
        // $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.role', compact('user', 'roles', 'permissions'));
    }
    public function assignRole(Request $request, User $user)
    {
        if ($user->hasRole($request->role)) {
            return back()->with('message', 'Role exists.');
        }

        $user->assignRole($request->role);
        return back()->with('message', 'Role assigned.');
    }

    public function removeRole(User $user, Role $role)
    {
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }

    public function givePermission(Request $request, User $user)
    {
        if ($user->hasPermissionTo($request->permission)) {
            return back()->with('message', 'Permission exists.');
        }
        $user->givePermissionTo($request->permission);
        return back()->with('message', 'Permission added.');
    }

    public function revokePermission(User $user, Permission $permission)
    {
        if ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
            return back()->with('message', 'Permission revoked.');
        }
        return back()->with('message', 'Permission does not exists.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return back()->with('message', 'you are admin.');
        }
        $user->delete();

        return back()->with('message', 'User deleted.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'LRN' => 'required|numeric|unique:users',
            'year' => 'required|integer',
            'section' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'LRN' => $validated['LRN'],
            'year' => $validated['year'],
            'section' => $validated['section'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->route('admin.users.index')->with('message', 'User created successfully.');
    }
    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'LRN' => 'required|numeric|unique:users,LRN,' . $user->id,
            'year' => 'required|integer',
            'section' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // 'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'LRN' => $request->LRN,
            'year' => $request->year,
            'section' => $request->section,
            'email' => $request->email,
            // 'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('admin.users.index')->with('message', 'User updated successfully.');
    }

     // Method for exporting users to CSV
     public function exportUsers()
     {
         $headers = [
             'Content-Type' => 'text/csv',
             'Content-Disposition' => 'attachment; filename="users.csv"',
         ];

         $callback = function () {
             $handle = fopen('php://output', 'w');
             fputcsv($handle, ['First Name', 'Last Name', 'LRN', 'Year', 'Section', 'Email']);

             $users = User::all(['first_name', 'last_name', 'LRN', 'year', 'section', 'email']);
             foreach ($users as $user) {
                 fputcsv($handle, [
                     $user->first_name,
                     $user->last_name,
                     $user->LRN,
                     $user->year,
                     $user->section,
                     $user->email,
                 ]);
             }

             fclose($handle);
         };

         return new StreamedResponse($callback, 200, $headers);
     }

     // Method for importing users from CSV
     public function importUsers(Request $request)
     {
         $request->validate([
             'csv_file' => 'required|mimes:csv,txt',
         ]);

         $file = $request->file('csv_file');
         $handle = fopen($file->getPathname(), 'r');
         $header = fgetcsv($handle); // Skip the header row

         while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
             User::create([
                 'first_name' => $data[0],
                 'last_name' => $data[1],
                 'LRN' => $data[2],
                 'year' => $data[3],
                 'section' => $data[4],
                 'email' => $data[5],
                 'password' => bcrypt('defaultPassword'), // Set a default password or handle accordingly
             ]);
         }

         fclose($handle);

         return redirect()->route('admin.users.index')->with('success', 'Users imported successfully!');
     }

}
