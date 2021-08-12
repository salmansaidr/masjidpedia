@extends('main')

@section('content')
    <div class="alert alert-success alert-success-abs fade hide" role="alert">
        <span></span>
    </div>
    <div class="content-head d-flex justify-content-between align-items-center">
        <h3>Detail Produk</h3>
    </div>
    <div class="card content-value mt-2 shadow">
        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td style="width: 15%;">Nama</td>
                        <td>:</td>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>:</td>
                        <td>{{ $product->user->name }}</td>
                    </tr>
                    <tr>
                        <td>Stok Tersedia</td>
                        <td>:</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection