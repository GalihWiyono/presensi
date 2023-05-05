@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kelas</h1>
    </div>

    @if (session()->has('message'))
        <div class="alert @if (session('status') == true) alert-success @else alert-danger @endif  alert-dismissible fade show mt-3 mx-3"
            id="notification" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-12">
                <div class="form-floating mb-3">
                    <input class="form-control" id="id" type="text" placeholder="ID" data-sb-validations=""
                        value="{{ $detail->id }}" disabled />
                    <label for="id">ID</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="mataKuliah" type="text" placeholder="Mata Kuliah"
                        data-sb-validations="" value="{{ $detail->matkul->nama_matkul }}" disabled />
                    <label for="mataKuliah">Mata Kuliah</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="kelas" type="text" placeholder="Kelas" data-sb-validations=""
                        value="{{ $detail->kelas->nama_kelas }}" disabled />
                    <label for="kelas">Kelas</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="hari" type="text" placeholder="Hari" data-sb-validations=""
                        value="{{ $detail->hari }}" disabled />
                    <label for="hari">Hari</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="jam" type="text" placeholder="Jam" data-sb-validations=""
                        value="{{ $detail->waktu_mulai }}"disabled />
                    <label for="jam">Jam</label>
                </div>
                <div class="row gx-1">
                    <div class="form-floating mb-3 col-lg-5">
                        <input class="form-control" id="jam" type="time" placeholder="Jam Absen"
                            data-sb-validations="" value="" />
                        <label for="jam">Mulai Absen</label>
                    </div>
                    <div class="form-floating mb-3 col-lg-5">
                        <input class="form-control" id="jam" type="time" placeholder="Jam Absen"
                            data-sb-validations="" value="" />
                        <label for="jam">Akhir Absen</label>
                    </div>
                    <div class="mb-3 col-lg-2 ">
                        <a class="btn btn-primary w-100 py-3 fs-6">Submit</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="row gx-1">
                    <form action="/dashboard/kelas/{{ $detail->id }}/presence" method="POST">
                        @csrf
                        <input class="form-control" id="jadwal_id" type="hidden" name='jadwal_id'
                            value="{{ $detail->id }}" />
                        <div class="form-floating mb-3 col-lg-10">
                            <input class="form-control" id="nim" type="input" placeholder="Jam Absen" name='nim'
                                value="" />
                            <label for="nim">NIM</label>
                        </div>
                        <div class="mb-3 col-lg-2 ">
                            <button class="btn btn-primary w-100 py-3 fs-6" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
