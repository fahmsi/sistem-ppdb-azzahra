<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index(): View
    {
        $galleries = Gallery::oldest()->get();

        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery item.
     */
    public function create(): View
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created gallery item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), $this->rules(true))
            ->validateWithBag('createGallery');

        $validated['image'] = $request->file('image')->store('galleries', 'public');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] ??= 0;

        $gallery = Gallery::create($validated);

        ActivityLog::log('created', $gallery, 'Menambahkan foto gallery "'.$gallery->title.'"');

        return redirect()->route('admin.gallery.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Foto Ditambahkan',
                'text' => 'Foto "'.$gallery->title.'" berhasil ditambahkan ke gallery.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(Gallery $gallery): View
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = Validator::make($request->all(), $this->rules(false))
            ->validateWithBag('updateGallery'.$gallery->id);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($gallery);
            $validated['image'] = $request->file('image')->store('galleries', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] ??= 0;
        $gallery->update($validated);

        ActivityLog::log('updated', $gallery, 'Memperbarui foto gallery "'.$gallery->title.'"');

        return redirect()->route('admin.gallery.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Perubahan Tersimpan',
                'text' => 'Foto "'.$gallery->title.'" berhasil diperbarui.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    /**
     * Remove the specified gallery item from storage.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        $title = $gallery->title;
        $this->deleteStoredImage($gallery);
        $gallery->delete();

        ActivityLog::log('deleted', null, 'Menghapus foto gallery "'.$title.'"');

        return redirect()->route('admin.gallery.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Foto Dihapus',
                'text' => 'Foto "'.$title.'" telah dihapus dari gallery.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(bool $imageRequired): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'image' => [
                $imageRequired ? 'required' : 'nullable',
                File::image()->max(5 * 1024), // 5MB max
            ],
        ];
    }

    private function deleteStoredImage(Gallery $gallery): void
    {
        if ($gallery->image && ! str_starts_with($gallery->image, 'http')) {
            Storage::disk('public')->delete($gallery->image);
        }
    }
}
