@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Change Account Password") }}</h1>
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
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 col-sm-4">
                <div class="body-white border rounded shadow py-4 px-3">
                    <form action="/dashboard/account/mahasiswa/changePassword" method="POST">
                        @csrf
                        <div class="form-floating">
                            <input class="form-control" name="id" id="id" type="hidden" placeholder="ID"
                                value="{{ $data->user_id }}" readonly />
                        </div>
                        <div class="row gx-1 input-group d-flex">
                            <div class="form-floating mb-3 col-10">
                                <input class="form-control" name="old_password" id="old_password" type="password"
                                    placeholder="Old Password" required />
                                <label for="old_password">{{ __("Old Password") }}</label>
                            </div>
                            <div class="col-2 input-group-text mb-3 justify-content-center">
                                <a class="text-decoration-none text-primary" id="togglePasword1"
                                    style="cursor: pointer">{{ __("Show") }}</a>
                            </div>
                        </div>
                        <div class="row gx-1 input-group d-flex">
                            <div class="form-floating mb-3 col-10">
                                <input class="form-control" name="new_password" id="new_password" type="password"
                                    placeholder="New Password" required />
                                <label for="new_password">{{ __("New Password") }}</label>
                            </div>
                            <div class="col-2 input-group-text mb-3 justify-content-center">
                                <a class="text-decoration-none text-primary" id="togglePasword2"
                                    style="cursor: pointer">{{ __("Show") }}</a>
                            </div>
                        </div>
                        <div class="mb-2">
                            <button id="btnForPresensi" href="" class="btn btn-primary py-3 w-100">{{ __("Change Password") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/account-script')
@endsection
