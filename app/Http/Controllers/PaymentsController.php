<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create(Request $request, Antrian $antrian)
    {   
        $this->ensureUserCanManage($request, $antrian);
        $amount = $request->input('amount');

        if (is_null($amount)) {
            $amount = optional($antrian->layanan)->harga
                ?? optional($antrian->layanan)->price
                ?? optional($antrian->layanan)->tarif
                ?? 0;
        }

        $payment = $antrian->payment()->first();

        if (! $payment) {
            $payment = $antrian->payment()->create([
                'amount' => $amount,
                'payment_status' => 'pending',
            ]);
        } elseif (! is_null($amount) && (float) $payment->amount !== (float) $amount) {
            $payment->update(['amount' => $amount]);
        }

        return view('payments.create', compact('antrian', 'payment'));
    }

    public function store(Request $request, Antrian $antrian): RedirectResponse
    {
        $this->ensureUserCanManage($request, $antrian); 
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $payment = $antrian->payment;

        $payload = [
            'amount' => $validated['amount'],
            'payment_status' => 'paid',
            'payment_method' => 'manual',
            'paid_at' => now(),
        ];

        if ($payment) {
            $payment->update($payload);
        } else {
            $antrian->payment()->create($payload);
        }

        if (! $antrian->nomor_antrian) {
            $last = Antrian::max('nomor_antrian') ?? 0;
            $antrian->nomor_antrian = $last + 1;
        }

        $antrian->status = 'approved';
        $antrian->save();

        return redirect()
            ->route('antrian.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi. Nomor antrian Anda ' . $antrian->nomor_antrian . '.');
    }

    protected function ensureUserCanManage(Request $request, Antrian $antrian): void
    {
        $user = $request->user();

       if (! $user) {
            abort(403);
        }

        if ($user->role !== 'admin' && $antrian->user_id !== $user->getAuthIdentifier()) {
            abort(403);
        }
    }
}