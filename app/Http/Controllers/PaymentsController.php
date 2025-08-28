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

        $antrian->update(['status' => 'paid']);

        return redirect()->route('antrian.index')->with('success', 'Payment successful.');
    }
}