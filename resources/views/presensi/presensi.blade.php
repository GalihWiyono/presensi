@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Presensi</h1>
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
            <div class="col-lg-6 col-sm-12 mx-auto" id="reader">
            </div>
        </div>
    </div>

    {{-- Modal Presensi --}}
    <div class="modal fade" id="presensiModal" tabindex="-1" aria-labelledby="presensiModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/dashboard/presensi/store" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="presensiModal">Presensi Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="id" id="id" type="hidden" value=""
                                readonly />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="mataKuliah" id="mataKuliah" type="text"
                                value=""readonly />
                            <label for="mataKuliah">Mata Kuliah</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="dosen" id="dosen" type="text"
                                value=""readonly />
                            <label for="mataKuliah">Dosen Pengajar</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="kelas" id="kelas" type="text" value=""
                                readonly />
                            <label for="kelas">Kelas</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="hari" id="hari" type="text" value=""
                                readonly />
                            <label for="hari">Hari</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="jam" id="jam" type="text" value=""
                                readonly />
                            <label for="jam">Jam Kelas</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="jam_presensi" id="jam_presensi" type="text" value=""
                                readonly />
                            <label for="jam">Jam Presensi</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Presensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('../script/qrcode-script')
@endsection
