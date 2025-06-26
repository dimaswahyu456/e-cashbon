@extends('layouts.master')
@section('title')
@lang('translation.Datatables')
@endsection
@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Tables @endslot
@slot('title') List Sudah Approved @endslot
@endcomponent

<!-- <div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="card">
      <div class="card-body">
        <a class="btn btn-success" href=""><i class="fas fa-plus"></i>  Tambah Warehouse</a>
      </div>
    </div>
  </div>
</div> -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
              <tr>
                <th>No.</th>
                <th>No. Dokumen</th>
                <th>Nama Vendor</th>
                <th>Doc Type</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>              
              @foreach ($res_cashbon as $docId => $items)
                  @php $first = $items->first(); @endphp
                  <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $docId }}</td>
                      <td>{{ $first->nama_vendor }}</td>
                      <td>{{ $first->DOC_TYPE }}</td>
                      <td>{{ number_format($items->sum('TOTAL'), 2, ',', '.') }}</td>
                      <td>
                          <a class="btn btn-primary" href="{{ route('acc.show', ['id' => str_replace('/', '_', $docId)]) }}">
                              <i class="uil uil-eye"></i>
                          </a>
                      </td>
                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->

@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection