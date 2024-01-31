<!-- resources/views/transaction/create-payment-proof.blade.php -->

@extends('layouts.main')

@section('content')
<h1 class="mt-4">Unggah Bukti Pembayaran</h1>
<form action="{{ route('transaction.storePaymentProof') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="transaction_id" class="form-label">Pilih Transaksi</label>
        <select class="form-control" id="transaction_id" name="transaction_id" onchange="previewTransactionImage()">
            @foreach($transactions as $transaction)
            @if(auth()->user()->is_admin || $transaction->user_id === auth()->user()->id)
            <option value="{{ $transaction->id }}" data-image="{{ asset('storage/' . $transaction->car->image) }}">
                {{ $transaction->id }} {{ $transaction->nama_pengguna }}
            </option>
            @endif
            @endforeach
        </select>

        <div class="mb-3">
            <label for="transaction_image" class="form-label">Gambar Kendaraan Terkait</label>
            <img src="" alt="" id="transaction_image_preview" width="100px">
        </div>

    </div>
    <div class="mb-3">
        <label for="payment_proof" class="form-label">Bukti Pembayaran</label>
        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Unggah Bukti Pembayaran</button>
    <input type="checkbox" id="modalToggle" style="display: none;">

    <div class="modal fade" id="detail" tabindex="-1" aria-labelledby="detailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Transfer</h5>
                    <label for="modalToggle" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></label>
                </div>
                <div class="modal-body">
                    <p>BRI: 3912-5678-9101</p>
                    <p>DANA/OVO/SHOPEEPAY: 0895636553207</p>
                    <p>QRis</p>
                    <img src="{{ asset('storage/payment_proofs/QR.png') }}" alt="Gambar Transfer" width="200">
                </div>
            </div>
        </div>
    </div>

    <!-- Button yang memicu modal -->
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detail">Detail Transfer</button>
</form>
@endsection
<script>
    function previewTransactionImage() {
        const transactionImagePreview = document.querySelector('#transaction_image_preview');
        const selectedTransaction = document.querySelector('#transaction_id');
        const selectedImage = selectedTransaction.options[selectedTransaction.selectedIndex].getAttribute('data-image');

        if (selectedImage) {
            transactionImagePreview.src = selectedImage;
            transactionImagePreview.style.display = 'block';
        } else {
            transactionImagePreview.style.display = 'none';
        }
    }
</script>
