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
@slot('title') List Warehouse @endslot
@endcomponent

<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="card">
      <div class="card-body">
        <a class="btn btn-success" href="{{ route('warehouse.create') }}"><i class="fas fa-plus"></i>  Tambah Warehouse</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
              <tr>
                <th>No.</th>
                <th>Warehouse ID</th>
                <th>Nama Warehouse</th>
                <th>Alamat</th>
                <th>remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($res_wh as $item)
              <tr>
                <td>{{ $loop->index + 1}}</td>
                <td>{{ $item->WAREHOUSE_ID}}</td>
                <td>{{ $item->WAREHOUSE_NAME}}</td>
                <td>{{ $item->ADDRESS}}</td>
                <td>{{ $item->REMARKS}}</td>
                <td>
                  <a class="btn btn-primary" href="{{ route('warehouse.edit',$item->WAREHOUSE_ID) }}"><i class="uil uil-pen font-size-18"></i></a>
                  <a class="btn btn-danger" href="{{ route('warehouse.destroy',$item->WAREHOUSE_ID) }}"><i class="uil uil-trash-alt font-size-18"></i></a>
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