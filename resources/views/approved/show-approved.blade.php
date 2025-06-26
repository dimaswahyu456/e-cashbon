@extends('layouts.master')
@section('title')
    @lang('translation.Profile')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') LPJ @endslot
        @slot('title') Detail LPJ @endslot
    @endcomponent

    <div class="row mb-2">
        <div class="col-md-6 col-lg-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="clearfix"></div>
                        <div>
                          <i class="fas fa-file-contract fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $find->VEND_NAME }}</h5>
                        <p class="text-muted">{{ $find->DOC_ID }}</p>
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
                            <p class="mb-1">Nominal Belum di LPJ :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($belum_lpj, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Sudah teralokasi :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($total_lpj, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="table-responsive mt-4">
                            <p class="mb-1">Nominal Kelebihan :</p>
                            <h5 class="font-size-16">
                                Rp. {{ number_format($kelebihan, 2, ',', '.') }}
                            </h5>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('approved.list') }}" class="btn btn-secondary">Back</a>
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
                    <!-- <br> -->
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Keterangan</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($res_detail as $item)
                                <tr>
                                    <td>{{ $loop->index + 1}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->DOC_DATE)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $item->REMARKS}}</td>
                                    <td>{{ number_format($item->TOTAL, 2, ',', '.')}}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick='editCashbon({!! json_encode($item) !!})'>
                                            <i class="uil uil-eye"></i>
                                        </button> 
                                    @csrf
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    
                    <!-- Modal ADD/Edit Data -->
                    <div class="modal fade" id="cashbonModal" tabindex="-1" aria-labelledby="cashbonModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="cashbonForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_method" id="formMethod" value="POST">
                                    <input type="hidden" name="id_cashbon" value="{{ request()->route('id_cashbon') ?? $id_cashbon ?? '' }}">
                                    <input type="hidden" name="id" id="id">
                                    <input type="hidden" name="oldImage" id="oldImage">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cashbonModalLabel">Tambah / Edit Detail Cashbon</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="doc_date" class="form-label">Tanggal</label>
                                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="{{ old('DOC_DATE') }}" readonly>
                                            @error('doc_date')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2" readonly>{{ old('REMARKS') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total" class="form-label">Total (Rp)</label>
                                            <input type="text" name="total" id="total" value="{{ old('TOTAL') }}" class="form-control" readonly>
                                            @error('total')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Jenis LPJ</label>
                                            <select class="form-select" id="doc_type" name="doc_type">
                                                <option value="makan">Uang Makan</option>
                                                <option value="transport">Uang Transport</option>
                                                <option value="entertain">Entertain</option>
                                                <option value="lain_lain">Lain-Lain</option>
                                            </select>

                                        </div>

                                        <div class="mb-3">
                                            <label for="image" class="form-label">Lampiran (Foto)</label>
                                            <input type="file" name="image[]" id="image" accept="image/*" class="form-control" multiple>
                                            <small class="text-muted">Ukuran file maksimal: 2MB</small>
                                            <div id="fileSizeError" class="text-danger"></div>
                                            <br><br>
                                            <div class="mb-3" id="previewContainer" style="display: none;">
                                                <label class="form-label">Preview Lampiran Lama:</label>
                                                <div id="oldImagePreview" class="d-flex flex-wrap gap-2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
                          <i class="fas fa-info fa-3x text-primary"></i>
                        </div>
                        <h5 class="mt-3 mb-1">Status Approved</h5>
                    </div>

                    <hr class="my-4">

                    
                    <div class="mt-4">
                        <h6 class="text-primary"><i class="fas fa-history me-1"></i> Riwayat Approval</h6>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle text-secondary fa-lg"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $find->VEND_NAME }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($find->DOC_DATE)->translatedFormat('d F Y') }}</small>
                                        <div class="mt-1">
                                            <em class="text-secondary">{{ $find->REMARKS }}</em>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @foreach ($res_approved as $approval)
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if ($approval->status == 'Approved')
                                            <i class="fas fa-check-circle text-success fa-lg"></i>
                                        @elseif ($approval->status == 'Pending')
                                            <i class="fas fa-hourglass-half text-warning fa-lg"></i>
                                        @elseif ($approval->status == 'Rejected')
                                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($approval->status == 'Pending')
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>Manager Keuangan</strong><br>
                                                <small class="text-muted">Belum disetujui</small><br>
                                                <em class="text-warning">Menunggu persetujuan Anda</em>
                                            </div>                                    
                                            <div>                                
                                                <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalSetuju" id="accBtn">Setujui</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak" >Tolak</button>
                                            </div>
                                        </div>                                            
                                        @elseif ($approval->status == 'Approved')
                                            <strong>{{ $approval->name_approved }} - {{ $approval->role_name }}</strong><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($approval->approved_date)->translatedFormat('d F Y - H:i') }}</small>
                                            <div class="mt-1">
                                                <em class="text-secondary">Telah anda Approved!!</em>
                                            </div>
                                        @elseif ($approval->status == 'Rejected')
                                            <strong>{{ $approval->name_approved }} - {{ $approval->role_name }}</strong><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($approval->approved_date)->translatedFormat('d F Y - H:i') }}</small>
                                            <div class="mt-1">
                                                <em class="text-secondary">Menunggu revisi dari {{ $find->VEND_NAME }}</em>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Lampiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselImages"></div>

                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Setuju -->
    <div class="modal fade" id="modalSetuju" tabindex="-1" aria-labelledby="modalSetujuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="setujuForm" method="POST" action="{{ route('approved.add') }}">
            @csrf
                <input type="hidden" name="id_cashbon" value="{{ $id_cashbon }}">
                <input type="hidden" name="notes" value="LPJ Anda telah disetujui">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSetujuLabel">Konfirmasi Setuju</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda sudah yakin setujui untuk pengajuan LPJ ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn" name="status" value="1">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Tolak -->
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('approved.add') }}">
            @csrf
            <input type="hidden" name="id_cashbon" value="{{ $id_cashbon }}">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalTolakLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan / Alasan</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control" required></textarea>
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="status" value="0">Kirim Penolakan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js"></script>
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('cashbonModal')).show();
    });    
</script>
@endif
<script>
    function openImageModal(images, id_cashbon, startIndex = 0) {
        const carouselInner = document.getElementById('carouselImages');
        carouselInner.innerHTML = '';

        images.forEach((img, index) => {
            const isActive = index === startIndex ? 'active' : '';
            carouselInner.innerHTML += `
                <div class="carousel-item ${isActive}">
                    <div class="zoom-container d-flex justify-content-center align-items-center" style="overflow: hidden; height: 500px;">
                        <img src="/temp/${id_cashbon}/${img}" class="d-block w-100" style="max-height: 100%; object-fit: contain; transition: transform 0.3s;">
                    </div>
                </div>
            `;
        });
        console.log("Opening modal with images:", images);

        const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        imageModal.show();

        setTimeout(() => {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('imageCarousel')) || new bootstrap.Carousel('#imageCarousel');
            carousel.to(startIndex);
            document.querySelectorAll('.zoomable-image').forEach((img) => {
                Panzoom(img, {
                    maxScale: 5,
                    contain: 'outside',
                    startScale: 1,
                });
            });
        }, 300);
    }
    document.addEventListener("DOMContentLoaded", function () {
        const totalInput = document.getElementById("total");

        totalInput.addEventListener("input", function (e) {
            let value = this.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            if (!value) return this.value = '';

            this.value = new Intl.NumberFormat('id-ID').format(value);
        });        
    })
    function validateFileSize(input) {
        const maxSize = 2 * 1024 * 1024; 
        const fileSize = input.files[0].size;

        if (fileSize > maxSize) {
            document.getElementById('fileSizeError').innerHTML = 'Ukuran file melebihi batas (2MB). Pilih file lain.';
            input.value = '';
        } else {
            document.getElementById('fileSizeError').innerHTML = '';
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('addBtn').addEventListener('click', function () {
            document.getElementById('cashbonForm').action = "{{ route('detail.add') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('cashbonModalLabel').innerText = 'Tambah Detail Cashbon';

            document.getElementById('doc_date').value = '';
            document.getElementById('keterangan').value = '';
            document.getElementById('total').value = '';
            document.getElementById('image').value = '';
            document.getElementById('doc_type').selectedIndex = 0;
            document.getElementById('id').value = '';

            new bootstrap.Modal(document.getElementById('cashbonModal')).show();
        });

        document.getElementById('accBtn').addEventListener('click', function () {
            document.getElementById('setujuForm').action = "{{ route('approved.add') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('modalSetujuLabel').innerText = 'Konfirmasi Setuju';
            new bootstrap.Modal(document.getElementById('modalSetuju')).show();
        });

        const totalField = document.getElementById('total');
        totalField.addEventListener('input', function () {
            let angka = this.value.replace(/\D/g, '');
            this.value = formatRupiah(angka);
        });

        const deleteModal = document.getElementById('confirmDeleteModal');
        const deleteBtn = document.getElementById('confirmDeleteBtn');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const id_cashbon = button.getAttribute('data-idcashbon');

            deleteBtn.href = `/detail/delete/${id}?id_cashbon=${id_cashbon}`;
        });
    });

    function formatRupiah(angka) {
        if (!angka) return '';
        angka = angka.toString().replace(/\D/g, '');
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function editCashbon(detail) {
        const form = document.getElementById('cashbonForm');
        form.action = "/detail/update/" + detail.id;
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('cashbonModalLabel').innerText = 'Edit Detail Cashbon';

        document.getElementById('doc_date').value = detail.DOC_DATE;
        document.getElementById('keterangan').value = detail.REMARKS;

        const totalField = document.getElementById('total');
        let cleanTotal = Math.round(parseFloat(detail.TOTAL)).toString();
        totalField.value = formatRupiah(cleanTotal);

        document.getElementById('doc_type').value = detail.DOC_TYPE;
        document.getElementById('id').value = detail.LPJ_ID;
        document.getElementById('oldImage').value = detail.IMAGE;

        const previewContainer = document.getElementById('oldImagePreview');
        previewContainer.innerHTML = '';

        const imageList = detail.IMAGE ? detail.IMAGE.split(',') : [];
        document.getElementById('oldImage').value = imageList.join(',');
        if (imageList.length > 0) {
            document.getElementById('previewContainer').style.display = 'block';
        }

        imageList.forEach((img, index) => {
            const imgElement = document.createElement('img');
            const safeFolderName = detail.DOC_ID.replaceAll('/', '_');
            imgElement.src = `/temp/${safeFolderName}/${img}`;
            imgElement.alt = `lampiran-${index}`;
            imgElement.style.width = '60px';
            imgElement.style.cursor = 'pointer';
            imgElement.onclick = () => openImageModal(imageList, safeFolderName, index);
            previewContainer.appendChild(imgElement);
        });

        new bootstrap.Modal(document.getElementById('cashbonModal')).show();
    }
</script>
@endsection
