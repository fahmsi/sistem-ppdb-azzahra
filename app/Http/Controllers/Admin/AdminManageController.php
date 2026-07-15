<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminManageController extends Controller
{
    /**
     * Display admin users list.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', Rule::in(['admin', 'super_admin'])],
            'status' => ['nullable', Rule::in(['active', 'suspended'])],
        ]);

        $baseQuery = User::whereIn('role', ['admin', 'super_admin']);

        $admins = (clone $baseQuery)
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_telpon', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, fn ($query, string $role) => $query->where('role', $role))
            ->when(($filters['status'] ?? null) === 'active', fn ($query) => $query->whereNull('suspended_at'))
            ->when(($filters['status'] ?? null) === 'suspended', fn ($query) => $query->whereNotNull('suspended_at'))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->whereNull('suspended_at')->count(),
            'suspended' => (clone $baseQuery)->whereNotNull('suspended_at')->count(),
            'super_admin' => (clone $baseQuery)->where('role', 'super_admin')->count(),
        ];

        $activeSuperAdminCount = User::where('role', 'super_admin')
            ->whereNull('suspended_at')
            ->count();

        return view('admin.kelola-admin.index', compact('admins', 'stats', 'activeSuperAdminCount'));
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'no_telpon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_telpon' => $validated['no_telpon'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
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
        abort_if(! in_array($user->role, ['admin', 'super_admin']), 404);

        return view('admin.kelola-admin.edit', compact('user'));
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin', 'super_admin']), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user->id)],
            'no_telpon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        if ($user->role === 'super_admin' && $validated['role'] !== 'super_admin' && $this->activeSuperAdminCount() <= 1) {
            return back()
                ->withInput()
                ->withErrors(['role' => 'Super admin terakhir tidak boleh diubah menjadi admin.']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_telpon = $validated['no_telpon'] ?? null;
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        ActivityLog::log('updated', $user, "Mengubah data admin: {$user->name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Suspend an admin account.
     */
    public function suspend(Request $request, User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin', 'super_admin']), 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat suspend akun sendiri.');
        }

        if ($user->role === 'super_admin' && $this->activeSuperAdminCount() <= 1 && ! $user->isSuspended()) {
            return back()->with('error', 'Super admin terakhir tidak boleh disuspend.');
        }

        $validated = $request->validate([
            'suspend_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->forceFill([
            'suspended_at' => now(),
            'suspended_by' => auth()->id(),
            'suspend_reason' => $validated['suspend_reason'] ?? null,
        ])->save();

        ActivityLog::log('updated', $user, "Suspend admin: {$user->name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Admin berhasil disuspend.');
    }

    /**
     * Restore a suspended admin account.
     */
    public function unsuspend(User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin', 'super_admin']), 404);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        $user->forceFill([
            'suspended_at' => null,
            'suspended_by' => null,
            'suspend_reason' => null,
        ])->save();

        ActivityLog::log('updated', $user, "Mengaktifkan kembali admin: {$user->name}");

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Admin berhasil diaktifkan kembali.');
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin', 'super_admin']), 404);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->role === 'super_admin' && $this->activeSuperAdminCount() <= 1 && ! $user->isSuspended()) {
            return back()->with('error', 'Super admin terakhir tidak boleh dihapus.');
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

    private function activeSuperAdminCount(): int
    {
        return User::where('role', 'super_admin')
            ->whereNull('suspended_at')
            ->count();
    }
}
