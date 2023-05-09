@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Database Mahasiswa</h1>
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
                    <input type="search" id="search" name="search" class="form-control" placeholder="Cari Mahasiswa" />
                    <button type="button" type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="">
                <a class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#tambahMahasiswaModal">Tambah
                    Mahasiswa</a>
            </div>
        </div>
        <div class="div table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tanggal Lahir</th>
                        <th>Gender</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswa as $item)
                        <tr>
                            <th>{{ $loop->index + 1 }}</th>
                            <td>{{ $item->nim }}</td>
                            <td>{{ $item->nama_mahasiswa }}</td>
                            <td>{{ $item->tanggal_lahir }}</td>
                            <td>{{ $item->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            <td>{{ $item->kelas->nama_kelas }}</td>
                            <td>
                                <a class="btn btn-warning btn-sm px-3" id="editBtn" data-bs-toggle="modal"
                                    data-bs-target="#editMahasiswaModal" data-user-id="{{ $item->user_id }}"
                                    data-nim="{{ $item->nim }}" data-nama="{{ $item->nama_mahasiswa }}"
                                    data-tanggal="{{ $item->tanggal_lahir }}" data-gender="{{ $item->gender }}"
                                    data-kelas="{{ $item->kelas_id }}"><span data-feather="edit"></span></a>
                                <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-user-id="{{ $item->user_id }}"
                                    data-id="{{ $item->nim }}" data-bs-toggle="modal"
                                    data-bs-target="#deleteMahasiswaModal"><span data-feather="x-circle"></span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Mahasiswa --}}
    <div class="modal fade" id="tambahMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tambahMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nim" id="nim" type="text" placeholder="NIM"
                                    required />
                                <label for="nim">NIM</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nama_mahasiswa" id="nama_mahasiswa" type="text"
                                    placeholder="Nama Mahasiswa" required />
                                <label for="nama_mahasiswa">Nama Mahasiswa</label>
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
                            <div class="form-floating mb-3">
                                <select class="form-select" name="kelas_id" id="kelas_id" required>
                                    <option selected>Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_id">Kelas</label>
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

    {{-- Modal Edit Mahasiswa --}}
    <div class="modal fade" id="editMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="user_id" id="user_id_edit" type="hidden" readonly />
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nim" id="nim_edit" type="text"
                                    placeholder="NIM" readonly />
                                <label for="nim">NIM</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="nama_mahasiswa" id="nama_mahasiswa_edit"
                                    type="text" placeholder="Nama Mahasiswa" required />
                                <label for="nama_mahasiswa">Nama Mahasiswa</label>
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
                            <div class="form-floating mb-3">
                                <select class="form-select" name="kelas_id" id="kelas_id_edit" required>
                                    <option selected>Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_id">Kelas</label>
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

    {{-- Modal Delete Mahasiswa --}}
    <div class="modal fade" id="deleteMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa/" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Apakah anda yakin akan menghapus data mahasiswa dengan NIM dibawah ini?</h6>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nim" id="nim_delete" type="text" placeholder="NIM"
                                readonly />
                            <label for="nim">NIM</label>
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
    @include('../script/mahasiswa-script')
@endsection
