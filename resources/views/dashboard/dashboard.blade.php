<div id='map'></div>@extends('layout/main')

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
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-dark" id="statusBtn">Offline</button>
                            </div>
                            <div class="col-sm-9">
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

                        <h6>Silakan aktifkan kembali lokasi dengan mengikuti <a href="#" id="lokasiLink">tutorial ini.</a></h6>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary col-2" href="">Refresh</a>
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
                    <div class="modal-footer">
                        <a class="btn btn-secondary col-2" href="">Refresh</a>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('isDosen')
        @include('dashboard/dashboard-dosen')

        {{-- Modal Lokasi Denied --}}
        <div class="modal fade" id="lokasiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="lokasiModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Location Not Found</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">Lokasi anda tidak ditemukan, apakah kelas akan dilaksanakan secara online?</h6>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-danger col-2" href="">Tidak</a>
                        <button class="btn btn-primary col-2" id="btnOnline">Ya</button>
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

                    <div class="modal-footer">
                        <button class="btn btn-primary col-2" id="btnOnline">Online</button>
                        <a class="btn btn-secondary col-2" href="">Refresh</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Change Status To Online --}}
        <div class="modal fade" id="statusKelasOnlineModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="statusKelasOnlineModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Status to Online?</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">Status kelas akan diubah menjadi online dan tidak memerlukan izin lokasi, apakah
                            anda setuju?</h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary col-2" id="btnChangeToOnline">Ya</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Change Status To Offline --}}
        <div class="modal fade" id="statusKelasOfflineModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="statusKelasOfflineModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Status to Offline?</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">Status kelas akan diubah menjadi offline dan memerlukan izin lokasi , apakah
                            anda setuju?</h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary col-2" id="btnChangeToOffline">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('footer-scripts')
    @include('../script/leaflet-script')
@endsection
