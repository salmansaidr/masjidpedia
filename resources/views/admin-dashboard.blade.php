@extends('main')

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h3>Supplier: {{ $supplier }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h3>Toko: {{ $store }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection