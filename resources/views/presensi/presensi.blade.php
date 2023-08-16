@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Presence") }}</h1>
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

    <div class="container">
        <div class="row">
            <div id='map'></div>
            <div class="col-lg-6 col-sm-12 mx-auto" id="reader">
            </div>
        </div>
    </div>

    {{-- Modal Presensi --}}
    <div class="modal fade" id="presensiModal" tabindex="-1" aria-labelledby="presensiModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/dashboard/presensi/store" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="presensiModal">{{ __("Presence Class") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="id" id="id" type="hidden" value=""
                                readonly />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="sesi_id" id="sesi_id" type="hidden" value=""
                                readonly />
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="mataKuliah" id="mataKuliah" type="text"
                                value=""readonly />
                            <label for="mataKuliah">{{ __("Course") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="dosen" id="dosen" type="text"
                                value=""readonly />
                            <label for="mataKuliah">{{ __("Lecturer") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="kelas" id="kelas" type="text" value=""
                                readonly />
                            <label for="kelas">{{ __("Class") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="pekan" id="pekan" type="text" value=""
                                readonly />
                            <label for="Pekan">{{ __("Week") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="jam" id="jam" type="text" value=""
                                readonly />
                            <label for="jam">{{ __("Class Time") }}</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" name="jam_presensi" id="jam_presensi" type="text" value=""
                                readonly />
                            <label for="jam">{{ __("Presence Time") }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</a>
                        <button type="submit" id="presensiBtn" class="btn btn-primary">{{ __("Presence") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Lokasi Denied --}}
    <div class="modal fade" id="lokasiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="lokasiModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Location Not Found") }}</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-justify">
                        {{__("Your location was not found. Please enable permission to obtain your location in order to access the features available on this website")}}!</h6>

                    <h6>{{ __("Please reactivate the location permission by following this tutorial") }}</h6>
                    <a href="#" id="lokasiLink" class="btn btn-info">Click</a>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-secondary" onclick="window.location='{{ route('dashboard') }}'">{{ __("Back") }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Lokasi Outside --}}
    <div class="modal fade" id="lokasiOutsideModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="lokasiOutsideModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Location Outside The Campus Area") }}</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-justify">{{__("Your location is outside the designated campus location. Please go to the specified location in order to access the features available on this website")}}.</h6>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-secondary" onclick="window.location='{{ route('dashboard') }}'">Back</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal QRCode Invalid --}}
    <div class="modal fade" id="qrInvalid" tabindex="-1" aria-labelledby="qrInvalid" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("QRCode Invalid") }}</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-justify">{{ __("You are not registered for this class or the QRCode is invalid, please double-check the QRCode you are scanning") }}!!</h6>
                </div>
            </div>
        </div>
    </div>
    @include('../script/qrcode-script')
@endsection

@section('footer-scripts')
    @include('../script/leaflet-script')
@endsection
