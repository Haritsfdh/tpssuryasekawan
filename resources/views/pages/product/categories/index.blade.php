@extends('layouts.master')
@section('title') Category @endsection
@section('page-name') Category @endsection

@section('content')

@include('components.flash') 
 <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Category Page</h3>
                @include('buttons.add', ['link' => url()->current().'/create'])
            </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-light">
                    <tr class="text-uppercase text-sm">
                        <th class="p-3">No</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="text-sm">
                            <td class="p-3">{{ $loop->iteration }}</td>
                            <td class="p-3">{{ $category->name }}</td>
                            <td class="p-3 d-flex gap-2">
                                    {{-- button edit --}}
                                    @include('buttons.edit', ['link' => route('product.category.edit', $category->id)])

                                    
                            <!-- Tombol trigger modal -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelete-{{ $category->id }}">
                                <i class="fas fa-trash"></i> Delete
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="modalDelete-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('product.category.delete', $category->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-{{ $category->id }}">Delete Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure to delete <strong>{{ $category->name }}</strong> Category ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          
                                  

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection