<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query, excluding users where 'first_name' is 'admin'
        $users = User::query()->whereNot('first_name', 'admin');

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $users->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        // Apply year filter
        if ($request->has('year') && $request->year != '') {
            $users->where('year', $request->year);
        }

        // Apply section filter
        if ($request->filled('section')) {
            $users->where('section', 'like', "%{$request->section}%");
        }

        // Exclude specific year and section combination
        $users = $users->where(function ($query) {
            $query->where('year', '!=', 1) // Exclude year 1
                ->orWhere('section', '!=', 'admin'); // Exclude section admin
        })->paginate(10);


        // Get the filtered results
        // $users = $users->paginate(10);

        // Define possible years (assuming 1 to 4 are the valid years)
        $years = User::distinct()->pluck('year')->toArray();

        // Retrieve distinct sections from the users table
        $sections = User::distinct()->pluck('section')->toArray();

        // Return the view with filtered users, years, and sections data
        return view('admin.users.index', compact('users', 'years', 'sections'));
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
     public function import(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt', // Accept only CSV or TXT files
    ]);

    // Retrieve the uploaded file
    $file = $request->file('csv_file');

    // Open the file for reading
    $handle = fopen($file->getPathname(), 'r');

    // Skip the header row
    $header = fgetcsv($handle);

    // Initialize counters and error storage
    $importedCount = 0;
    $errors = [];

    // Read and process each row
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        try {
            // Validate row data before creating a user
            $validatedData = [
                'first_name' => $data[0],
                'last_name' => $data[1],
                'LRN' => $data[2],
                'year' => $data[3],
                'section' => $data[4],
                'email' => $data[5],
            ];

            // Perform field-level validation
            $validator = Validator::make($validatedData, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'LRN' => 'required|numeric|unique:users,LRN',
                'year' => 'required|integer',
                'section' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ]);


            if ($validator->fails()) {
                // Capture validation errors for the current row
                $errors[] = "Row with LRN {$data[2]}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Create the user
            User::create([
                'first_name' => $data[0],
                'last_name' => $data[1],
                'LRN' => $data[2],
                'year' => $data[3],
                'section' => $data[4],
                'email' => $data[5],
                'password' => bcrypt(value: 'salus2024'), // Set default password
            ]);

            $importedCount++; // Increment the counter on successful creation
        } catch (\Exception $e) {
            // Capture any other errors during user creation
            $errors[] = "Row with LRN {$data[2]}: " . $e->getMessage();
        }
    }

    // Close the file after processing
    fclose($handle);

    // Prepare feedback messages
    $message = "{$importedCount} users imported successfully.";
    if (!empty($errors)) {
        $message .= " However, there were errors with some rows.";
    }

    // Redirect back with success and error messages
    return redirect()->route('admin.users.index')
        ->with('message', $message)
        ->with('errors', $errors);
}


}
