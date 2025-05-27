<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::with('role', 'userStatus')->get();
        return response()->json(['users' => $users]);
    }

    public function addUser(Request $request)
    {
        $users = $request->all();

        if (isset($users[0])) {
            $createdUsers = [];

            foreach ($users as $userData) {
                $validator = Validator::make($userData, [
                    'u_name' => ['required', 'string', 'max:255'],
                    'u_email' => ['required', 'email', 'max:255', 'unique:users,u_email'],
                    'u_pass' => ['required', 'string', 'min:8'],
                    'confirm_password' => ['required', 'same:u_pass'],
                    'role_id' => ['required', 'exists:roles,id'],
                    'status_id' => ['required', 'exists:user_statuses,id'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $user = User::create([
                    'u_name' => $userData['u_name'],
                    'u_email' => $userData['u_email'],
                    'u_pass' => Hash::make($userData['u_pass']),
                    'role_id' => $userData['role_id'],
                    'status_id' => $userData['status_id'],
                ]);

                $createdUsers[] = $user;
            }

            return response()->json(['message' => 'Users created successfully!', 'users' => $createdUsers]);
        } else {
            // Single user creation
            $request->validate([
                'u_name' => ['required', 'string', 'max:255'],
                'u_email' => ['required', 'email', 'max:255', 'unique:users,u_email'],
                'u_pass' => ['required', 'string', 'min:8'],
                'confirm_password' => ['required', 'same:u_pass'],
                'role_id' => ['required', 'exists:roles,id'],
                'status_id' => ['required', 'exists:user_statuses,id'],
            ]);

            $user = User::create([
                'u_name' => $request->u_name,
                'u_email' => $request->u_email,
                'u_pass' => Hash::make($request->u_pass),
                'role_id' => $request->role_id,
                'status_id' => $request->status_id,
            ]);

            return response()->json(['message' => 'User successfully created!', 'user' => $user]);
        }
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
            'u_name' => ['required', 'string', 'max:255'],
            'u_email' => ['required', 'email', 'max:255', 'unique:users,u_email,' . $id],
            'role_id' => ['required', 'exists:roles,id'],
            'status_id' => ['required', 'exists:user_statuses,id'],
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $user->update([
            'u_name' => $request->u_name,
            'u_email' => $request->u_email,
            'role_id' => $request->role_id,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'User successfully edited!', 'user' => $user]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User successfully deleted!']);
    }
}
