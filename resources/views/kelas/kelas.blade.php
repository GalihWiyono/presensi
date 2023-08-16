@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Schedule List") }}</h1>
    </div>
    <div class="body-white border rounded shadow">
        <div class="container">
            <div class="div table-responsive">
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("Course") }}</th>
                            <th>{{ _("Class") }}</th>
                            <th>{{ __("Day") }}</th>
                            <th>{{ __("Class Time") }}</th>
                            <th>{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $item)
                            <tr>
                                <th>{{ $loop->index + 1 }}</th>
                                <td>{{ $item->matkul->nama_matkul }}</td>
                                <td>{{ $item->kelas->nama_kelas }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('l') }}</td>
                                <td>{{ $item->jam_mulai . ' - ' . $item->jam_berakhir }}</td>
                                <td><a class="btn btn-primary btn-sm px-3"
                                        href="{{ url('dashboard/kelas/' . $item->id . '') }}"><span data-feather="eye"></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
