@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Students Database") }}</h1>
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
            <div class="d-flex justify-content-between mb-3">
                <div class="">
                    <form action="/dashboard/database/mahasiswa">
                        <div class="input-group">
                            <input type="search" id="search" name="search" class="form-control"
                                placeholder="{{ __("Search Student") }}" value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="">
                    <a class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#tambahMahasiswaModal">{{ __("Upload Student") }}</a>
                    <a class="btn btn-success px-4" href="/dashboard/database/mahasiswa/export">{{ __("Download Student") }}</a>
                </div>
            </div>
            <div class="div table-responsive">
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIM</th>
                            <th>{{ __("Student Name") }}</th>
                            <th>{{ __("Date Of Birth") }}</th>
                            <th>{{ __("Gender") }}</th>
                            <th>{{ __("Class") }}</th>
                            <th>{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswa as $item)
                            <tr>
                                <th>{{ ($mahasiswa->currentpage() - 1) * $mahasiswa->perpage() + $loop->index + 1 }}</th>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama_mahasiswa }}</td>
                                <td>{{ $item->tanggal_lahir }}</td>
                                <td>{{ $item->gender == 'L' ? __("Male") : __("Female") }}</td>
                                <td>{{ $item->kelas == null ? '-' : $item->kelas->nama_kelas }}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm px-3" id="changePassword" data-bs-toggle="modal"
                                        data-bs-target="#gantiPasswordModal" data-user-id="{{ $item->user_id }}"
                                        data-nim="{{ $item->nim }}"><i class="fa-solid fa-unlock-keyhole"></i></a>
                                    {{-- <a class="btn btn-warning btn-sm px-3" id="editBtn" data-bs-toggle="modal"
                                        data-bs-target="#editMahasiswaModal" data-user-id="{{ $item->user_id }}"
                                        data-nim="{{ $item->nim }}" data-nama="{{ $item->nama_mahasiswa }}"
                                        data-tanggal="{{ $item->tanggal_lahir }}" data-gender="{{ $item->gender }}"
                                        data-kelas="{{ $item->kelas_id }}"><span data-feather="edit"></span></a>
                                    <a class="btn btn-danger btn-sm px-3" id='deleteBtn'
                                        data-user-id="{{ $item->user_id }}" data-id="{{ $item->nim }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteMahasiswaModal"><span
                                            data-feather="x-circle"></span></a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah Mahasiswa --}}
    <div class="modal fade" id="tambahMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tambahMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa/import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Upload Student Master Data") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="file" id="formFile" name="file"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                            required>
                        <span class="text-danger">
                            {{ __("Only files of types xlsx, xls, and csv are supported") }}.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-primary" type="submit">{{ __("Upload") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Change Password User --}}
    <div class="modal fade" id="gantiPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="gantiPasswordModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa/changePassword" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Change Student Password") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nim" id="nim_password" type="text"
                                placeholder="NIM" readonly />
                            <label for="nim">NIM</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="user_id" id="user_id_password" type="hidden" readonly />
                        </div>
                        <div class="row gx-1 input-group">
                            <div class="form-floating mb-3 col-10">
                                <input class="form-control" name="admin_password" id="admin_password" type="password"
                                    placeholder="Admin Password" required />
                                <label for="admin_password">{{ __("Admin Password") }}</label>
                            </div>
                            <div class="col-2 input-group-text mb-3 d-flex justify-content-center">
                                <a class="text-decoration-none text-primary" id="togglePasword"
                                    style="cursor: pointer">{{ __("Show") }}</a>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="student_password" id="student_password" type="text"
                                placeholder="Student Password" required />
                            <label for="student_password">{{ __("Student New Password") }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-primary" type="submit">{{ __("Change") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Mahasiswa --}}
    {{-- <div class="modal fade" id="editMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Student</h5>
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
                                <label for="nama_mahasiswa">Student Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="tanggal_lahir" id="tanggal_lahir_edit" type="date"
                                    placeholder="Tanggal Lahir" required />
                                <label for="tanggal_lahir">Date of Birth</label>
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
                                    <option value="default" selected disabled hidden>Choose Class</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_id">Class</label>
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
    </div> --}}

    {{-- Modal Delete Mahasiswa --}}
    {{-- <div class="modal fade" id="deleteMahasiswaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteMahasiswaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/database/mahasiswa" method="POST">
                  	@method('delete')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure you want to delete the student data with the following NIM?</h6>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="nim" id="nim_delete" type="text" placeholder="NIM"
                                readonly />
                            <label for="nim">NIM</label>
                        </div>
                        <span class="text-danger">The deleted data cannot be recovered. Be careful when performing this action!</span>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="user_id" id="user_id_delete" type="hidden" readonly />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

@section('footer-scripts')
    @include('../script/mahasiswa-script')
@endsection
