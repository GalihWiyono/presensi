<div id='map' width='1px' ></div>@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        @can('isAdmin')
            <h1 class="h2">Welcome Back Admin, {{ $data->nama_admin }}</h1>
        @endcan

        @can('isMahasiswa')
            <h1 class="h2">Welcome Back Mahasiswa, {{ $data->nama_mahasiswa }}</h1>
        @endcan

        @can('isDosen')
            <h1 class="h2">Welcome Back Dosen, {{ $data->nama_dosen }}</h1>
        @endcan

    </div>

    @can('isAdmin')
        <h1>Ini Main Dashboard Admin</h1>
    @endcan

    @can('isMahasiswa')
        <h1>Ini Main Dashboard Mahasiswa</h1>
        <div id='map'></div>
    @endcan

    @can('isDosen')
        <h1>Ini Main Dashboard Dosen</h1>
        <div id='map'></div>
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
                    <h6 class="text-justify">Lokasi anda tidak ditemukan, silakan aktifkan izin untuk mendapatkan lokasi
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
