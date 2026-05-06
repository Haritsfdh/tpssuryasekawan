@extends('layouts.master')

@section('title') Products @endsection
@section('page-name') Products @endsection

@section('content')
@include('components.flash')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Products Page</h3>
            @include('buttons.add', ['link' => url()->current().'/create'])
          </div>

          <div class="card-body">
            <table class="table table-bordered table-striped" id="table-product">
              <thead class="table-light">
                <tr class="text-uppercase text-sm">
                  <th class="p-3">No</th>
                  <th class="p-3">Product</th>
                  <th class="p-3">Price</th>
                  <th class="p-3">Category</th>
                  <th class="p-3">Action</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@section('footer')
<script>
const csrf = '{{ csrf_token() }}';
let table;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

$(document).ready(function() {
   table = $('#table-product').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("product.product") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'price', name: 'price',
                render: function (data, type, row) {
                    return `<span class="editable" data-id="${row.id}" data-field="price">${data}</span>`;
                }
            },
            { data: 'product_category', name: 'product_category' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    let csrf = '{{ csrf_token() }}'; // Tambahkan ini

    $(document).on('click', '.editable', function () {
        let span = $(this);
        let currentValue = span.text().replace(/[^0-9]/g, ''); // Hapus Rp dan titik
        let id = span.data('id');
        let field = span.data('field');

        console.log()

        let input = $('<input type="number" step="1" min="1" class="form-control form-control-sm">').val(currentValue);
        span.replaceWith(input);
        input.focus();

        input.blur(function () {
            let newValue = $(this).val();

            if (newValue != currentValue) {
                $.ajax({
                    url: `/product/product/${id}`,
                    type: 'PUT',
                    data: {
                        _token: csrf,
                        field: field,
                        value: newValue
                    },
                    success: function () {
                        showSuccess('Update successful');
                        table.ajax.reload(null, false);
                    },
                    error: function () {
                        showError('Update failed');
                        input.replaceWith(`<span class="editable" data-id="${id}" data-field="${field}">Rp. ${formatNumber(currentValue)}</span>`);
                    }
                });
            } else {
                input.replaceWith(`<span class="editable" data-id="${id}" data-field="${field}">Rp. ${formatNumber(currentValue)}</span>`);
            }
        });
    });

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});

//Delete feature
 $(document).on('click', '.deletes', function () {
    let buttonDelete = $(this);
    let id = buttonDelete.data('id');

    if (confirm('Are you sure you want to delete this data?')) {
      $.ajax({
        url: "{{ url('/product/product') }}/" + id,
        method: 'DELETE',
        data: { _token: csrf },
        success: function (msg) {
          if(msg.status === 'OK') {
          showSuccess(msg.message);
          table.ajax.reload(null, false);
          }
        },
        error: function(xhr) {
          showError("Terjadi kesalahan: " + xhr.responseText);
        }
      });
    }
  });
</script>
@endsection
