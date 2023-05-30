@extends('layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Kelas </h1>
    </div>

    @if (session()->has('message'))
        <div class="alert @if (session('status') == true) alert-success @else alert-danger @endif  alert-dismissible fade show mt-3 mx-3"
            id="notification" role="alert">
            @if (session('status') == true)
                <span class="me-1" data-feather="check-circle"></span>
            @else
                <span class="me-1" data-feather="alert-triangle"></span>
            @endif
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Detail Kelas --}}
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
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
                        data-sb-validations="" value="{{ \Carbon\Carbon::parse($detail->tanggal_mulai)->format('l') }}"
                        readonly />
                    <label for="hari">Hari</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="jam" id="jam" type="text" placeholder="Jam"
                        data-sb-validations="" value="{{ $detail->jam_mulai . ' - ' . $detail->jam_berakhir }}" readonly />
                    <label for="jam">Jam</label>
                </div>
                <div class="row gx-1">
                    <div class="form-floating mb-3 col-lg-6">
                        <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                            placeholder="Jam Mulai Absen" data-sb-validations="" value="{{ $detail->mulai_absen }}"
                            readonly />
                        <label for="jam">Jam Mulai Presensi</label>
                    </div>
                    <div class="form-floating mb-3 col-lg-6">
                        <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                            placeholder="Jam Akhir Absen" data-sb-validations="" value="{{ $detail->akhir_absen }}"
                            readonly />
                        <label for="jam">Jam Akhir Presensi</label>
                    </div>
                </div>
                <div class="mb-3">
                    <a href="../../dashboard/presensi" class="btn btn-primary py-3 w-100">Presensi</a>
                </div>
            </div>

            {{-- Daftar Hadir --}}
            <div class="col-lg-8 col-sm-12">
                <div class="row">
                    <div class="col-6">
                        <h3>History Presensi</h3>
                    </div>
                </div>

                <div class="div table-responsive">
                    <table class="table table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pekan</th>
                                <th>Tanggal</th>
                                <th>Waktu Presensi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sesi as $item)
                                <tr>
                                    <th>{{ ($sesi->currentpage() - 1) * $sesi->perpage() + $loop->index + 1 }}</th>
                                    <td>Pekan {{ $item->sesi }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>
                                        @foreach ($presensi as $item2)
                                            @if ($item->id == $item2->sesi_id)
                                                {{ $item2->waktu_presensi }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($presensi as $item2)
                                            @if ($item->id == $item2->sesi_id)
                                                {{ $item2->status }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $sesi->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-scripts')
    @include('../script/detail-kelas-script')
@endsection
