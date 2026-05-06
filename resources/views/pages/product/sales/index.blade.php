@extends('layouts.master')
@section('title') Sales Page @endsection
@section('page-name') Sales Page @endsection

@section('content')
@include('components.flash')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Sales Page</h3>
          </div>
          <div class="card-body">
            @include('buttons.add', ['link' => url()->current().'/create'])

            <table class="table table-sm table-striped" id="table-sales">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Username</th>
                  <th>Product List</th>
                  <th>Total Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>

            <!-- Grand Total -->
            <div class="row justify-content-end mt-5 mb-2">
              <div class="col-md-4">
                <div class="card bg-light shadow-sm p-3">
                  <h6 class="mb-2 fw-bold">Grand Total</h6>
                  <h6 id="grand_total" class="text-end text-primary fw-bold m-0">
                    @foreach ($sales as $sale)
                        {{ number_format($sale->grand_total, 0, ',', '.') }}
                    @endforeach
                  </h6>
                </div>
              </div>
            </div>
            <!-- End Grand Total -->

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

  function updateGrandTotal(table) {
     $.ajax({
    url: '{{ route("product.sales") }}',
    data: { get_total: 1 },
    success: function(res) {
      const formatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(res.total);

      $('#grand_total').text(formatted);
    },
    error: function() {
      $('#grand_total').text("Rp 0");
    }
  });
  }

  $(document).ready(function () {
    table = $('#table-sales').DataTable({
      paging: false,        // Hilangkan pagination
      info: false,          // Hilangkan info "Showing x to x of x entries"
      processing: true,
      serverSide: true,
      ajax: '{{ route("product.sales") }}',
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'date', name: 'date' },
        { data: 'user_name', name: 'user_name' },
        { data: 'product_list', name: 'product_list'},
        { data: 'grand_total', name: 'grand_total'},
        { data: 'action', name: 'action' }
      ],
      drawCallback: function () {
        updateGrandTotal();
      }
    });
  });

  // Inline Edit
  $(document).on('click', '.editable', function () {
    let span = $(this);
    let currentValue = span.text();
    let id = span.data('id');
    let field = span.data('field');

    let input = $('<input type="number" step="1" min="1" class="form-control">').val(currentValue);
    span.replaceWith(input);
    input.focus();

    input.blur(function () {
      let newValue = $(this).val();

      if (newValue != currentValue) {
        $.ajax({
          url: `/product/sales/update/${id}`,
          method: 'POST',
          data: {
            _token: csrf,
            field: field,
            value: newValue
          },
          success: function () {
            table.ajax.reload(null, false);
          },
          error: function () {
            alert('Update failed');
          }
        });
      } else {
        $(this).replaceWith(`<span class="editable" data-id="${id}" data-field="${field}">${currentValue}</span>`);
      }
    });
  });

  // Delete
  $(document).on('click', '.deletes', function () {
    let buttonDelete = $(this);
    let href = buttonDelete.data('url');
    let id = buttonDelete.data('id');

    if (confirm('Are you sure you want to delete this data?')) {
      $.ajax({
        url: href,
        method: 'DELETE',
        data: { _token: csrf },
        success: function () {
          table.ajax.reload(null, false);
        },
        error: function () {
          alert("Delete failed");
        }
      });
    }
  });
</script>
@endsection
