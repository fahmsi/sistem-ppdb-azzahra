<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminManageController extends Controller
{
    /**
     * Display admin users list.
     */
    public function index(): View
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.kelola-admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create(): View
    {
        return view('admin.kelola-admin.create');
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => $validated['role'],
        ]);

        ActivityLog::log('created', $user, "Membuat akun admin baru: {$user->name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an admin.
     */
    public function edit(User $user): View
    {
        abort_if(!in_array($user->role, ['admin', 'super_admin']), 404);

        return view('admin.kelola-admin.edit', compact('user'));
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if(!in_array($user->role, ['admin', 'super_admin']), 404);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        ActivityLog::log('updated', $user, "Mengubah data admin: {$user->name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(!in_array($user->role, ['admin', 'super_admin']), 404);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->delete();

        ActivityLog::log('deleted', null, "Menghapus akun admin: {$name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }

    /**
     * Show activity logs (Super Admin only).
     */
    public function activityLogs(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.activity-log.index', compact('logs'));
    }
}
