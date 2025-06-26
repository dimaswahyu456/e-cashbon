@extends('layouts.master')
@section('title')
    @lang('translation.Profile')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css" />
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Cashbon @endslot
        @slot('title') Detail Cashbon @endslot
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
                            <a href="{{ route('cashbon.list') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 col-lg-8 mb-3">
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
                    <a class="btn btn-success" id="addBtn">
                        <i class="fas fa-plus"></i> Add Data
                    </a><br><br>
                    
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead style="text-align: center;">
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Keterangan</th>
                                <th>Total</th>
                                <th>Notes</th>
                                <th>Status Approved</th>
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
                                    <td class="text-center">
                                        @if ($item->REASON)
                                            <i class="fas fa-sticky-note text-warning fa-lg"
                                                style="cursor: pointer;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reasonModal"
                                                data-reason="{{ addslashes($item->REASON) }}"
                                                data-note="{{ addslashes($item->NOTE_REASON) }}"
                                                data-id="{{ $item->LPJ_ID }}"
                                                data-idcashbon="{{ $item->DOC_ID }}"
                                                >
                                            </i>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($item->STATUS_APPROVED == '1')
                                            <i class="fas fa-check-circle text-success fa-lg"></i>
                                        @elseif ($item->STATUS_APPROVED == '0')
                                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick='editCashbon({!! json_encode($item) !!})'>
                                            <i class="uil uil-pen"></i>
                                        </button>                                        
                                        <a class="btn btn-danger btn-sm"
                                            href="#" data-id="{{ $item->LPJ_ID }}" data-idcashbon="{{ $item->DOC_ID }}" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                            <i class="uil uil-trash-alt"></i>
                                        </a>
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
                                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="{{ old('DOC_DATE') }}" required>
                                            @error('doc_date')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2" required>{{ old('REMARKS') }}</textarea>
                                            @error('keterangan')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total" class="form-label">Total (Rp)</label>
                                            <input type="text" name="total" id="total" value="{{ old('TOTAL') }}" class="form-control" required>
                                            @error('total')
                                                <div class="invalid-feedback">
                                                {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Jenis LPJ</label>
                                            <select name="doc_type" id="doc_type" class="form-select" aria-label="Floating label select">
                                                @foreach ($type as $item)
                                                <option value="{{$item->LPJ_ID}}">{{$item->LPJ_NAME}}</option>
                                                @endforeach
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
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 mb-3">
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
                            <li class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">     
                                        @if ($allApproved)
                                            <i class="fas fa-check-circle text-success fa-lg"></i>                                                    
                                        @elseif ($hasPending)
                                            <i class="fas fa-times-circle text-danger fa-lg"></i> 
                                        @else
                                            <i class="fas fa-times-circle text-warning fa-lg"></i>
                                        @endif
                                    </div>                                        
                                    <div>                                     
                                        @if ($allApproved)
                                            <strong>Manager Keuangan</strong><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($latestApprovedDate)->translatedFormat('d F Y') }}</small>
                                            <div class="mt-1">
                                                <em class="text-secondary">Pengajuan anda telah terapproved.</em>
                                            </div>
                                        @elseif ($hasPending)
                                            <strong>Manager Keuangan</strong><br>
                                            <div class="mt-1">
                                                <em class="text-secondary">Pengajuan anda sedang di tinjau atau ada yang harus direvisi dari pengajuan anda, mohon cek apabila ada note di data pengajuan anda.
                                                Mohon segera direvisi.
                                                </em>
                                            </div>
                                        @else
                                            <strong>Anda belum melakukan pengajuan, lakukan pengajuan LPJ dulu.</strong>
                                        @endif
                                    </div>
                                </div>
                            </li>
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

    <!-- Modal Konfirmasi Delete -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus detail cashbon ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Reason -->
    <form method="POST" action="{{ route('cashbon.send_response') }}">
        @csrf
        <input type="hidden" name="lpj_id" id="modalLpjId">
        <input type="hidden" name="id_cashbon" id="modalCashbonId">

        <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reasonModalLabel">Alasan / Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p id="reasonText" class="mb-2 text-dark"></p>

                    <div id="prevNoteReasonContainer" style="display: none;">
                        <label class="form-label">Balasan Sebelumnya:</label>
                        <div class="border rounded p-2 mb-3 bg-light text-dark" id="prevNoteReason"></div>
                    </div>

                    <label for="responseMessage" class="form-label">Balasan / Tanggapan:</label>
                    <textarea id="responseMessage" class="form-control" rows="3" placeholder="Tulis pesan anda..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
                </div>
            </div>
        </div>
    </form>
                
    <!-- end row -->
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js"></script>
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
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
                <a href="https://divisitic.saraswanti.info/upload/${id_cashbon}/${img}" data-caption="Lampiran ${index + 1}" class="zoomable-link">
                    <img src="https://divisitic.saraswanti.info/upload/${id_cashbon}/${img}" class="d-block w-100" style="max-height: 500px; object-fit: contain; cursor: zoom-in;">
                </a>
            </div>
        `;
        });
        console.log("Opening modal with images:", images);

        const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        imageModal.show();

        setTimeout(() => {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('imageCarousel')) || new bootstrap.Carousel('#imageCarousel');
            carousel.to(startIndex);

            baguetteBox.run('#carouselImages .carousel-item.active', {
                animation: 'fadeIn',
                overlayBackgroundColor: 'rgba(0,0,0,0.9)'
            });
        }, 300);
    }
    document.getElementById('imageCarousel').addEventListener('slid.bs.carousel', function () {
        if (typeof baguetteBox !== 'undefined') {
            baguetteBox.destroy();
        }

        baguetteBox.run('#carouselImages .carousel-item.active', {
            animation: 'fadeIn',
            overlayBackgroundColor: 'rgba(0,0,0,0.9)'
        });
    });
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
    const reasonModal = document.getElementById('reasonModal');
    reasonModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        const note = trigger.getAttribute('data-note') || '';

        document.getElementById('reasonText').innerText = trigger.getAttribute('data-reason') || '-';
        document.getElementById('modalLpjId').value = trigger.getAttribute('data-id');
        document.getElementById('modalCashbonId').value = trigger.getAttribute('data-idcashbon');
        document.getElementById('responseMessage').value = '';
        const prevNoteContainer = document.getElementById('prevNoteReasonContainer');
        const prevNoteText = document.getElementById('prevNoteReason');

        if (note && note.trim() !== '') {
            prevNoteText.innerText = note;
            prevNoteContainer.style.display = 'block';
        } else {
            prevNoteText.innerText = '';
            prevNoteContainer.style.display = 'none';
        }
    });
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
            const wrapper = document.createElement('div');
            wrapper.style.position = 'relative';
            wrapper.style.display = 'inline-block';

            const imgElement = document.createElement('img');
            const safeFolderName = detail.DOC_ID.replaceAll('/', '_');
            imgElement.src = `https://divisitic.saraswanti.info/upload/${safeFolderName}/${img}`;
            imgElement.alt = `lampiran-${index}`;
            imgElement.style.width = '60px';
            imgElement.style.marginRight = '5px';
            imgElement.style.cursor = 'pointer';
            imgElement.onclick = () => openImageModal(imageList, safeFolderName, index);

            const deleteBtn = document.createElement('span');
            deleteBtn.innerHTML = '&times;';
            deleteBtn.style.position = 'absolute';
            deleteBtn.style.top = '-8px';
            deleteBtn.style.right = '0';
            deleteBtn.style.background = 'red';
            deleteBtn.style.color = 'white';
            deleteBtn.style.borderRadius = '50%';
            deleteBtn.style.cursor = 'pointer';
            deleteBtn.style.padding = '2px 6px';
            deleteBtn.onclick = function(e) {
                e.stopPropagation();
                if (confirm('Yakin hapus lampiran ini?')) {
                    deleteImageFromCashbon(detail.LPJ_ID, safeFolderName, img, wrapper);
                }
            };

            wrapper.appendChild(imgElement);
            wrapper.appendChild(deleteBtn);
            previewContainer.appendChild(wrapper);
        });

        new bootstrap.Modal(document.getElementById('cashbonModal')).show();
    }
    function deleteImageFromCashbon(lpj_id, folder, image, wrapper) {
        fetch('/detail/delete-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                lpj_id: lpj_id,
                folder: folder,
                image: image
            })
        }).then(res => res.json())
        .then(res => {
            if (res.success) {
                wrapper.remove();
                const oldImageInput = document.getElementById('oldImage');
                let images = oldImageInput.value.split(',').filter(i => i.trim() !== '');
                images = images.filter(img => img !== image); 
                oldImageInput.value = images.join(',');
            } else {
                alert('Gagal hapus gambar!');
            }
        });
    }
</script>
@endsection