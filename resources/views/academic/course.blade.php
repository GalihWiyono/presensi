@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Academic Course") }}</h1>
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
                    <form action="/dashboard/academic/course">
                        <div class="input-group">
                            <input type="search" id="search" name="search" class="form-control"
                                placeholder="{{ __("Search Course") }}" value="{{ request('search') }}" />
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
                                <th>{{ __("Course Name") }}</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matkul as $item)
                                <tr>
                                    <th>{{ ($matkul->currentpage() - 1) * $matkul->perpage() + $loop->index + 1 }}</th>
                                    <td>{{ $item->nama_matkul }}</td>
                                    {{-- <td>
                                        <a class="btn btn-warning btn-sm px-3" id="editBtn" data-id="{{ $item->id }}"
                                            data-matkul="{{ $item->nama_matkul }}" data-bs-toggle="modal"
                                            data-bs-target="#editCourseModal"><span data-feather="edit"></span></a>
                                        <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-id="{{ $item->id }}"
                                            data-matkul="{{ $item->nama_matkul }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteCourseModal"><span data-feather="x-circle"></span></a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $matkul->links() }}
                    </div>
                </div>
                <div class="div col-lg-4 col-sm-12 mb-3" style="padding-left:20px; border-left: 1px solid #ccc;">
                    <form id="course" action="/dashboard/academic/course/import" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="mb-3">
                            <h5 class="text-center" id="title-course">{{ __("Upload Course Master Data") }}</h5>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" id="formFile" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required>
                            <span class="text-danger">
                                {{ __("Only files of types xlsx, xls, and csv are supported") }}.</span>
                        </div>
                        <div class="mb-4 d-flex justify-content-center">
                            <button class="btn btn-primary w-75" type="submit" id="send-course">{{ __("Upload Course") }}</button>
                        </div>
                    </form>
                    <hr class="bg-danger border-2 border-top border-dark">
                    <div class="mb-3">
                        <h5 class="text-center" id="title-class">{{ __("Download Course Master Data") }}</h5>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <a class="btn btn-success w-75" href="/dashboard/academic/course/export">{{ __("Download Course") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Class --}}
    {{-- <div class="modal fade" id="editCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editCourseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/course" method="POST">
                  	@method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="id" id="id_matkul" type="hidden" placeholder=""
                            readonly />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_matkul" id="nama_matkul" type="text"
                                placeholder="Nama Mata Kuliah" />
                            <label for="nama_kelas">Course</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-warning" type="submit">Edit</button>
                    </div>
                </form>
            </div>
        </div>
	</div> --}}

    {{-- Modal Delete Course --}}
    {{-- <div class="modal fade" id="deleteCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteCourseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/course" method="POST">
                  	@method('delete')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure want to delete this course?</h6>
                        <input class="form-control" name="id_matkul" id="id_matkul_delete" type="hidden" placeholder=""
                            required />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_matkul" id="nama_matkul_delete" type="text"
                                placeholder="Nama Mata Kuliah" readonly />
                            <label for="nama_matkul">Nama Mata Kuliah</label>
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
    @include('../script/course-script')
@endsection
