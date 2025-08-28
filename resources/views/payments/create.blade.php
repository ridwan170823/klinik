@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pembayaran</h1>
    <p>Jumlah: {{ number_format($payment->amount, 2) }}</p>
    <form method="POST" action="{{ route('payments.store', $antrian) }}">
        @csrf
        <div class="mb-3">
            <label for="amount" class="form-label">Jumlah</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" required>
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
    </form>
</div>
@endsection