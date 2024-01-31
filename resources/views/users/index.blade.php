@extends('layouts.main')

@section('content')
<h1 class="mt-4">USER</h1>
<ol class="mb-4 breadcrumb">
    <li class="breadcrumb-item active">Homepage User</li>
</ol>
@if(auth()->user()->is_admin == 1)
<div class="d-flex">
    <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
</div>
@endif
<div class="mt-3 justify-content-center">
    <form action="{{ route('user.index') }}" method="GET">
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
        <th scope="col">ID</th>
            <th scope="col">Nama</th>
            <th scope="col">Image</th>
            <th scope="col">Email</th>
            <th scope="col">Sejak</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse($users as $user)
    @if(auth()->user()->is_admin == 1 || auth()->user()->id == $user->id)
        <tr>
        <th scope="row">{{ $user->id }}</th>
            <td>
                <div class="position-relative">
                    {{ $user->name }}
                    @if($user->is_admin)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            admin
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </div>
            </td>
            <td>
                <img src="{{ asset('storage/' . $user->image) }}" width="50" class="rounded-circle" alt="">
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
            <td>
                <!-- Hanya tampilkan tombol edit, detail, dan hapus untuk admin atau pemilik data -->
               
                    <!-- Tombol Edit hanya untuk admin -->
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success">Edit</a>

                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detail{{ $user->id }}">
                    Detail
                </button>
                <!-- Tombol Hapus hanya untuk admin -->
                @if(auth()->user()->is_admin == 1)
                    <form class="d-inline" action="{{ route('user.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger" onclick="return confirm('Hapus data pengguna ini?')">Hapus</button>
                    </form>
                @endif
            </td>

            </td>
            <!-- Modal -->
            <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="detail{{ $user->id }}" tabindex="-1" aria-labelledby="detail{{ $user->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Nama : {{ $user->name }}</p>
                            <p>Email : {{ $user->email }}</p>
                            <p>Status : @if($user->is_admin == 1)
                                <span class="badge bg-danger">Admin</span>
                                @else
                                <span class="badge bg-success">User</span>
                                @endif
                            </p>
                            <p>
                                Sejak : {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                            </p>
                            <p>
                                <img src="{{ asset('storage/' . $user->image) }}" width="400" alt="">

                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </tr>

        </tr>
        @endif
        @empty
        <tr>
            <td colspan="6" class="text-center">
                Belum ada data
            </td>
        </tr>
        @endforelse
        {{ $users->links() }}
    </tbody>
</table>
@endsection
