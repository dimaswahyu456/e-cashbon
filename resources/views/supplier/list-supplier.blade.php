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
@slot('title') List Supplier @endslot
@endcomponent

<!-- <div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="card">
      <div class="card-body">
        <a class="btn btn-success" href="{{ route('warehouse.create') }}"><i class="fas fa-plus"></i>  Tambah Supplier</a>
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
                <th>Supplier ID</th>
                <th>Nama Supplier</th>
                <th>Type</th>
                <th>City</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($res_supplier as $item)
              <tr>
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $item->VEND_ID}}</td>
                <td>{{ $item->VEND_NAME}}</td>
                <td>{{ $item->VEND_TYPE}}</td>
                <td>{{ $item->CITY}}</td>
                <td>
                  <a class="btn btn-info" href="{{ route('supplier.show',$item->VEND_ID) }}"><i class="uil uil-eye font-size-18"></i></a>
                  @csrf
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