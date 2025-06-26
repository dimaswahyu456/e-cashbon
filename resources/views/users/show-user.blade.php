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
@slot('title') Show User @endslot
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
                @csrf
                <!-- @method('POST') -->

                <div class="mb-3 row">
                    <label for="example-text-input" class="col-md-2 col-form-label">Nama : </label>
                    <div class="col-md-10">
                        <input type="text" name="name" value="{{ $find->name }}" class="form-control" placeholder="Nama User">
                    </div>
                    <br><br>
                    {{-- <label for="example-text-input" class="col-md-2 col-form-label">Role : </label>
                    <div class="col-md-10">
                        <select name="role" id="userSelectCategory" class="form-select" aria-label="Floating label select">
                            @foreach ($res_role as $item)
                            @if ($find->id == $item->id)
                            <option value="{{$item->id}}" selected>{{$item->level}}</option>
                            @else
                            <option value="{{$item->id}}">{{$item->level}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <br><br> --}}
                    <label for="example-text-input" class="col-md-2 col-form-label">email : </label>
                    <div class="col-md-10">
                        <input type="text" name="email" value="{{ $find->email }}" class="form-control" placeholder="email">
                    </div>
                    <br><br>
                    <label for="example-text-input" class="col-md-2 col-form-label">Password : </label>
                    <div class="col-md-10">
                        <input type="password" name="password" class="form-control" value="" id="password" placeholder="Enter password">
                    </div>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('user.list') }}"> Back</a>
                </div>
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