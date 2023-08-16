<div id='map'></div>@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        @if (session()->has('message'))
            <div class="position-fixed mt-5 top-0 end-0 p-3" style="z-index: 11">
                <div id="toastNotification" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div id="toast-header"
                        class="toast-header @if (session('status') == true) text-success @else text-danger @endif">
                        <i class="fa-solid fa-square fa-xl"></i>
                        <strong class="ms-2 me-auto">{{ session('status') == true ? 'Success' : 'Failed' }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        {{ session('message') }}
                    </div>
                </div>
            </div>
        @endif

        @can('isAdmin')
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">{{ __("Welcome Back") }}, {{ $data->nama_admin }}!</h4>
                        <p class="text-muted mb-0">{{ __("Here's what's happening today") }}.</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control dash-filter-picker border-dark "
                                        value="{{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
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
                        <h4 class="fs-16 mb-1">{{ __("Welcome Back") }}, {{ $data->nama_mahasiswa }}!</h4>
                        <p class="text-muted mb-0">{{ __("Here's what's happening today") }}.</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-auto">
                                <div class="input-group">
                                    <input type="text" class="form-control dash-filter-picker border-dark "
                                        value="{{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
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
                        <h4 class="fs-16 mb-1">{{ __("Welcome Back") }}, {{ $data->nama_dosen }}!</h4>
                        <p class="text-muted mb-0">{{ __("Here's what's happening today") }}.</p>
                    </div>
                    <div class="mt-3 mt-lg-0">
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-outline-dark" id="statusBtn">{{ __("Online") }}</button>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control dash-filter-picker border-dark "
                                        value="{{ \Carbon\Carbon::now()->translatedFormat('l') . ', ' . \Carbon\Carbon::now()->translatedFormat('d F Y') }}"
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

        {{-- Modal Lokasi Denied --}}
        <div class="modal fade" id="lokasiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="lokasiModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Location Not Found") }}</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">{{ __("Your location was not found. Will the class be conducted online?") }}
                        </h6>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-danger col-2" href="">{{ __("No") }}</a>
                        <button class="btn btn-primary col-2" id="btnOnline">{{ __("Yes") }}</button>
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
                        <h5 class="modal-title">{{ __("Location Outside The Campus Area") }}</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">{{ __("Your location is outside the designated campus location. Please go to the specified location in order to access the features available on this website") }}!</h6>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary col-2" id="btnOnline">{{ __("Online") }}</button>
                        <a class="btn btn-secondary col-2 px-2" href="">{{ __("Refresh") }}</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Change Status To Online --}}
        <div class="modal fade" id="statusKelasOnlineModal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="statusKelasOnlineModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Change Status to Online") }}?</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">{{ __("The class status will be changed to online and will not require location permission. Do you agree") }}?</h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-primary col-2" id="btnChangeToOnline">{{ _("Yes") }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Change Status To Offline --}}
        <div class="modal fade" id="statusKelasOfflineModal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="statusKelasOfflineModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ _("Change Status to Offline") }}?</h5>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-justify">{{ __("The class status will be changed to offline and will require location permission. Do you agree") }}?</h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-primary col-2" id="btnChangeToOffline">{{ __("Yes") }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('footer-scripts')
    @include('../script/leaflet-script')
@endsection
