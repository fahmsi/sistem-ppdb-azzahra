<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class AchievementController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), $this->rules(true))
            ->validateWithBag('createAchievement');
        $validated['image'] = $request->file('image')->store('achievements', 'public');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] ??= 0;

        $achievement = Achievement::create($validated);

        ActivityLog::log('created', $achievement, 'Menambahkan prestasi siswa "'.$achievement->title.'"');

        return $this->redirectToSettings(
            'Prestasi Ditambahkan',
            'Prestasi "'.$achievement->title.'" berhasil ditambahkan ke landing page.'
        );
    }

    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $validated = Validator::make($request->all(), $this->rules(false))
            ->validateWithBag('updateAchievement'.$achievement->id);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($achievement);
            $validated['image'] = $request->file('image')->store('achievements', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] ??= 0;
        $achievement->update($validated);

        ActivityLog::log('updated', $achievement, 'Memperbarui prestasi siswa "'.$achievement->title.'"');

        return $this->redirectToSettings(
            'Perubahan Tersimpan',
            'Prestasi "'.$achievement->title.'" berhasil diperbarui.'
        );
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $title = $achievement->title;
        $this->deleteStoredImage($achievement);
        $achievement->delete();

        ActivityLog::log('deleted', null, 'Menghapus prestasi siswa "'.$title.'"');

        return $this->redirectToSettings(
            'Prestasi Dihapus',
            'Prestasi "'.$title.'" telah dihapus dari daftar.'
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(bool $imageRequired): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'level' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'achievement_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'image' => [
                $imageRequired ? 'required' : 'nullable',
                File::image()->max(3 * 1024),
            ],
        ];
    }

    private function deleteStoredImage(Achievement $achievement): void
    {
        if ($achievement->image && ! str_starts_with($achievement->image, 'http')) {
            Storage::disk('public')->delete($achievement->image);
        }
    }

    private function redirectToSettings(string $title, string $message): RedirectResponse
    {
        return redirect(route('admin.settings.index').'#prestasi')->with('swal', [
            'icon' => 'success',
            'title' => $title,
            'text' => $message,
            'confirmButtonText' => 'Selesai',
        ]);
    }
}
