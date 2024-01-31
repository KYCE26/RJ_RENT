<!-- resources/views/transactions/show-payment-proof.blade.php -->

@extends('layouts.main')

@section('content')
<h1>Bukti Pembayaran</h1>

@if ($transaction->payment_proof)
<img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Pembayaran" style="max-width: 500px;">
@else
<p>Tidak ada bukti pembayaran.</p>
@endif

<a href="{{ route('transactions.index') }}" class="btn btn-primary">Kembali</a>
@endsection
