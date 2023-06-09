@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Mahasiswa: {{ $mahasiswa->nama_mahasiswa }}</h1>
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

    <div class="body-white border rounded shadow">
        <div class="container mt-3">
            <div class="d-flex col-8 justify-content-between mb-3">
                <div class="">
                    <form action="/dashboard/academic/class/{{ $mahasiswa->kelas_id }}/{{ $mahasiswa->nim }}"
                        method="GET">
                        <select class="form-select" id="kelas" name="kelas">
                            @foreach ($jadwal as $item)
                                <option value="{{ $item->id }}" {!! $item->id == request('kelas') ? 'selected' : '' !!}>{{ $item->matkul->nama_matkul }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="div col-lg-12 col-sm-12 table-responsive">
                    <table class="table table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pekan</th>
                                <th>Tanggal</th>
                                <th>Waktu Presensi</th>
                                <th>Status Presensi</th>
                                <th>Action</th>
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
                                                @if ($item2->status == 'Tidak Hadir' || $item2->status == 'Izin')
                                                    -
                                                @endif
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
                                    <td>
                                        <a class="btn btn-warning btn-sm px-3"><span data-feather="edit"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $sesi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Preview Mahasiswa --}}
    <div class="modal fade" id="presensiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="presensiModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/class/" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Presensi Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/detail-mahasiswa-class-script')
@endsection
