<div class="row">
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
                            Schedule</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                    data-target="825">{{ $dashboard->jadwal->byNip }}</span>
                            </h4>
                        </div>
                        <a class="text-decoration-none text-muted">from {{ $dashboard->jadwal->total }} schedules</a>
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
                            Class</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                    data-target="825">{{ $dashboard->kelas->byNip }}</span>
                            </h4>
                        </div>
                        <a class="text-decoration-none text-muted">from {{ $dashboard->kelas->total }} classes</a>
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
                            Course</p>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                    data-target="825">{{ $dashboard->matkul->byNip }}</span>
                            </h4>
                        </div>
                        <a class="text-decoration-none text-muted">from {{ $dashboard->matkul->total }} courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <h4 class="card-title mb-0 flex-grow-1">Upcoming Weekly Activities</h4>
            </div><!-- end card header -->
            <div class="card-body pt-0">
                <ul class="list-group list-group-flush border-dashed">
                    @foreach ($dashboard->activity as $item)
                        <li class="list-group-item ps-0">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <div class="avatar-md py-1 px-2 py-2 h-auto avatar-body-gray rounded-3">
                                        <div class="text-center">
                                            <h5 class="mb-0">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                                            </h5>
                                            <div class="text-muted fs-14">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('D') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="text-muted mt-0 mb-1 fs-13">{{ $item->jadwal->jam_mulai }} -
                                        {{ $item->jadwal->jam_berakhir }}</h5>
                                    <a class="text-decoration-none text-muted fs-14 mb-0">Pertemuan Kelas
                                        {{ $item->jadwal->kelas->nama_kelas }}, Mata Kuliah
                                        {{ $item->jadwal->matkul->nama_matkul }}</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="align-items-center row g-3 text-center text-sm-start mt-1">
                    <div class="col-sm">
                        <div class="text-muted">Showing <span
                                class="fw-semibold">{{ ($dashboard->activity->currentpage() - 1) * $dashboard->activity->perpage() + $dashboard->activity->count() }}
                            </span> of <span class="fw-semibold">{{ $dashboard->activity->total() }}</span> Results
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        {{ $dashboard->activity->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <h4 class="card-title mb-0 flex-grow-1">Log Activities</h4>
            </div><!-- end card header -->
            <div class="card-body pt-0">
                <div class="div table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="text-center">
                            <tr>
                                <th>Activity</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboard->log as $item)
                                <tr>
                                    <td>{{ $item->activity }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="align-items-center mt-1 row g-3 text-center text-sm-start">
                    <div class="col-sm">
                        <div class="text-muted">Showing <span class="fw-semibold">{{ ($dashboard->log->currentpage() - 1) * $dashboard->log->perpage() + $dashboard->log->count() }}</span> of <span
                                class="fw-semibold">{{ $dashboard->log->total() }}</span> Results
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        {{ $dashboard->log->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
