<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create(Antrian $antrian)
    {
        $payment = $antrian->payment()->firstOrCreate([], [
            'amount' => 0,
            'payment_status' => 'pending',
        ]);

        return view('payments.create', compact('antrian', 'payment'));
    }

    public function store(Request $request, Antrian $antrian)
    {
        if ($antrian->payment) {
            $antrian->payment->update([
                'payment_status' => 'paid',
                'payment_method' => 'manual',
                'paid_at' => now(),
            ]);
        }

        $antrian->update(['status' => 'paid']);

        return redirect()->route('antrian.index')->with('success', 'Payment successful.');
    }
}