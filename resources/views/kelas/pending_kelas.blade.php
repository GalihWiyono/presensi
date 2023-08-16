@extends('/layout/main')

@section('container')
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

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Pending Week List") }}</h1>
    </div>
    <div class="body-white border rounded shadow">
        <div class="container">
            <div class="div table-responsive">
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("Course") }}</th>
                            <th>{{ __("Class") }}</th>
                            <th>{{ __("Week") }}</th>
                            <th>{{ __("Date") }}</th>
                            <th>{{ __("Class Time") }}</th>
                            <th>{{ __("New Date") }}</th>
                            <th>{{ __("New Class Time") }}</th>
                            <th>{{ __("New Presence Time") }}</th>
                            <th>{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingWeek as $item)
                            <tr>
                                <th>{{ $loop->index + 1 }}</th>
                                <td>{{ $item->jadwal->matkul->nama_matkul }}</td>
                                <td>{{ $item->jadwal->kelas->nama_kelas }}</td>
                                <td>{{ __("Week") }} {{ $item->sesi->sesi }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ $item->jadwal->jam_mulai . ' - ' . $item->jadwal->jam_berakhir }}</td>
                                <td>{{ $item->tanggal_baru == null ? '-' : $item->tanggal_baru }}</td>
                                <td>{{ $item->jam_mulai_baru == null || $item->jam_berakhir_baru == null ? '-' : $item->jam_mulai_baru . ' - ' . $item->jam_berakhir_baru }}
                                <td>{{ $item->mulai_absen_baru == null || $item->akhir_absen_baru == null ? '-' : $item->mulai_absen_baru . ' - ' . $item->akhir_absen_baru }}
                                </td>
                                <td><a class="btn btn-warning btn-sm px-3" data-bs-toggle="modal" id="addNewDate"
                                        data-bs-target="#editWaktuModal" data-id="{{ $item->id }}"
                                        data-new-date="{{ $item->tanggal_baru }}"
                                        data-start-time="{{ $item->jam_mulai_baru }}"
                                        data-end-time="{{ $item->jam_berakhir_baru }}"
                                        data-start-absen="{{ $item->mulai_absen_baru }}"
                                        data-end-absen="{{ $item->akhir_absen_baru }}"><span data-feather="edit"></i></a>
                                    <a class="btn btn-primary btn-sm px-3"
                                        href="{{ url('dashboard/pending/' . $item->id) }}"><span
                                            data-feather="eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal edit waktu --}}
    <div class="modal fade" id="editWaktuModal" tabindex="-1" aria-labelledby="editWaktuModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/dashboard/pending" method="POST">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModal">{{ __("Register Pending Class Date") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" name="id" id="id" type="hidden" />
                        <div class="form-floating mb-3">
                            <input class="form-control" name="tanggal_baru" id="new_date" type="date"
                                placeholder="New Date" min="{{ date('Y-m-d') }}" required />
                            <label for="new_date">{{ __("New Date") }}</label>
                        </div>

                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="jam_mulai" id="jam_mulai" type="time"
                                    placeholder="Start Class Time" required />
                                <label for="jam_mulai">{{ __("Start Class Time") }}</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="jam_berakhir" id="jam_berakhir" type="time"
                                    placeholder="End Presence Time" required />
                                <label for="jam_berakhir">{{ __("End Class Time") }}</label>
                            </div>
                        </div>

                        <div class="row gx-1">
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="mulai_absen" id="mulai_absen" type="time"
                                    placeholder="Start Presence Time" required />
                                <label for="mulai_absen">{{ __("Presence Start Time") }}</label>
                            </div>
                            <div class="form-floating mb-3 col-lg-6">
                                <input class="form-control" name="akhir_absen" id="akhir_absen" type="time"
                                    placeholder="End Presence Time" required />
                                <label for="akhir_absen">{{ __("Presence End Time") }}</label>
                            </div>
                        </div>
                        <span class="text-danger">
                            {{ __("When you save a new attendance date and time, the system will automatically register this pending week to be closed automatically when the class is finished") }}.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button class="btn btn-primary" type="submit">{{ __("Save") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
    @include('../script/pending-kelas-script')
@endsection
