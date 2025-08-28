<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create(Request $request, Antrian $antrian)
    {   
        $amount = $request->input('amount');

        if (is_null($amount)) {
            $amount = optional($antrian->layanan)->harga
                ?? optional($antrian->layanan)->price
                ?? optional($antrian->layanan)->tarif
                ?? 0;
        }

        $payment = $antrian->payment()->firstOrCreate([], [
            'amount' => $amount,
            'payment_status' => 'pending',
        ]);

        return view('payments.create', compact('antrian', 'payment'));
    }

    public function store(Request $request, Antrian $antrian)
    {   
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        if ($antrian->payment) {
            $antrian->payment->update([
                'amount' => $validated['amount'],
                'payment_status' => 'paid',
                'payment_method' => 'manual',
                'paid_at' => now(),
            ]);
        }

        if (! $antrian->nomor_antrian) {
            $last = Antrian::max('nomor_antrian') ?? 0;
            $antrian->nomor_antrian = $last + 1;
        }

        $antrian->status = 'approved';
        $antrian->save();

        // $antrian->user->notify(new NomorAntrianAssigned($antrian));

        return redirect()->route('antrian.index')
            ->with('success', 'Pembayaran berhasil, nomor antrian Anda ' . $antrian->nomor_antrian . '.');
    }
}