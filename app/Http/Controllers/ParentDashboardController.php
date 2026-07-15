<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $siswas = $request->user()
            ->siswas()
            ->with([
                'pendaftaranDetails' => fn ($query) => $query
                    ->with(['pendaftaran', 'pembayaran', 'observasiTerbaru'])
                    ->latest(),
            ])
            ->latest()
            ->get();

        return view('parent.dashboard', [
            'siswas' => $siswas,
            'paymentSetting' => PaymentSetting::current(),
        ]);
    }
}
