<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePaymentSettingRequest;
use App\Models\ActivityLog;
use App\Models\PaymentSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class PaymentSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.payment-settings.edit', [
            'paymentSetting' => PaymentSetting::current(),
        ]);
    }

    public function update(UpdatePaymentSettingRequest $request): RedirectResponse
    {
        $paymentSetting = PaymentSetting::current() ?? new PaymentSetting;
        $paymentSetting->id = PaymentSetting::SINGLETON_ID;

        $data = $request->safe()->except(['qris', 'remove_qris']);
        $oldQrisPath = $paymentSetting->qris_path;
        $newQrisPath = null;

        if ($request->hasFile('qris')) {
            $newQrisPath = $request->file('qris')->store('payment/qris', 'public');
            $data['qris_path'] = $newQrisPath;
        } elseif ($request->boolean('remove_qris')) {
            $data['qris_path'] = null;
        }

        try {
            $paymentSetting->fill($data)->save();
        } catch (Throwable $exception) {
            if ($newQrisPath) {
                Storage::disk('public')->delete($newQrisPath);
            }

            throw $exception;
        }

        if ($oldQrisPath && array_key_exists('qris_path', $data) && $oldQrisPath !== $data['qris_path']) {
            Storage::disk('public')->delete($oldQrisPath);
        }

        ActivityLog::log(
            'updated',
            $paymentSetting,
            'Memperbarui konfigurasi pembayaran'
        );

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Konfigurasi Tersimpan',
            'text' => 'Informasi pembayaran berhasil diperbarui.',
            'confirmButtonText' => 'Selesai',
        ]);
    }
}
