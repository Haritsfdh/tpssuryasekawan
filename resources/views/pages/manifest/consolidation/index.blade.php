@extends('layouts.master')
@section('title') Consolidation @endsection
@section('page-name') Consolidation @endsection

@section('content')

@include('components.flash')
 <!-- Main content -->
 <section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">

        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Consolidation</h3>
            <div class="card-tools">
            @can('create_manifest_consolidation')
            <a href="{{ route('manifest.consolidation.create') }}"
                 class="btn btn-info">
                 <i class="fas fa-plus"></i> Add
              </a>
            <button class="btn btn-info ml-2"
                      data-toggle="modal"
                      data-target="#modal-legacy">
                <i class="fas fa-plus"></i> Add Legacy
              </button>
            @endcan
          </div>
          </div>

          <div class="card-body">
            <table class="table table-sm table-striped" id="table-master">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NPWP</th>
                  <th>Airline Code</th>
                  <th>MAWB Number</th>
                  <th>Arrival Date</th>
                  <th>Masuk Gudang</th>
                  <th>PU Number</th>
                  <th>Total Collie</th>
                  <th>Gross Weight</th>
                  <th>HAWB Count</th>
                  <th>Pending</th>
                  <th>Pending XRAY</th>
                  <th>Released</th>
                  <th>Upload Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
  <!-- /.content -->

@endsection

@section('footer')

<script>
    let table;

    $(document).ready(function () {
    table = $('#table-master').DataTable({
      paging: true,        // Hilangkan pagination
      info: true,          // Hilangkan info "Showing x to x of x entries"
      processing: true,
      serverSide: true,
      ajax: '{{ route("manifest.consolidation") }}',
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'NPWP', name: 'NPWP'},
        { data: 'AirlineCode', name: 'AirlineCode' },
        { data: 'MAWBNumber', name: 'user_name' },
        { data: 'ArrivalDate', name: 'product_list'},
        { data: 'MasukGudang', name: 'MasukGudang'},
        { data: 'PUNumber', name: 'MasukGudang'},
        { data: 'mNoOfPackages', name: 'MasukGudang'},
        { data: 'mGrossWeight', name: 'mGrossWeight'},
        { data: 'HAWBCount', name: 'MasukGudang'},
        { data: 'pending', name: 'MasukGudang'},
        { data: 'pendingXRAY', name: 'pendingXRAY' },
        { data: 'released', name: 'released' },
        { data: 'UploadStatus' , name: 'UploadStatus'},
        { data: 'action', name: 'action' }
      ]
    });
  });
</script>
@endsection
