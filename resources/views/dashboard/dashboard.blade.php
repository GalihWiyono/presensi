<div id='map' width='1px'></div>@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        @can('isAdmin')
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Welcome Back, {{ $data->nama_admin }}!</h4>
                        <p class="text-muted mb-0">Here's what's happening today.</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control dash-filter-picker border-dark "
                                        value="{{ \Carbon\Carbon::now()->format('l') . ', ' . \Carbon\Carbon::now()->format('d F Y') }}"
                                        readonly>
                                    <div class="input-group-text bg-dark border-dark text-white">
                                        <span data-feather="calendar"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('isMahasiswa')
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Welcome Back, {{ $data->nama_mahasiswa }}!</h4>
                    <p class="text-muted mb-0">Here's what's happening today.</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="text" class="form-control dash-filter-picker border-dark "
                                    value="{{ \Carbon\Carbon::now()->format('l') . ', ' . \Carbon\Carbon::now()->format('d F Y') }}"
                                    readonly>
                                <div class="input-group-text bg-dark border-dark text-white">
                                    <span data-feather="calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('isDosen')
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Welcome Back, {{ $data->nama_dosen }}!</h4>
                    <p class="text-muted mb-0">Here's what's happening today.</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <div class="row g-3 mb-0 align-items-center">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="text" class="form-control dash-filter-picker border-dark "
                                    value="{{ \Carbon\Carbon::now()->format('l') . ', ' . \Carbon\Carbon::now()->format('d F Y') }}"
                                    readonly>
                                <div class="input-group-text bg-dark border-dark text-white">
                                    <span data-feather="calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

    </div>

    @can('isAdmin')
        @include('dashboard/dashboard-admin')
    @endcan

    @can('isMahasiswa')
        @include('dashboard/dashboard-mahasiswa')
    @endcan

    @can('isDosen')
        @include('dashboard/dashboard-dosen')
    @endcan

    {{-- Modal Lokasi Denied --}}
    <div class="modal fade" id="lokasiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="lokasiModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Location Not Found</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-justify">Lokasi anda tidak ditemukan, silakan aktifkan izin untuk mendapatkan
                        lokasi
                        agar dapat menggunakan
                        fitur yang ada pada website ini!</h6>

                    <h6>Silakan aktifkan kembali lokasi dengan mengikuti tutorial ini</h6>
                    <a href="#" id="lokasiLink" class="btn btn-info">Click</a>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal Lokasi Outside --}}
    <div class="modal fade" id="lokasiOutsideModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="lokasiOutsideModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Location Outside The Campus Area</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-justify">Lokasi anda berada diluar lokasi kampus yang sudah ditentukan,
                        silakan pergi ke lokasi agar dapat menggunakan fitur yang ada pada website ini!</h6>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/leaflet-script')
@endsection
