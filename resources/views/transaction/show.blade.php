@extends('layouts.main')

@section('content')
<h1 class="mt-4">Pembayaran</h1>
<ol class="mb-4 breadcrumb">
    <li class="breadcrumb-item active">Homepage Pembayaran</li>
</ol>
<div class="d-flex" style="gap: 1rem;">
<a href="{{ route('transaction.createPaymentProof', $transaction->id) }}" class="btn btn-primary">Unggah Bukti Pembayaran</a>
</div>
<div class="mt-3 justify-content-center">
    <form action="{{ route('transaction.index') }}" method="GET">
    </form>
</div>
<table class="table table-bordered yajra-datatable">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Pelanggan</th>
            <th scope="col">Kendaraan</th>
            <th scope="col">Tanggal mulai</th>
            <th scope="col">Tanggal selesai</th>
            <th scope="col">Status</th>
            <th scope="col">Total</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="8" class="text-center">
                Belum ada data
            </td>
        </tr>
    </tbody>
</table>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
    $(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaction.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },

                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'car_id',
                    name: 'car_id'
                },
                {
                    data: 'date_start',
                    name: 'date_start'
                },
                {
                    data: 'date_end',
                    name: 'date_end'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },

            ]
        });
    });
</script>
@endpush
