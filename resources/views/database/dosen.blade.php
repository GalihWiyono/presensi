@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Database Dosen</h1>
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

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <div class="">
                <div class="input-group">
                    <input type="search" id="search" name="search" class="form-control" placeholder="Cari dosen" />
                    <button type="button" type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="">
                <a class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#tambahdosenModal">Tambah
                    Dosen</a>
            </div>
        </div>
        <div class="div table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama Dosen</th>
                        <th>Tanggal Lahir</th>
                        <th>Gender</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dosen as $item)
                        <tr>
                            <th>{{ $loop->index + 1 }}</th>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama_dosen }}</td>
                            <td>{{ $item->tanggal_lahir }}</td>
                            <td>{{ $item->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            <td>
                                <a class="btn btn-warning btn-sm px-3" id="editBtn" data-bs-toggle="modal"
                                    data-bs-target="#editDosenModal" data-user-id="{{ $item->user_id }}"
                                    data-nip="{{ $item->nip }}" data-nama="{{ $item->nama_dosen }}"
                                    data-tanggal="{{ $item->tanggal_lahir }}" data-gender="{{ $item->gender }}"><span
                                        data-feather="edit"></span></a>
                                <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-user-id="{{ $item->user_id }}"
                                    data-id="{{ $item->nip }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteDosenModal"><span data-feather="x-circle"></span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah dosen --}}
    <div class="modal fade" id="tambahdosenModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tambahdosenModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/dosen" method="POST">
                    @csrf
                    @method('post')
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nip" id="nip" type="text" placeholder="NIP"
                                    required />
                                <label for="nip">NIP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nama_dosen" id="nama_dosen" type="text"
                                    placeholder="Nama dosen" required />
                                <label for="nama_dosen">Nama dosen</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="tanggal_lahir" id="tanggal_lahir" type="date"
                                    placeholder="Tanggal Lahir" required />
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="gender" id="gender" required>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Dosen --}}
    <div class="modal fade" id="editDosenModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editDosenModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/dosen" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="user_id" id="user_id_edit" type="hidden" readonly />
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nip" id="nip_edit" type="text"
                                    placeholder="nip" readonly />
                                <label for="nip">NIP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nama_dosen" id="nama_dosen_edit" type="text"
                                    placeholder="Nama dosen" required />
                                <label for="nama_dosen">Nama Dosen</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="tanggal_lahir" id="tanggal_lahir_edit" type="date"
                                    placeholder="Tanggal Lahir" required />
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="gender" id="gender_edit" required>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <label for="gender">Gender</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete Dosen --}}
    <div class="modal fade" id="deleteDosenModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteDosenModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/dosen/" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Apakah anda yakin akan menghapus data dosen dengan NIP dibawah ini?</h6>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nip" id="nip_delete" type="text" placeholder="NIP"
                                readonly />
                            <label for="nip">NIP</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="user_id" id="user_id_delete" type="hidden" readonly />
                        </div>
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
    @include('../script/dosen-script')
@endsection
