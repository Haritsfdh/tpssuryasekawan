@extends('layouts.master')
@section('title') Add Product @endsection
@section('page-name') Add Product  @endsection

@section('content')


<!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">

                  <h3 class="card-title mb-0">Add Product</h3>
                </div>

                <div class="card-body">
                <form id = "form-create">
                    @csrf

                    <div class="form-group w-25">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Insert Product" required>
                    </div>

                    <div class="form-group w-25">
                        <label for="price">Price</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control" id="price">
                    </div>

                    <div class="form-group w-25">
                        <label for="category_id">Category</label>
                    <x-select2-ajax
                        id="category_id"
                        name="category_id"
                        :route="route('category.search')"
                        placeholder="Select Category"
                        width="300px">
                    </x-select2-ajax>

                    </div>

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
                </div>
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
      $(document).ready(function(){
        $('#form-create').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url: '{{ route('product.product.store') }}',
              method: "POST",
              data: $(this).serialize(),
              success: function(response) {
                showSuccess(response.message);
                $('#form-create')[0].reset();
                window.location.href = '{{ route("product.product") }}'; // redirect balik ke index
              },
              error: function(xhr) {
                showError("Terjadi kesalahan: " + xhr.responseText);
              }
            });
        });
    });

//     $(document).ready(function(){
//     $('.category-select').select2({
//       placeholder: 'Select Category',

//       ajax: {
//         url: '{{ route("category.search") }}',
//         dataType: 'json',
//         delay: 250,
//         data: function(params){
//           return {
//             q:params.term
//           };
//         },
//         processResults: function(data){
//           return {
//             results: data
//           };
//         },
//         cache: true
//       }
//     });
//   })

    </script>

@endsection
