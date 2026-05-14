<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        
        $query = Pembayaran::with(['pendaftaranDetail.siswa.user', 'pendaftaranDetail.pendaftaran'])->latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $pembayarans = $query->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }
}
