<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-users fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total {{ __("Student") }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->mahasiswa }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/database/mahasiswa" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-user-tie fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total {{ __("Lecturer") }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->dosen }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/database/dosen" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-user-gear fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total Admin</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->admin }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/database/admin" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-chalkboard-user fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total {{ __("Class") }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->kelas }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/academic/class" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-book-bookmark fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total {{ __("Course") }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->matkul }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/academic/course" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                            <i class="fa-solid fa-calendar-day fa-xs text-dark"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden ms-3">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                            Total {{ __("Schedule") }}</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="825">{{ $dashboard->count->jadwal }}</span>
                            </h4>
                        </div>
                        <a href="dashboard/academic/schedule" class="text-decoration-underline text-link-color">{{ __("See details") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <h4 class="card-title mb-0 flex-grow-1">{{ __("Log Activities") }}</h4>
            </div>
            <div class="card-body pt-0">
                <div class="div table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="">
                            <tr>
                                <th>NIP</th>
                                <th>{{ __("Activity") }}</th>
                                <th>{{ __("Timestamp") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboard->logAdmin as $item)
                                <tr>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->activity }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="align-items-center mt-1 row g-3 text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted">{{ __("Showing") }} <span class="fw-semibold">{{ ($dashboard->logAdmin->currentpage() - 1) * $dashboard->logAdmin->perpage() + $dashboard->logAdmin->count() }}</span> {{ _("of") }} <span
                                class="fw-semibold">{{ $dashboard->logAdmin->total() }}</span> {{ __("Result") }}
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        {{ $dashboard->logAdmin->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <h4 class="card-title mb-0 flex-grow-1">{{ __("Log System") }}</h4>
            </div>
            <div class="card-body pt-0">
                <div class="div table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="">
                            <tr>
                                <th>{{ __("Activity") }}</th>
                                <th>Status</th>
                                <th>{{ __("Timestamp") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboard->logSystem as $item)
                                <tr>
                                    <td>{{ $item->activity }}</td>
                                    <td class="@if($item->status == "Success") text-success @else text-danger @endif">{{ $item->status }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="align-items-center mt-1 row g-3 text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted">{{ __("Showing") }} <span class="fw-semibold">{{ ($dashboard->logSystem->currentpage() - 1) * $dashboard->logSystem->perpage() + $dashboard->logSystem->count() }}</span> {{ __("of") }} <span
                                class="fw-semibold">{{ $dashboard->logSystem->total() }}</span> {{ __("Result") }}
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        {{ $dashboard->logSystem->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

