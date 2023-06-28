@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-lg-9">
            <h1 class="h2">Class Pending Detail </h1>
        </div>
        <div class="input-group">
            <input type="text" class="form-control dash-filter-picker border-dark "
                value="Pending Week {{ $pendingData->sesi->sesi }} - {{ $pendingData->tanggal }}" readonly>
            <div class="input-group-text bg-dark border-dark text-white">
                <span data-feather="calendar"></span>
            </div>
        </div>
    </div>


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

    {{-- Detail Kelas --}}

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <div class="body-white border rounded shadow py-4 px-3">
                    {{-- <form action="/dashboard/kelas/{{ $detail->id }}/generate" method="POST"> --}}
                    @csrf
                    <div class="form-floating">
                        <input class="form-control" name="id" id="id" type="hidden" placeholder="ID"
                            data-sb-validations="" value="{{ $detail->id }}" readonly />
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="mataKuliah" id="mataKuliah" type="text"
                            placeholder="Mata Kuliah" data-sb-validations="" value="{{ $detail->matkul->nama_matkul }}"
                            readonly />
                        <label for="mataKuliah">Course</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="kelas" id="kelas" type="text" placeholder="Kelas"
                            data-sb-validations="" value="{{ $detail->kelas->nama_kelas }}" readonly />
                        <label for="kelas">Class</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="hari" id="hari" type="date" placeholder="Hari"
                            data-sb-validations="" value="{{ $pendingData->tanggal }}" readonly />
                        <label for="hari">Old Date</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="jam" id="jam" type="date" placeholder="Jam"
                            data-sb-validations="" value="{{ $pendingData->tanggal_baru }}" readonly />
                        <label for="jam">New Date</label>
                    </div>
                    <form action="" method="POST">
                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                                    placeholder="Jam Mulai Absen" data-sb-validations=""
                                    value="{{ $pendingData->mulai_absen_baru }}" readonly />
                                <label for="jam">Presence Start Time</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="Jam Akhir Absen" data-sb-validations=""
                                    value="{{ $pendingData->akhir_absen_baru }}" readonly />
                                <label for="jam">Presence End Time</label>
                            </div>
                            <div class="mb-3 col-lg-2 ">
                                <a class="btn btn-secondary py-3 w-100" data-bs-toggle="modal"
                                    data-bs-target="#editWaktuModal">
                                    <span data-feather="edit"></span>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="mb-3">
                        <button id="btnForPresensi" href="" class="btn btn-primary py-3 w-100"
                            data-bs-toggle="modal" data-bs-target="#qrModal" {!! $status == 'Inactive' ? 'disabled' : '' !!}>Generate
                            QRCode</button>
                    </div>
                    {{-- </form> --}}
                    <div class="">
                        <button class="btn btn-warning py-3 w-100" data-bs-toggle="modal"
                            data-bs-target="#closeWeekModal" {!! $status == 'Inactive' ? 'disabled' : '' !!}>Close Week</button>
                    </div>
                </div>
            </div>
            {{-- Daftar Hadir --}}
            <div class="col-lg-8 col-sm-12">
                <div class="body-white border rounded shadow py-4 px-3">
                    <div class="row">
                        <div class="col-lg-10 col-9">
                            <h3>Attendance List</h3>
                        </div>
                        <div class="col-lg-2 col-3">
                            <button class="btn btn-primary px-4" data-bs-toggle="modal"
                                data-bs-target="#addPresensiModal" {!! $status == 'Inactive' ? 'disabled' : '' !!}>
                                <span class="feather-24" data-feather="plus-circle">
                            </button>
                        </div>
                    </div>

                    <div class="div table-responsive">
                        <table class="table table-striped text-center align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIM</th>
                                    <th>Student Name</th>
                                    <th>Presence Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absen as $item)
                                    <tr>
                                        <th>{{ ($absen->currentpage() - 1) * $absen->perpage() + $loop->index + 1 }}</th>
                                        <td>{{ $item->nim }}</td>
                                        <td>{{ $item->mahasiswa->nama_mahasiswa }}</td>
                                        <td>
                                            @if ($item->status == 'Hadir' || $item->status == 'Terlambat')
                                                {{ $item->waktu_presensi }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->status }}</td>
                                        <td><button type="button" class="btn btn-warning btn-sm" id="editPresensi"
                                                data-bs-toggle="modal" data-bs-target="#editPresensiModal"
                                                data-nim="{{ $item->nim }}"
                                                data-nama="{{ $item->mahasiswa->nama_mahasiswa }}"
                                                data-sesi="{{ $item->sesi_id }}" data-status="{{ $item->status }}"
                                                data-waktu={{ $item->waktu_presensi }}><span data-feather="edit">
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{-- {{ $absen->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tutup / Pending Pekan --}}
    <div class="modal fade" id="closeWeekModal" tabindex="-1" aria-labelledby="closeWeekModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/kelas/pending/tutup" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="closeWeekModal">Close / Pending Week</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Week Information:
                        <div class="form-floating mb-3">
                            <input class="form-control" name="jadwal_id" id="jadwal_id" type="hidden"
                                placeholder="Jadwal" value="{{ $detail->id }}" readonly />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="sesi_id" id="sesi_id" type="hidden" placeholder="Sesi"
                                value="{{ $pendingData->sesi_id }}" readonly />
                        </div>
                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-6 col-sm-12">
                                <input class="form-control" id="Week" type="text" placeholder="Week"
                                    value="{{ $pendingData->sesi->sesi }}" readonly />
                                <label for="Week">Week</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6 col-sm-12">
                                <input class="form-control" id="Date" type="text" placeholder="Date"
                                    value="{{ $pendingData->tanggal_baru }}" readonly />
                                <label for="Date">Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Close Week</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal QRCode --}}
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModal">QRCode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="visible-print text-center">
                        {!! $qrcode !!}
                        <p class="mt-3">Silakan scan QRCode diatas untuk melakukan presensi</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Presensi --}}
    <div class="modal fade" id="presensiModal" tabindex="-1" aria-labelledby="presensiModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <form action="/dashboard/kelas/{{ $detail->id }}/presensiOnline" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="presensiModal">Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="div table-responsive">
                            <table class="table table-striped text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nim</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anggotaKelas as $item)
                                        <tr>
                                            <th>{{ $loop->index + 1 }}</th>
                                            <td>{{ $item->nim }}</td>
                                            <input type="hidden" value="{{ $item->nim }}" name="nim[]">
                                            <input type="hidden" value="{{ $activeSesi->id }}" name="sesi_id" />
                                            <td>{{ $item->nama_mahasiswa }}</td>
                                            <td>
                                                <div class="btn-group" role="group" id="{{ $loop->index }}">

                                                    <input type="radio" name="status[{{ $loop->index }}]"
                                                        id="btnradio4_{{ $loop->index }}" value="Tidak Hadir"
                                                        {!! $item->status == 'Tidak Hadir' ? 'checked' : '' !!}>
                                                    <label class="btn" for="btnradio4_{{ $loop->index }}">Tidak
                                                        Hadir</label>

                                                    <input type="radio" name="status[{{ $loop->index }}]"
                                                        id="btnradio1_{{ $loop->index }}" value="Hadir"
                                                        {!! $item->status == 'Hadir' ? 'checked' : '' !!}>
                                                    <label class="btn"
                                                        for="btnradio1_{{ $loop->index }}">Hadir</label>

                                                    <input type="radio" name="status[{{ $loop->index }}]"
                                                        id="btnradio2_{{ $loop->index }}" value="Izin"
                                                        {!! $item->status == 'Izin' ? 'checked' : '' !!}>
                                                    <label class="btn"
                                                        for="btnradio2_{{ $loop->index }}">Izin</label>

                                                    <input type="radio" name="status[{{ $loop->index }}]"
                                                        id="btnradio3_{{ $loop->index }}" value="Terlambat"
                                                        {!! $item->status == 'Terlambat' ? 'checked' : '' !!}>
                                                    <label class="btn"
                                                        for="btnradio3_{{ $loop->index }}">Terlambat</label>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal edit waktu --}}
    <div class="modal fade" id="editWaktuModal" tabindex="-1" aria-labelledby="editWaktuModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/kelas/pending" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModal">Register Pending Class Date</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="id" id="id" value="{{ $pendingData->id }}"
                            type="hidden" />
                        <div class="form-floating mb-3">
                            <input class="form-control" name="tanggal_baru" id="new_date" type="date"
                                placeholder="New Date" min="{{ date('Y-m-d') }}"
                                value="{{ $pendingData->tanggal_baru }}" required />
                            <label for="new_date">New Date</label>
                        </div>
                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                                    placeholder="Start Presence Time" value="{{ $pendingData->mulai_absen_baru }}"
                                    required />
                                <label for="mulai_absen">Start Presence Time</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="End Presence Time" value="{{ $pendingData->akhir_absen_baru }}"
                                    required />
                                <label for="akhir_absen">End Presence Time</label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Presensi --}}
    <div class="modal fade" id="editPresensiModal" tabindex="-1" aria-labelledby="editPresensiModal"
        data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/kelas/{{ $detail->id }}/edit_presensi" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModal">Edit Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="sesi_id" id="sesi_edit" type="hidden"
                                value="{{ $pendingData->sesi_id }}" placeholder="Sesi" />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nim" id="nim_edit" type="text" placeholder="NIM"
                                readonly />
                            <label for="nim">NIM</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nama_mahasiswa" id="nama_mahasiswa_edit" type="text"
                                placeholder="Nama Mahasiswa" readonly />
                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" name="status" id="status_edit">
                                <option value="Hadir">Hadir</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Izin">Izin</option>
                                <option value="Tidak Hadir">Tidak Hadir</option>
                            </select>
                            <label for="nim">Status</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="waktu_presensi_hidden" type="hidden"
                                placeholder="Waktu Presensi" />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="waktu_presensi" id="waktu_presensi_edit" type="time"
                                placeholder="Waktu Presensi" required />
                            <label for="waktu_presensi">Waktu Presensi</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Presensi --}}
    <div class="modal fade" id="addPresensiModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="addPresensiModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModal">Tambah Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input class="form-control" id="kelas_id_search" value="{{ $detail->kelas_id }}" type="hidden" />
                    <input class="form-control" id="sesi_id_search" value="{{ $pendingData->sesi_id }}"
                        type="hidden" />
                    <form action="">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="nim_search" type="text" placeholder="NIM"
                                autocomplete="off" />
                            <label for="nim">NIM</label>
                            <div class="invalid-feedback">
                                <p id="messageError">Error Message</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="searchNim">Search</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showDataMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="showDataMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/kelas/{{ $detail->id }}/presensi" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModal">Tambah Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="sesi_id" id="sesi_id" value="{{ $pendingData->sesi_id }}"
                            type="hidden" />
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nim" id="nim_show" type="text" placeholder="NIM"
                                readonly />
                            <label for="nim">NIM</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nama_mahasiswa" id="nama_show" type="text"
                                placeholder="Nama Mahasiswa" readonly />
                            <label for="nim">Nama Mahasiswa</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="kelas" id="kelas_show" type="text" placeholder="NIM"
                                readonly />
                            <label for="nim">Kelas</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" name="status" id="status_show">
                                <option value="Hadir">Hadir</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Izin">Izin</option>
                                <option value="Tidak Hadir">Tidak Hadir</option>
                            </select>
                            <label for="nim">Status</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="waktu_presensi" id="waktu_presensi_show" type="time"
                                placeholder="Waktu Presensi" required />
                            <label for="waktu_presensi">Waktu Presensi</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/detail-kelas-script')
@endsection
