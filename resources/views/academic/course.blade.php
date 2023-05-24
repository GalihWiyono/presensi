@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Academic Course</h1>
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
                <form action="/dashboard/academic/course">
                    <div class="input-group">
                        <input type="search" id="search" name="search" class="form-control" placeholder="Search Course"
                            value="{{ request('search') }}" />
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
                            <th>Course Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($matkul as $item)
                            <tr>
                                <th>{{ ($matkul->currentpage() - 1) * $matkul->perpage() + $loop->index + 1 }}</th>
                                <td>{{ $item->nama_matkul }}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm px-3" id="editBtn" data-id="{{ $item->id }}"
                                        data-matkul="{{ $item->nama_matkul }}"><span data-feather="edit"></span></a>
                                    <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-id="{{ $item->id }}"
                                        data-matkul="{{ $item->nama_matkul }}" data-bs-toggle="modal"
                                        data-bs-target="#deleteCourseModal"><span data-feather="x-circle"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $matkul->links() }}
                </div>
            </div>
            <div class="div col-lg-4 col-sm-12 table-responsive" style="padding-left:20px; border-left: 1px solid #ccc;">
                <form id="course" action="/dashboard/academic/course" method="POST">
                    @csrf
                    <div class="mb-4">
                        <h5 class="text-center" id="title-course">Add Course</h5>
                    </div>
                    <div class="">
                        <input class="form-control" name="id_matkul" id="id_matkul" type="hidden"
                        placeholder="" required />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_matkul" id="nama_matkul" type="text"
                                placeholder="Nama Mata Kuliah" required />
                            <label for="nama_matkul">Nama Mata Kuliah</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <button class="btn btn-secondary" type="reset" id="reset-course">Clear</button>
                        <button class="btn btn-primary" type="submit" id="send-course">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal Delete Mahasiswa --}}
    <div class="modal fade" id="deleteCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteCourseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/course/" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure want to delete this course?</h6>
                        <input class="form-control" name="id_matkul" id="id_matkul_delete" type="hidden"
                        placeholder="" required />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_matkul" id="nama_matkul_delete" type="text"
                                placeholder="Nama Mata Kuliah" required />
                            <label for="nama_matkul">Nama Mata Kuliah</label>
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
    @include('../script/course-script')
@endsection
