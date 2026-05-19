<?php

namespace App\Http\Controllers;

use App\Models\RoleChangeLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $role = (string) request('role', '');

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, ['admin', 'user'], true), function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $logs = RoleChangeLog::with(['actor', 'target'])
            ->latest()
            ->limit(20)
            ->get();

        return view('Admin.users', compact('users', 'logs', 'search', 'role'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return redirect()->route('users')->with('error', 'You cannot remove your own admin role.');
        }

        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users')->with('error', 'System must have at least one admin account.');
            }
        }

        $oldRole = $user->role;

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($oldRole !== $user->role) {
            RoleChangeLog::create([
                'actor_user_id' => Auth::id(),
                'target_user_id' => $user->id,
                'action' => 'role_changed',
                'old_role' => $oldRole,
                'new_role' => $user->role,
                'note' => 'Role updated in admin users page.',
            ]);
        }

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users')->with('error', 'You cannot delete your own account while logged in.');
        }

        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('users')->with('error', 'Cannot delete the last admin account.');
            }
        }

        $deletedRole = $user->role;
        $deletedUserId = $user->id;

        $user->delete();

        RoleChangeLog::create([
            'actor_user_id' => Auth::id(),
            'target_user_id' => null,
            'action' => 'user_deleted',
            'old_role' => $deletedRole,
            'new_role' => null,
            'note' => "Deleted user id {$deletedUserId}.",
        ]);

        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

    public function toggleVip(User $user)
    {
        if ($user->is_vip) {
            // Remove VIP status
            $user->update([
                'is_vip' => false,
                'vip_expires_at' => null,
            ]);
            $message = 'Đã hủy VIP cho user ' . $user->name;
        } else {
            // Add VIP status (30 days from now)
            $user->update([
                'is_vip' => true,
                'vip_expires_at' => now()->addDays(30),
            ]);
            $message = 'Đã cấp VIP 30 ngày cho user ' . $user->name;
        }

        return redirect()->back()->with('success', $message);
    }
}
