@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pembayaran</h1>
    <p>Jumlah: {{ number_format($payment->amount, 2) }}</p>
    <form method="POST" action="{{ route('payments.store', $antrian) }}">
        @csrf
        <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
    </form>
</div>
@endsection