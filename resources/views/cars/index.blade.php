@extends('layouts.main')

@section('content')
<h1 class="mt-4">Kendaraan</h1>
<ol class="mb-4 breadcrumb">
    <li class="breadcrumb-item active">Homepage Kendaraan</li>
</ol>
@if(auth()->user()->is_admin == 1)
<div class="d-flex">
    <a href="{{ route('car.create') }}" class="btn btn-primary">Tambah Kendaraan</a>
</div>
@endif
<div class="mt-3 justify-content-center">
    <form action="{{ route('car.index') }}" method="GET">
        <div class="row">
            <div class="col">
                <div class="input-group mb-3">
                    <input type="text" value="{{ Request::input('search') }}" class="form-control" placeholder="search . . ." name="search">
                </div>
            </div>
            <div class="col-1">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
        </div>
    </form>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Merk</th>
            <th scope="col">Image</th>
            <th scope="col">Plat</th>
            <th scope="col">Status</th>
            <th scope="col">Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cars as $car)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $car->name }}</td>
            <td><img src="{{ asset('storage/' . $car->image) }}" alt="" style="max-width: 100px; max-height: 100px;"></td>
            <td>{{ $car->plat }}</td>
            <td>@if($car->status == 1)
                            <span class="badge bg-success">Available</span>
                            @else
                            <span class="badge bg-danger">Not Available</span>
                            @endif</td>
            <td>{{ $car->price }}</td>

            <td>
            <td>
                @if(auth()->user()->is_admin == 1)
                <form class="d-inline" action="{{ route('car.destroy', $car->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" onclick="return confirm('Hapus data mobil ini?')">Hapus</button>
                </form>
                <a href="{{ route('car.edit', $car->id) }}" class="btn btn-success">Edit</a>
                @endif
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detail{{ $car->id }}">Detail</button>

            </td>

        </tr>

        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="detail{{ $car->id }}" tabindex="-1" aria-labelledby="detail{{ $car->id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Nama : {{ $car->name }}</p>
                        <p>Plat : {{ $car->plat }}</p>
                        <p>Deskripsi : {{ $car->description }}</p>
                        <p>Harga : {{ number_format($car->price, 2) }}</p>
                        <p>Status : @if($car->status == 1)
                            <span class="badge bg-success">Available</span>
                            @else
                            <span class="badge bg-danger">Not Available</span>
                            @endif
                        </p>
                        <p>
                            <img src="{{ asset('storage/' . $car->image) }}" width="400" alt="">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @empty
        <tr>
            <td colspan="6" class="text-center">
                Belum ada data
            </td>
        </tr>
        @endforelse

    </tbody>
</table>
{{ $cars->links() }}

@endsection
