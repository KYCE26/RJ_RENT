@extends('layouts.main')
@section('content')
<h1 class="mt-4">Transaksi</h1>
<ol class="mb-4 breadcrumb">
    <li class="breadcrumb-item active">Tambah Transaksi</li>
</ol>
<form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data">
@csrf()
<div class="mb-3">
    <label for="user_id" class="form-label">Nama Pelanggan</label>
    <select class="form-select" id="user_id" name="user_id" @if(auth()->user()->is_admin != 1) @endif>
        <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
        @if(auth()->user()->is_admin == 1)
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        @endif
    </select>
    @error('user_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

    <div class="mb-3">
    <label for="name" class="form-label">Pilih Mobil</label>
    <select class="form-select" id="status" name="car_id">
        @foreach($cars as $car)
            @if($car->status == 1) <!-- Tambahkan kondisi ini -->
                <option {{ (old('car_id') == $car->id ? 'selected' : '') }} value="{{ $car->id }}">{{ $car->name }}</option>
            @endif
        @endforeach
    </select>
    @error('car_id')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

    <div class="mb-3">
    <label for="date_start" class="form-label">Tanggal Pinjam</label>
    <input type="date" class="form-control @error('date_start') is-invalid @enderror" name="date_start" id="date_start" value="{{ old('date_start') }}">
    @error('date_start')
    <span class="invalid-feedback">
        {{ $message }}
    </span>
    @enderror
</div>

<div class="mb-3">
    <label for="date_end" class="form-label">Tanggal Kembali</label>
    <input type="date" class="form-control @error('date_end') is-invalid @enderror" name="date_end" id="date_end" value="{{ old('date_end') }}" min="{{ date('Y-m-d') }}">
    @error('date_end')
    <span class="invalid-feedback">
        {{ $message }}
    </span>
    @enderror
</div>

<script>
    // Mengatur nilai tanggal minimum untuk tanggal_kembali
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    document.getElementById("date_end").setAttribute("min", today);
</script>

    </div>
    <div class="mb-3">
        <label for="note" class="form-label">Note</label>
        <textarea name="note" id="note" class="form-control @error('note') @enderror">{{ old('note') }}</textarea>
        @error('note')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection
