<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display all settings grouped for management.
     */
    public function index(): View
    {
        $settings = Setting::orderBy('group')->orderBy('id')->get()->groupBy('group');
        $achievements = Achievement::orderBy('sort_order')->orderByDesc('achievement_year')->get();

        return view('admin.settings.index', compact('settings', 'achievements'));
    }

    /**
     * Update settings in bulk.
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        ActivityLog::log('updated', null, 'Memperbarui pengaturan situs');

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Pengaturan Tersimpan',
            'text' => 'Perubahan pengaturan situs berhasil disimpan.',
            'confirmButtonText' => 'Selesai',
        ]);
    }
}
