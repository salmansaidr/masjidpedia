@extends('main')

@section('css')
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #0d6efd;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
  margin: auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('content')
    <div class="alert alert-success alert-success-abs fade hide" role="alert">
        <span></span>
    </div>
    <div class="content-head d-flex justify-content-between align-items-center">
        <h3>List Request Produk Dari Toko</h3>
    </div>
    <div class="card content-value mt-2 shadow">
        <div class="card-body">
            <table class="table table-striped" id="tableData" style="width: 100%;">
                <thead>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Toko</th>
                    <th>Produk</th>
                    <th>Pilihan</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="modalApprove" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Apakah anda ingin menyetujui permintaan produk ini ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-primary btn-approve" idrequest=""></button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close close-product" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body body-product">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
var requestModal =  new bootstrap.Modal(document.getElementById('modalApprove'), {
  keyboard: false
});
var productModal =  new bootstrap.Modal(document.getElementById('modalProduct'), {
  keyboard: false
});

$(document).ready( function () {
    getRequest();

    $('body').on('click', '.product-detail', function(e) {
        e.preventDefault();
        var requestID = $(this).attr('idrequest');
        $('.body-product').html(`<div class="loader"></div>`);
        productModal.show();
        $.ajax({
            type: "GET",
            url: "{{ route('product-request.detail') }}" + `/${requestID}`,
            success(res) {
                $('.body-product').html(res);
            }
        })
    });

    $('.close-product').on('click', function() {
        productModal.hide();
    })

    $('body').on('click', '.approve-btn', function() {
        $('.btn-approve').text('Ya');
        $('.btn-approve').attr('disabled', false);
        var requestID = $(this).attr('idrequest');
        $('.btn-approve').attr('idrequest', requestID);
        requestModal.show();
    });

    $('.btn-approve').on('click', function() {
        var requestID = $(this).attr('idrequest');
        $.ajax({
            type: "PUT",
            url: "{{ route('product-request.approve') }}" + `/${requestID}`,
            data: { _token: "{{ csrf_token() }}" },
            beforeSend() {
                $('.btn-approve').text('menghapus...');
                $('.btn-approve').attr('disabled', true);
            },
            success() {
                getRequest();
                requestModal.hide();
                showSuccessMessage("Permintaan Produk berhasil disetujui");
            }
        })
    });

    function showSuccessMessage(msg) {
        $('.alert-success-abs').find('span').text(msg)
        $('.alert-success-abs').removeClass('hide')
        $('.alert-success-abs').addClass('show')
        setTimeout(function() {
            $('.alert-success-abs').removeClass('show')
            $('.alert-success-abs').addClass('hide')
        }, 3000)
    }

    function getRequest() {
        $('#tableData').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax":{
                "url": "{{ route('product-request.datatable') }}",
                "dataType": "json",
                "data": {
                    "_token": "{{ csrf_token() }}"
                },
                "type": "GET",
            },
            "columns": [
                { "data": "id" },
                { "data": "tanggal" },
                { "data": "toko" },
                { "data": "product" }, 
                { "data": "options" }
            ],
            "autoWidth": true,
            "columnDefs": [
                { "orderable": false, 
                    "targets": [0,1,2,3,4] 
                },    
            ],
            "scrollX":        true,
            "scrollCollapse": true,
            "paging":         true
        });
    }
})
</script>
@endsection