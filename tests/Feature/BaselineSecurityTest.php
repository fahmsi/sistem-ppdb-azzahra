<?php

use App\Http\Controllers\Admin\VerifikasiController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
 * PR3HardeningTest was removed because observation, school decision, and final
 * enrollment behavior are outside the current rebuild stage. This baseline
 * route-surface assertion remains relevant and is intentionally retained here.
 */
test('unused bulk verification mutation has no route method or rendered form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    expect(Route::has('admin.verifikasi.bulkUpdate'))->toBeFalse()
        ->and(method_exists(VerifikasiController::class, 'bulkUpdate'))->toBeFalse();

    $this->actingAs($admin)
        ->get(route('admin.verifikasi.index'))
        ->assertOk()
        ->assertDontSee('bulk-update', false)
        ->assertDontSee('bulkUpdate', false);
});
