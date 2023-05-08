@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kelas </h1>
    </div>

    @if (session()->has('message'))
        <div class="alert @if (session('status') == true) alert-success @else alert-danger @endif  alert-dismissible fade show mt-3 mx-3"
            id="notification" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Detail Kelas --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                {{-- <form action="/dashboard/kelas/{{ $detail->id }}/generate" method="POST"> --}}
                @csrf
                <div class="form-floating mb-3">
                    <input class="form-control" name="id" id="id" type="hidden" placeholder="ID"
                        data-sb-validations="" value="{{ $detail->id }}" readonly />
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="mataKuliah" id="mataKuliah" type="text" placeholder="Mata Kuliah"
                        data-sb-validations="" value="{{ $detail->matkul->nama_matkul }}" readonly />
                    <label for="mataKuliah">Mata Kuliah</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="kelas" id="kelas" type="text" placeholder="Kelas"
                        data-sb-validations="" value="{{ $detail->kelas->nama_kelas }}" readonly />
                    <label for="kelas">Kelas</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="hari" id="hari" type="text" placeholder="Hari"
                        data-sb-validations="" value="{{ $detail->hari }}" readonly />
                    <label for="hari">Hari</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="jam" id="jam" type="text" placeholder="Jam"
                        data-sb-validations="" value="{{ $detail->jam_mulai . ' - ' . $detail->jam_berakhir }}" readonly />
                    <label for="jam">Jam</label>
                </div>
                <form action="/dashboard/kelas/{{ $detail->id }}/update_jam" method="POST">
                    @csrf
                    @method('put')
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
                            <a class="btn btn-secondary py-3 w-100" data-bs-toggle="modal" data-bs-target="#editWaktuModal">
                                <span data-feather="edit"></span>
                            </a>
                        </div>
                    </div>
                </form>
                <div class="mb-3">
                    <button href="" class="btn btn-primary py-3 w-100" data-bs-toggle="modal"
                        data-bs-target="#qrModal" {!! $detail->mulai_absen == null || $detail->akhir_absen == null ? 'disabled' : '' !!}>Generate QRCode</button>
                </div>
                {{-- </form> --}}
            </div>

            {{-- Daftar Hadir --}}
            <div class="col-lg-8 col-sm-12">
                <div class="row">
                    <div class="col-6">
                        <h3>Daftar Hadir</h3>
                    </div>
                    <div class="col-6">
                        <form action="/dashboard/kelas/{{ $detail->id }}/sesi" method="POST">
                            @csrf
                            @method('post')
                            <select class="form-select" id="sesiSelect">
                                @foreach ($sesi as $item)
                                    <option value="{{ $item->sesi }}" {!! $item->sesi == $sesiNow ? 'selected' : '' !!}>Sesi {{ $item->sesi }} -
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
                                    <th>{{ $loop->index + 1 }}</th>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->mahasiswa->nama_mahasiswa }}</td>
                                    <td>{{ $item->waktu_presensi }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td><a class="btn btn-secondary btn-sm"
                                            href="{{ url('dashboard/kelas/' . $item->id . '') }}">Check</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="row gx-1">
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
                </div> --}}
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
                                    value="{{ $detail->mulai_absen }}" />
                                <label for="jam">Mulai Absen</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="Jam Akhir Absen" data-sb-validations=""
                                    value="{{ $detail->akhir_absen }}" />
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
@endsection

@section('footer-scripts')
    @include('../script/detail-kelas-script')
@endsection
