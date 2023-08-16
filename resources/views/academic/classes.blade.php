@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Academic Class") }}</h1>
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
            <div class="d-flex justify-content-between mb-3">
                <div class="">
                    <form action="/dashboard/academic/class">
                        <div class="input-group">
                            <input type="search" id="search" name="search" class="form-control"
                                placeholder="{{ __("Search Class") }}" value="{{ request('search') }}" />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-sm-12 table-responsive">
                    <table class="table table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __("Class") }}</th>
                                <th>{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelas as $item)
                                <tr>
                                    <th>{{ ($kelas->currentpage() - 1) * $kelas->perpage() + $loop->index + 1 }}</th>
                                    <td>{{ $item->nama_kelas }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" id="downloadBtn" data-bs-target="#downloadModal"
                                            data-id="{{ $item->id }}" class="btn btn-success btn-sm px-3"><i
                                                class="fa-solid fa-download"></i></a>
                                        <a class="btn btn-primary btn-sm px-3" id="detailKelas"
                                            href="/dashboard/academic/class/{{ $item->id }}"><span
                                                data-feather="eye"></span></a>
                                        {{-- <a class="btn btn-warning btn-sm px-3" id="editBtn" data-id="{{ $item->id }}"
                                            data-kelas="{{ $item->nama_kelas }}"><span data-feather="edit" 
                                            data-bs-toggle="modal" data-bs-target="#editCourseModal"></span></a>
                                        <a class="btn btn-danger btn-sm px-3" id='deleteBtn' data-id="{{ $item->id }}"
                                            data-kelas="{{ $item->nama_kelas }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteCourseModal"><span data-feather="x-circle"></span></a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $kelas->links() }}
                    </div>
                </div>
                <div class="div col-lg-4 col-sm-12 mb-3" style="padding-left:20px; border-left: 1px solid #ccc;">
                    <form id="class" action="/dashboard/academic/class/import" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="mb-3">
                            <h5 class="text-center" id="title-class">{{ __("Upload Class Master Data") }}</h5>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" id="formFile" name="file"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required>
                            <span class="text-danger">
                                {{ __("Only files of types xlsx, xls, and csv are supported") }}.</span>
                        </div>
                        <div class="mb-4 d-flex justify-content-center">
                            <button class="btn btn-primary w-75" type="submit" id="send-class">{{ __("Upload Class") }}</button>
                        </div>
                    </form>
                    <hr class="bg-danger border-2 border-top border-dark">
                    <div class="mb-3">
                        <h5 class="text-center" id="title-class">{{ __("Download Class Master Data") }}</h5>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <a class="btn btn-success w-75" href="/dashboard/academic/class/export">{{ __("Download Class") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Download --}}
    <div class="modal fade" id="downloadModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="downloadModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="GET" id="formDownload" target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __("Download Compensation Report") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="kelas_id" name="id">
                        <div class="form-floating mb-3">
                            <select class="form-select" name="download_type" id="download_type" required>
                                <option value selected disabled hidden>{{ __("Choose Type") }}</option>
                                <option value="PDF">PDF</option>
                                <option value="Excel">Excel</option>
                            </select>
                            <label for="download_type">{{ __("Type") }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-success" type="submit">{{ __("Download") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Class --}}
    {{-- <div class="modal fade" id="editCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editCourseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/class" method="POST">
                  	@method('put')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="id" id="id_kelas" type="hidden" placeholder=""
                            readonly />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_kelas" id="nama_kelas" type="text"
                                placeholder="Nama Mata Kuliah" />
                            <label for="nama_kelas">Class</label>
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

    {{-- Modal Delete Class --}}
    {{-- <div class="modal fade" id="deleteCourseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteCourseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/academic/class" method="POST">
                  	@method('delete')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure want to delete this course?</h6>
                        <input class="form-control" name="id" id="id_kelas_delete" type="hidden" placeholder=""
                            readonly />
                        <div class="form-floating mb-4">
                            <input class="form-control" name="nama_kelas" id="nama_kelas_delete" type="text"
                                placeholder="Nama Mata Kuliah" readonly />
                            <label for="nama_kelas">Class</label>
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
    @include('../script/classes-script')
@endsection
