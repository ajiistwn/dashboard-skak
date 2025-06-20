<?php

namespace App\Http\Controllers\Dashboard\Utils;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    // Ambil semua user tanpa password dan remember_token
    public function index(Request $request)
    {
        $search = $request->input('search');
        $access = $request->input('access'); // filter berdasarkan kategori access

        $users = User::select([
                'id', 'name', 'full_name', 'email', 'phone', 'address',
                'job_title', 'access', 'status', 'image', 'email_verified_at', 'created_at', 'updated_at'
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                    ->orWhere('job_title', 'like', "%$search%")
                    ->orWhere('access', 'like', "%$search%");
                });
            })
            ->when($access, function ($query, $access) {
                $query->where('access', $access);
            })
            ->get();

        return response()->json($users);
    }

    public function show($id)
    {
        try {
            $user = User::select([
                    'id', 'name', 'full_name', 'email', 'phone', 'address',
                    'job_title', 'access', 'status', 'image', 'email_verified_at', 'created_at', 'updated_at'
                ])
                ->findOrFail($id);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    // Tambah user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'access'   => 'in:admin,bod,team,intern,crew,partner,investor',
            'status'   => 'in:employee',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'full_name'=> $request->full_name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'job_title'=> $request->job_title,
            'access'   => $request->access ?? 'team',
            'status'   => $request->status ?? 'employee',
            'image'    => $request->image,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created', 'user' => $user], 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'email|unique:users,email,' . $user->id,
            'access' => 'in:admin,bod,team,intern,crew,partner,investor',
            'status' => 'in:employee',
        ]);

        $user->update([
            'name'      => $request->name ?? $user->name,
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'address'   => $request->address,
            'job_title' => $request->job_title,
            'access'    => $request->access ?? $user->access,
            'status'    => $request->status ?? $user->status,
            'image'     => $request->image,
        ]);

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
