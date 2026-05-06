@extends('layouts.master')
@section('title') Add Category @endsection
@section('page-name') Add Category  @endsection

@section('content')


<!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                @if ($name == 'create')
                  <h3 class="card-title mb-0">Add Category</h3>
                @else
                  <h3 class="card-title mb-0">Edit</h3>
                @endif
                </div>

                <div class="card-body">
                @if ($name == 'create')
                <form action="{{ route('product.category.store') }}" method="POST">
                        @csrf

                        <div class="form-group w-25">
                            <label for="category">Category Name</label>
                            <input type="text" name="category" id="category" class="form-control" placeholder="Insert Category" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add</button>
                </form>
                @else
                <form action="{{ route('product.category.update', $category->id)  }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PUT')
                    <input type="text"
                           name="update"
                           placeholder="Insert Category"
                           id="update"
                           class="form-control w-auto"
                           value="{{ old('update') ?? $category->name }}">
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                @endif
                </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->


@endsection
