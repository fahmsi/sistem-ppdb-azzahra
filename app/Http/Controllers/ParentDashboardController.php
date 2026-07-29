<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Support\ParentRegistrationProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $paymentSetting = PaymentSetting::current();

        $siswas = $request->user()
            ->siswas()
            ->with([
                'pendaftaranDetails' => fn ($query) => $query
                    ->with(['pendaftaran', 'pembayaran'])
                    ->latest(),
            ])
            ->latest()
            ->get();

        $progressBySiswa = $siswas->mapWithKeys(function ($siswa) use ($paymentSetting) {
            $progress = (new ParentRegistrationProgress(
                $siswa,
                (float) ($paymentSetting?->amount ?? 0) > 0,
            ))->toArray();

            return [$siswa->getKey() => $progress];
        });

        $requestedSiswaId = $request->integer('siswa');
        $selectedProgress = $requestedSiswaId > 0
            ? $progressBySiswa->get($requestedSiswaId)
            : null;

        $selectedProgress ??= $progressBySiswa
            ->sortByDesc('attention_priority')
            ->first();

        $selectedProgress ??= (new ParentRegistrationProgress(
            null,
            (float) ($paymentSetting?->amount ?? 0) > 0,
        ))->toArray();

        return view('parent.dashboard', [
            'siswas' => $siswas,
            'progressBySiswa' => $progressBySiswa,
            'progress' => $selectedProgress,
        ]);
    }
}
