@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        @can('isAdmin')
            <h1 class="h2">Welcome Back Admin, {{ $data -> nama_admin }}</h1>
        @endcan

        @can('isMahasiswa')
            <h1 class="h2">Welcome Back Mahasiswa, {{ $data -> nama_mahasiswa }}</h1>
        @endcan

        @can('isDosen')
            <h1 class="h2">Welcome Back Dosen, {{ $data -> nama_dosen }}</h1>
        @endcan

    </div>

    @can('isAdmin')
        <h1>Ini Main Dashboard Admin</h1>
    @endcan

    @can('isMahasiswa')
        <h1>Ini Main Dashboard Mahasiswa</h1>
    @endcan

    @can('isDosen')
        <h1>Ini Main Dashboard Dosen</h1>
    @endcan
@endsection
