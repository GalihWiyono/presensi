@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Class: {{ $kelas->nama_kelas }}</h1>
    </div>

    {{-- @if (session()->has('message'))
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
    @endif --}}

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
                    <form action="/dashboard/academic/class">
                        <div class="input-group">
                            <input type="search" id="search" name="search" class="form-control"
                                placeholder="Search Student" value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="div col-lg-8 col-sm-12 table-responsive">
                    <table class="table table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIM</th>
                                <th>Nama Mahasiswa</th>
                                <th>Tanggal Lahir</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggota as $item)
                                <tr>
                                    <th>{{ ($anggota->currentpage() - 1) * $anggota->perpage() + $loop->index + 1 }}</th>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->mahasiswa->nama_mahasiswa }}</td>
                                    <td>{{ $item->mahasiswa->tanggal_lahir }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm px-3"
                                            href="/dashboard/academic/class/{{ $item->kelas_id }}/{{ $item->nim }}"><span
                                                data-feather="eye"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $anggota->links() }}
                    </div>
                </div>
                <div class="div col-lg-4 col-sm-12 mb-3" style="padding-left:20px; border-left: 1px solid #ccc;">
                    <form id="class" action="/dashboard/academic/class" method="POST">
                        @csrf
                        <div class="mb-4">
                            <h5 class="text-center" id="title-class">Report Presensi</h5>
                        </div>
                        <div class="">
                            <input class="form-control" name="id" id="id_kelas" type="hidden" placeholder=""
                                required />
                            <div class="form-floating mb-4">
                                <select class="form-select" name="kelas_id" id="kelas_id_edit" required>
                                    <option value="PDF" selected>PDF</option>
                                </select>
                                <label for="nama_kelas">File Type</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-success" type="submit" id="send-class" disabled>Download</button>
                        </div>
                    </form>
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
    @include('../script/class-script')
@endsection
