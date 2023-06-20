@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kelas </h1>
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
                        <label for="mataKuliah">Mata Kuliah</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="kelas" id="kelas" type="text" placeholder="Kelas"
                            data-sb-validations="" value="{{ $detail->kelas->nama_kelas }}" readonly />
                        <label for="kelas">Kelas</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="hari" id="hari" type="text" placeholder="Hari"
                            data-sb-validations="" value="{{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('l') }}"
                            readonly />
                        <label for="hari">Hari</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" name="jam" id="jam" type="text" placeholder="Jam"
                            data-sb-validations="" value="{{ $detail->jam_mulai . ' - ' . $detail->jam_berakhir }}"
                            readonly />
                        <label for="jam">Jam</label>
                    </div>
                    <form action="" method="POST">
                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                                    placeholder="Jam Mulai Absen" data-sb-validations="" value="{{ $detail->mulai_absen }}"
                                    readonly />
                                <label for="jam">Jam Mulai Presensi</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="Jam Akhir Absen" data-sb-validations="" value="{{ $detail->akhir_absen }}"
                                    readonly />
                                <label for="jam">Jam Akhir Presensi</label>
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
                        <button id="btnForPresensi" href="" class="btn btn-primary py-3 w-100" data-bs-toggle="modal"
                            data-bs-target="#qrModal" {!! $detail->mulai_absen == null || $detail->akhir_absen == null || $activeSesi->status != 'Belum'
                                ? 'disabled'
                                : '' !!}>Generate QRCode</button>
                    </div>
                    {{-- </form> --}}
                    <div class="">
                        <form action="/dashboard/kelas/tutup" method="POST">
                            @csrf
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="jadwal_id" id="jadwal_id" type="hidden"
                                    placeholder="Jadwal" value="{{ $detail->id }}" readonly />
                            </div>
                            <div class="form-floating mb-3 col-lg-5">
                                <input class="form-control" name="sesi_id" id="sesi_id" type="hidden"
                                    placeholder="Sesi" value="{{ $activeSesi->id }}" readonly />
                            </div>
                            <button class="btn btn-warning py-3 w-100" {!! $activeSesi->status == 'Belum' ? '' : 'disabled' !!}>Tutup Pekan</button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Daftar Hadir --}}
            <div class="col-lg-8 col-sm-12">
                <div class="body-white border rounded shadow py-4 px-3">
                    <div class="row">
                        <div class="col-lg-6 col-9">
                            <h3>Daftar Hadir</h3>
                        </div>
                        <div class="col-lg-1 col-3 mb-3">
                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPresensiModal"><span
                                    class="feather-24" data-feather="plus-circle"></a>
                        </div>
                        <div class="col-lg-5 col-12">
                            <form action="/dashboard/kelas/{{ $detail->id }}" method="GET">
                                <select class="form-select" id="searchPekan" name="searchPekan">
                                    @foreach ($sesi as $item)
                                        <option value="{{ $item->sesi }}" {!! $item->sesi == $sesiNow ? 'selected' : '' !!}>Pekan
                                            {{ $item->sesi }}
                                            -
                                            {{ $item->tanggal }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="div table-responsive">
                        <table class="table table-striped text-center align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nim</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Waktu Presensi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
                        {{ $absen->links() }}
                    </div>
                </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Presensi --}}
    <div class="modal fade" id="presensiModal" tabindex="-1" aria-labelledby="presensiModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
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
                                        <td name="nim">{{ $item->nim }}</td>
                                        <td>{{ $item->nama_mahasiswa }}</td>
                                        <td>
                                            <div class="btn-group" role="group" id="{{ $loop->index }}">
                                                <input type="radio" name="status{{ $loop->index }}"
                                                    id="btnradio4_{{ $loop->index }}" value="Tidak Hadir" checked>
                                                <label class="btn" for="btnradio4_{{ $loop->index }}">Tidak
                                                    Hadir</label>

                                                <input type="radio" name="status{{ $loop->index }}"
                                                    id="btnradio1_{{ $loop->index }}" value="Hadir">
                                                <label class="btn" for="btnradio1_{{ $loop->index }}">Hadir</label>

                                                <input type="radio" name="status{{ $loop->index }}"
                                                    id="btnradio2_{{ $loop->index }}" value="Izin">
                                                <label class="btn" for="btnradio2_{{ $loop->index }}">Izin</label>

                                                <input type="radio" name="status{{ $loop->index }}"
                                                    id="btnradio3_{{ $loop->index }}" value="Terlambat">
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
            </div>
        </div>
    </div>

    {{-- Modal Edit Waktu --}}
    <div class="modal fade" id="editWaktuModal" tabindex="-1" aria-labelledby="editWaktuModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/kelas/{{ $detail->id }}/update_jam" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModal">Edit Waktu Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                                    placeholder="Jam Mulai Absen" data-sb-validations=""
                                    value="{{ \Carbon\Carbon::parse($detail->mulai_absen)->format('H:i') }}" />
                                <label for="jam">Mulai Absen</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="Jam Akhir Absen" data-sb-validations=""
                                    value="{{ \Carbon\Carbon::parse($detail->akhir_absen)->format('H:i') }}" />
                                <label for="jam">Akhir Absen</label>
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
        aria-hidden="true">
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
                                placeholder="Sesi" />
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
                    <input class="form-control" id="sesi_id_search" value="{{ $activeSesi->id }}" type="hidden" />
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
                        <input class="form-control" name="sesi_id" id="sesi_id" value="{{ $activeSesi->id }}"
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
