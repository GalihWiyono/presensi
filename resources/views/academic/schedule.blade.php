@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Academic Schedule</h1>
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
                    <form action="/dashboard/academic/schedule">
                        <div class="input-group">
                            <select class="form-select" name="filter" id="filter" value="{{ request('filter') }}">
                                <option value="Course" {!! request('filter') == 'Course' ? 'selected' : '' !!}>Course</option>
                                <option value="Class" {!! request('filter') == 'Class' ? 'selected' : '' !!}>Class</option>
                                <option value="Lecture" {!! request('filter') == 'Lecture' ? 'selected' : '' !!}>Lecture</option>
                            </select>
                            <input type="search" id="search" name="search" class="form-control" placeholder="Search"
                                value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="">
                    <a class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#tambahJadwalModal">Add
                        Schedule</a>
                </div>
            </div>
            <div class="div table-responsive">
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Class</th>
                            <th>Lecture</th>
                            <th>Start Course Date</th>
                            <th>Class Start Time</th>
                            <th>Class End Time</th>
                            <th>Attendance Start Time</th>
                            <th>Attendance End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $item)
                            <tr>
                                <th>{{ ($jadwal->currentpage() - 1) * $jadwal->perpage() + $loop->index + 1 }}</th>
                                <td>{{ $item->matkul->nama_matkul }}</td>
                                <td>{{ $item->kelas->nama_kelas }}</td>
                                <td>{{ $item->dosen->nama_dosen }}</td>
                                <td>{{ $item->tanggal_mulai->toDateString() }}</td>
                                <td>{{ $item->jam_mulai }}</td>
                                <td>{{ $item->jam_berakhir }}</td>
                                <td>{{ $item->mulai_absen ?: '-' }}</td>
                                <td>{{ $item->akhir_absen ?: '-' }}</td>
                                <td>
                                    <a href="/dashboard/academic/schedule/{{ $item->id }}/pdf" class="btn btn-success btn-sm px-3"><i class="fa-solid fa-download"></i></a>
                                    <a class="btn btn-warning btn-sm px-3" id="editBtn" data-bs-toggle="modal"
                                        data-bs-target="#editJadwalModal" data-id="{{ $item->id }}"
                                        data-matkul="{{ $item->matkul_id }}" data-kelas="{{ $item->kelas_id }}"
                                        data-dosen="{{ $item->nip }}"
                                        data-tanggal-mulai="{{ $item->tanggal_mulai->toDateString() }}"
                                        data-jam-mulai="{{ $item->jam_mulai }}"
                                        data-jam-berakhir="{{ $item->jam_berakhir }}"><span data-feather="edit"></span></a>
                                    <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-id="{{ $item->id }}"
                                        data-matkul="{{ $item->matkul->nama_matkul }}"
                                        data-kelas="{{ $item->kelas->nama_kelas }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteJadwalModal"><span data-feather="x-circle"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $jadwal->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Tambah Jadwal --}}
    <div class="modal fade" id="tambahJadwalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tambahJadwalModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/schedule" method="POST">
                    @csrf
                    @method('post')
                    <div class="modal-header">
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="matkul_id" id="matkul_id" required>
                                    <option value selected disabled hidden>Choose Course</option>
                                    @foreach ($matkul as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_matkul }}</option>
                                    @endforeach
                                </select>
                                <label for="matkul_id">Course</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="kelas_id" id="kelas_id" required>
                                    <option value selected disabled hidden>Choose Class</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_id">Class</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="dosen_id" id="dosen_id" required>
                                    <option value selected disabled hidden>Choose Lecture</option>
                                    @foreach ($dosen as $item)
                                        <option value="{{ $item->nip }}">{{ $item->nama_dosen }}</option>
                                    @endforeach
                                </select>
                                <label for="dosen_id">Lecture</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai" type="date" required/>
                                <label for="tanggal_mulai">Start Course Date</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="jam_mulai" id="jam_mulai" type="time"
                                    placeholder="Jam Mulai Kelas" min="07:00" max="18:00" required
                                    oninvalid="setCustomValidity('Jam mulai kelas antara 07:00 - 18:00')"
                                    oninput="setCustomValidity('')" />
                                <label for="jam_mulai">Class Start Time</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="jam_berakhir" id="jam_berakhir" type="time"
                                    placeholder="Jam Akhir Kelas" min="07:00" max="18:00" required
                                    oninvalid="setCustomValidity('Jam akhir kelas antara 07:00 - 18:00')"
                                    oninput="setCustomValidity('')" />
                                <label for="jam_berakhir">Class End Time</label>
                            </div>
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

    {{-- Modal Edit jadwal --}}
    <div class="modal fade" id="editJadwalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editJadwalModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/schedule" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="id" id="id_edit" type="hidden" readonly />
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="matkul_id" id="matkul_edit" required>
                                    <option value selected disabled hidden>Choose Course</option>
                                    @foreach ($matkul as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_matkul }}</option>
                                    @endforeach
                                </select>
                                <label for="matkul_edit">Course</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="kelas_id" id="kelas_edit" required>
                                    <option value selected disabled hidden>Choose Class</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <label for="kelas_edit">Class</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-select" name="dosen_id" id="dosen_edit" required>
                                    <option value selected disabled hidden>Choose Lecture</option>
                                    @foreach ($dosen as $item)
                                        <option value="{{ $item->nip }}">{{ $item->nama_dosen }}</option>
                                    @endforeach
                                </select>
                                <label for="dosen_edit">Lecture</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="tanggal_mulai" id="tanggal_mulai_edit"
                                    type="date" required/>
                                <label for="hari">Start Course Date</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="jam_mulai" id="jam_mulai_edit" type="time"
                                    placeholder="Jam Mulai Kelas" min="07:00" max="18:00" required
                                    oninvalid="setCustomValidity('Jam mulai kelas antara 07:00 - 18:00')"
                                    oninput="setCustomValidity('')" />
                                <label for="jam_mulai_edit">Class Start Time</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" name="jam_berakhir" id="jam_berakhir_edit" type="time"
                                    placeholder="Jam Akhir Kelas" min="07:00" max="18:00" required
                                    oninvalid="setCustomValidity('Jam akhir kelas antara 07:00 - 18:00')"
                                    oninput="setCustomValidity('')" />
                                <label for="jam_berakhir_edit">Class End Time</label>
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
    </div>

    {{-- Modal Delete Jadwal --}}
    <div class="modal fade" id="deleteJadwalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteJadwalModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/schedule" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Delete this schedule?</h6>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="id" id="id_delete" type="hidden" readonly />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="matkul" id="matkul_delete" type="text"
                                placeholder="Mata Kuliah" readonly />
                            <label for="matkul">Course</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="kelas" id="kelas_delete" type="text"
                                placeholder="Kelas" readonly />
                            <label for="kelas">Class</label>
                        </div>
                        <span class="text-danger">If you delete this schedule, all the presences and weeks belong to this schedule will be deleted too!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/jadwal-script')
@endsection
