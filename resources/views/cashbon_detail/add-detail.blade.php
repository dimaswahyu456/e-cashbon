@extends('layouts.master')
@section('title')
@lang('translation.Datatables')
@endsection
@section('css')
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Tables @endslot
@slot('title') Add Layanan @endslot
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
        <form action="{{ route('layanan.add') }}" method="POST">
          @csrf
          <div class="mb-3 row">
            <label for="example-text-input" class="col-md-2 col-form-label">Nama Layanan : </label>
            <div class="col-md-10">
              <input class="form-control" type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" id="example-text-input" placeholder="Nama Layanan">
            </div>
            <br><br>
            <label for="example-text-input" class="col-md-2 col-form-label">harga : </label>
            <div class="col-md-10">
              <input class="form-control" type="text" name="harga" value="{{ old('harga') }}" id="example-text-input" placeholder="harga">
            </div>
            <br><br>
            <label for="example-text-input" class="col-md-2 col-form-label">Keterangan : </label>
            <div class="col-md-10">
              <input class="form-control" type="text" name="keterangan" value="{{ old('keterangan') }}" id="example-text-input" placeholder="Keterangan">
            </div>
          </div>
          <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('layanan.list') }}"> Back</a>
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
<script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endsection