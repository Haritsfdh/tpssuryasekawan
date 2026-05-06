@extends('layouts.master')
@section('title') Add Sales Report @endsection
@section('page-name') Add Sales Report  @endsection

@section('content')


<!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                  <h3 class="card-title mb-0">Add Sales Report</h3>
                </div>

                <div class="card-body">

                <form id="form-sales"
                  @if ($sales->id)
                  action="{{ route('product.sales.update', $sales->id) }}"
                  @else
                  action="{{ route('product.sales.store') }}"
                  @endif
                >

                @csrf
                @if ($sales->id)
                  @method('PUT')
                  @endif
                 <table class="table table-sm table-striped" id="table-sales">
              <thead>
                <tr>
                  <th>NO</th>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($sales->details as $detail)
                    <tr class="kolom" id="kolom_{{ $loop->iteration }}">
                        <!-- Primary Key (Hidden) -->
                        <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                        <td>{{ $loop->iteration }}</td>

                        <!-- Dropdown Product -->
                        <td>
                            <select name="product_id[]"
                                    id="product_{{ $loop->iteration }}"
                                    class="form-control select2product">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            {{ $detail->product_id == $product->id ? 'selected' : '' }} data-price={{ $product->price }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- Quantity -->
                        <td>
                            <input type="number"
                                name="quantity[]"
                                id="quantity_{{ $loop->iteration }}"
                                value="{{ $detail->quantity }}"
                                class="form-control qty"
                                data-baris={{ $loop->iteration }}>
                        </td>

                        <td>
                            <input type="number"
                                name="price[]"
                                id="price_{{ $loop->iteration }}"
                                value="{{ $detail->first()->product->price }}"
                                class="form-control"
                                readonly
                                >
                        </td>

                        <td>
                            <input type="number"
                                name="total_price[]"
                                id="total_price_{{ $loop->iteration }}"
                                value="{{ $detail->total_price }}"
                                class="form-control total-price"
                                readonly
                                >
                        </td>
                    </tr>
                @empty
                @endforelse
                @if($disabled != 'disabled')
                <tr id="trAkhir">
                        <td colspan="100%">
                          <button type="button" class="btn btn-primary btn-xs elevation-2" id="btnTambah">
                            <i class="fas fa-plus"></i>
                          </button>
                        </td>
                </tr>
                @endif
            </tbody>
            </table>

            <!-- Grand Total -->
            <div class="row justify-content-end mt-5 mb-2">
              <div class="col-md-4">
                <div class="card bg-light shadow-sm p-3">
                  <h6 class="mb-2 fw-bold">Grand Total</h6>
                  <h6 id="grand_total" class="text-end text-primary fw-bold m-0">Rp 0</h4>
                </div>
              </div>
            </div>
            <!-- End Grand Total -->
                </div>
                <div class="card-footer">
                      <button id="saveBtn" class="btn btn-success mt-3 w-25">
                        <i class="fas fa-save"></i> Save
                      </button>
                </div>
                </form>

            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->


@endsection

@section('footer')
<script>

  const csrf = '{{ csrf_token() }}';
  let table;
  let salesId = $('#sales_id').val() ?? null;


  function updateGrandTotal(table) {
    let total = 0;

    $('.total-price').each(function(){
        let number = parseInt($(this).val().replace(/\D/g, '')) || 0;
        total += number;
    });


    const formatted = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(total);

    $('#grand_total').text(formatted);
  }

      function products() {
    $('.select2product').select2({
            placeholder: 'Select...',
            allowClear: true,
            ajax: {
                url: "{{ route('select2.product') }}",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name + " ( " + item.price + " ) ",
                                id: item.id,
                                price: item.price
                            }
                        })
                    };
                },
                cache: true
            },
            templateSelection: function(container) {
                $(container.element).attr("data-price", container.price);
                return container.text;
            }
        });
    }


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

    $(document).ready(function(){
        $('#form-sales').on('submit', function(e){
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
              url: action,
              method: "POST",
              data: $(this).serialize(),
              success: function(response) {
                console.log(response);
                if (response.status == 'OK') {
                            showSuccess(response.message)
                        } else {
                            showError(response.message);
                        }
                          },
              error: function(xhr) {
                alert("Terjadi kesalahan: " + xhr.responseText);
              }
            });
        });

    //function jumlah total , yang trigger nya diselect atau ganti quantity

    // pake val . on(change, select2product, fu)

    $(document).on('change', '.select2product', function(){
        var baris = $(this).attr('data-baris');
        var price = $(this).find(':selected').data('price');
        var qty = $('#quantity_'+ baris).val();
        var total = price * qty;

        $('#price_'+ baris).val(price);
        $('#total_price_' + baris).val(total);

        console.log(total);
    });

    // Perhatikan Selectorrr
    $(document).on('change input paste', '.qty', function(){
    var baris = $(this).data('baris');
    var qty   = $(this).val();
    var price = parseFloat($('#product_'+ baris).find(':selected').data('price'));
    var total = price * qty;


    $('#total_price_' + baris).val(total).trigger('change');

    updateGrandTotal();
    });

    $(document).on('click', '#btnTambah', function(){

        var last = $('.kolom').length + 1;
        var lastTr = $('#trAkhir');

        $('<tr class="kolom" id="kolom_'+last+'"><input type="hidden" name="detail_id[]" value=""><td>'+last+'</td><td><select name="product_id[]"id="product_'+last+'"class="select2product" style="min-width: 300px" data-baris="'+last+'"></select></td><td><input type="number"name="quantity[]"id="quantity_'+last+'" class="form-control qty" data-baris="'+last+'"></td><td><input type="number"name="price[]"id="price_'+last+'"value=""class="form-control"readonly></td><td><input type="number"name="total_price[]"id="total_price_'+last+'"value=""class="form-control"readonly></td><td class="text-center"><button type="button" class="btn btn-danger btn-xs elevation-2 delete" data-id="'+last+'"><i class="fas fa-trash"></i></button></td></tr> ').insertBefore(lastTr);

        products();
        updateGrandTotal();
    });

});
</script>
@endsection
