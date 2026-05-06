@extends('layouts.master')
@section('title') Users @endsection
@section('page_name') User Lists @endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Users</h3>
            </div>
            <div class="card-body">      
              @include('buttons.add', ['link' => url()->current().'/create'])
              @include('buttons.download', ['link' => '/administrator/download?model=Users'])
              @include('buttons.upload', ['target' => 'modal-upload'])
              @include('table.ajax')
            </div>
          </div>          
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

  <!-- Modal Upload -->
<div class="modal fade" id="modal-upload">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Upload</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formUpload"
            class="form-horizontal" 
            action="{{ route('admin.upload') }}" 
            method="POST" 
            enctype="multipart/form-data">
				@csrf
        <input type="hidden" name="model" value="Users">
				<div class="modal-body">
					<div class="form-group">
						<label>Pilih File</label>
						<input type="file" class="form-control" name="upload" required="required" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
					</div>							
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Upload</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('footer')
  <script>
    jQuery(document).ready(function(){
      $('#dataAjax').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url()->current() }}',
        columns:[
          @forelse ($items as $keys => $item)            
            {data: "{{$keys}}", name: "{{$keys}}"},
          @empty
          @endforelse          
        ]
      });
    })
  </script>
@endsection