@extends('main')

@section('content')
    <div class="alert alert-success alert-success-abs fade hide" role="alert">
        <span></span>
    </div>
    <div class="content-head d-flex justify-content-between align-items-center">
        <h3>List Produk</h3>
        <div class="head-button">
            <button class="btn btn-success btn-add-product">Add</button>
        </div>
    </div>
    <div class="card content-value mt-2 shadow">
        <div class="card-body">
            <table class="table table-striped" id="tableData" style="width: 100%;">
                <thead>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Pilihan</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-product">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stock">
                            <small class="text-danger"></small>
                        </div>
                
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type" idproduct="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-submit"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Apakah anda ingin menghapus produk ini ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-primary btn-delete" idproduct="">Ya</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
var productModal =  new bootstrap.Modal(document.getElementById('modalProduct'), {
  keyboard: false
});
var deleteProductModal =  new bootstrap.Modal(document.getElementById('modalDeleteProduct'), {
  keyboard: false
});

$(document).ready( function () {
    getProducts();

    $('.btn-add-product').on('click', function() {
        $('.modal-title').text('Tambah Produk');
        $('.btn-submit').text('Tambah');
        $('#form-product')[0].reset();
        resetError();
        $('input[name="type"]').val('add');
        $('input[name="type"]').attr('idproduct', '');
        productModal.show();
    });

    $('body').on('click', '.edit-btn', function() {
        $('input[name="type"]').val('edit');
        $('.modal-title').text('Edit Produk');
        $('.btn-submit').text('Update');
        var productID = $(this).attr('idproduct');
        $.ajax({
            type: "GET",
            url: `{{ route('product.edit') }}` + `/${productID}`,
             success(res) {
                $(`input[name="name"]`).val(res.name);
                $(`input[name="stock"]`).val(res.stock);
                $('input[name="type"]').attr('idproduct', productID);
                productModal.show();
             }
        })
    });

    $('body').on('click', '.delete-btn', function() {
        $('.btn-delete').text('Ya');
        $('.btn-delete').attr('disabled', false);
        var productID = $(this).attr('idproduct');
        $('.btn-delete').attr('idproduct', productID);
        deleteProductModal.show();
    });

    $('.btn-delete').on('click', function() {
        var productID = $(this).attr('idproduct');
        $.ajax({
            type: "DELETE",
            url: "{{ route('product.delete') }}" + `/${productID}`,
            data: { _token: "{{ csrf_token() }}" },
            beforeSend() {
                $('.btn-delete').text('menghapus...');
                $('.btn-delete').attr('disabled', true);
            },
            success() {
                getProducts();
                deleteProductModal.hide();
                showSuccessMessage("Produk berhasil dihapus");
            }
        })
    });

    $('#form-product').on('submit', function(e) {
        e.preventDefault();
        
        var typeForm = $('input[name="type"]').val();

        var name = $('input[name="name"]').val();
        var stock = $('input[name="stock"]').val();

        if(typeForm === "add") {
            addProduct(name, stock);
        } else {
            var productID = $('input[name="type"]').attr('idproduct');
            updateProduct(productID, name, stock)
        }
    });

    function addProduct(name, stock) {
        $.ajax({
            url: "{{ route('product.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                name,
                stock
            },
            beforeSend() {
                resetError();
                $('.btn-submit').text('menambahkan...');
                $('.btn-submit').attr('disabled', true);
            },
            success(msg) {
                successAjax('Tambah', 'Produk berhasil ditambahkan');
            },
            error(err) {
                failedAjax('Tambah', err);
            }
        })
    }

    function updateProduct(id, name, stock) {
        $.ajax({
            url: "{{ route('product.update') }}" + `/${id}`,
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                name,
                stock
            },
            beforeSend() {
                resetError();
                $('.btn-submit').text('mengupdate...');
                $('.btn-submit').attr('disabled', true);
            },
            success(msg) {
                successAjax("Update", "Produk berhasil diubah")
            },
            error(err) {
               failedAjax('Update', err);
            }
        })
    }

    function successAjax(btnText, successMsg) {
        $('.btn-submit').text(btnText);
        $('.btn-submit').attr('disabled', false);
        getProducts();
        productModal.hide();
        showSuccessMessage(successMsg);
    }

    function showSuccessMessage(msg) {
        $('.alert-success-abs').find('span').text(msg)
        $('.alert-success-abs').removeClass('hide')
        $('.alert-success-abs').addClass('show')
        setTimeout(function() {
            $('.alert-success-abs').removeClass('show')
            $('.alert-success-abs').addClass('hide')
        }, 3000)
    }

    function failedAjax(btnText, err) {
        $('.btn-submit').text(btnText);
        $('.btn-submit').attr('disabled', false);
        var errorProduct = err.responseJSON.errors;
        for (var property in errorProduct) {
            $(`input[name="${property}"]`).addClass('is-invalid');
            $(`input[name="${property}"]`).parent().find('small').text(errorProduct[property][0]);
        }
    }

    function resetError() {
        $(`input[name="name"]`).removeClass('is-invalid');
        $(`input[name="name"]`).parent().find('small').text('');
        $(`input[name="stock"]`).removeClass('is-invalid');
        $(`input[name="stock"]`).parent().find('small').text('');
    }

    function getProducts() {
        $('#tableData').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax":{
                "url": "{{ route('product.datatable') }}",
                "dataType": "json",
                "data": {
                    "_token": "{{ csrf_token() }}"
                },
                "type": "GET",
            },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "stock" },
                { "data": "options" }
            ],
            "autoWidth": true,
            "columnDefs": [
                { "orderable": false, 
                    "targets": [0,1,2,3] 
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