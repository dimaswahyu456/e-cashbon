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
@slot('title') Edit Layanan @endslot
@endcomponent

@if ($errors->any())
<div class="alert alert-danger">
  <strong>Whoops!</strong> There were some problems with your input.<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
      <form action="{{ route('warehouse.update', $find->WAREHOUSE_ID) }}" method="POST">
        @csrf
        <!-- @method('PUT') -->
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label">Nama Warehouse :</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="WAREHOUSE_NAME" value="{{ $find->WAREHOUSE_NAME }}" placeholder="Nama Warehouse">
          </div>
          <br><br>

          <label class="col-md-2 col-form-label">Address :</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="ADDRESS" value="{{ $find->ADDRESS }}" placeholder="Address">
          </div>
          <br><br>

          <label class="col-md-2 col-form-label">City :</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="CITY" value="{{ $find->CITY }}" placeholder="City">
          </div>
          <br><br>

          <label class="col-md-2 col-form-label">Remarks :</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="REMARKS" value="{{ $find->REMARKS }}" placeholder="Remarks">
          </div>
        </div>

        <div class="pull-right">
          <a class="btn btn-primary" href="{{ route('warehouse.list') }}">Back</a>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>

      </div>
    </div>
  </div> <!-- end col -->
</div>

@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
@endsection