@extends('layouts.master')
@section('title')
    @lang('translation.Profile')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Supplier @endslot
        @slot('title') Detail Supplier @endslot
    @endcomponent

    <div class="row mb-2">
        <div class="col-md-6 col-lg-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="clearfix"></div>
                        <div>
                          <i class="fas fa-network-wired fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $find->VEND_NAME }}</h5>
                        <p class="text-muted">{{ $find->VEND_ID }}</p>
                    </div>

                    <hr class="my-4">

                    <div class="text-muted">
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Keterangan :</p>
                            <h5 class="font-size-16">
                                {{ $find->REMARKS }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Nominal Cashbon :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->NOMINAL, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Sudah teralokasi :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->NOMINAL_SETTLAMENT, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Nominal Belum di LPJ :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->OUTSTANDING, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('cashbon.list') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="clearfix"></div>
                        <div>
                          <i class="fas fa-server fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-1">Data Detail Cashbon</h5>
                    </div>

                    <hr class="my-4">
                    <a class="btn btn-success" href=""><i class="fas fa-plus"></i>Add Data</a><br><br>
                    
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Keterangan</th>
                                <th>Total</th>
                                <th>Lampiran</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($res_detail as $item)
                                <tr>
                                    <td>{{ $loop->index + 1}}</td>
                                    <td>{{ $item->doc_date}}</td>
                                    <td>{{ $item->keterangan}}</td>
                                    <td>{{ number_format($item->total, 2, ',', '.')}}</td>
                                    <td>@if(!empty($item->file))
                                        <a class="btn btn-info" href=""><i class="fas fa-paperclip"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                    <a class="btn btn-primary" href=""><i class="uil uil-pen font-size-16"></i></a>
                                    @csrf
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="clearfix"></div>
                        <div>
                          <i class="fas fa-network-wired fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $find->VEND_NAME }}</h5>
                        <p class="text-muted">{{ $find->VEND_ID }}</p>
                    </div>

                    <hr class="my-4">

                    <div class="text-muted">
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Keterangan :</p>
                            <h5 class="font-size-16">
                                {{ $find->REMARKS }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Nominal Cashbon :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->NOMINAL, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Sudah teralokasi :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->NOMINAL_SETTLAMENT, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Nominal Belum di LPJ :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($find->OUTSTANDING, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('supplier.list') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        
                
    <!-- end row -->
@endsection
