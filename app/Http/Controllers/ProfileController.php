<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->isParent()) {
            $user->load(['siswas' => fn ($query) => $query->orderBy('nama')]);
        }

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information (name, email, phone, avatar).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'no_telpon' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diubah.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Admin cannot delete their account from here
        if ($user->isAdmin()) {
            return back()->with('error', 'Admin tidak dapat menghapus akun melalui halaman ini.');
        }

        $siswas = $user->siswas()->withTrashed()->get();

        $hasRegistrationHistory = $siswas->contains(fn ($siswa) => $siswa->pendaftaranDetails()->exists());
        if ($hasRegistrationHistory) {
            return back()->with('error', 'Akun tidak dapat dihapus karena salah satu anak memiliki riwayat pendaftaran. Silakan hubungi admin sekolah.');
        }

        $publicPaths = $siswas->pluck('foto')
            ->filter()
            ->push($user->avatar)
            ->filter()
            ->values();
        $localPaths = $siswas->flatMap(fn ($siswa) => [
            $siswa->foto_kk,
            $siswa->foto_akta,
            $siswa->foto_ktp_ayah,
            $siswa->foto_ktp_ibu,
            $siswa->foto_ktp_wali,
        ])
            ->filter()
            ->values();

        Auth::logout();

        DB::transaction(function () use ($user, $siswas): void {
            foreach ($siswas as $siswa) {
                $siswa->forceDelete();
            }

            $user->delete();
        });

        Storage::disk('public')->delete($publicPaths->all());
        Storage::disk('local')->delete($localPaths->all());

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}
