@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ __("Schedules List") }} : {{ $kelas->nama_kelas }}</h1>
    </div>

    <div class="body-white border rounded shadow">
        <div class="container">
            <div class="div table-responsive">
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __("Course") }}</th>
                            <th>{{ __("Lecturer") }}</th>
                            <th>{{ __("Day") }}</th>
                            <th>{{ __("Class Time") }}</th>
                            <th>{{ __("Presence Time") }}</th>
                            <th>{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwal as $item)
                            <tr>
                                <th>{{ $loop->index + 1 }}</th>
                                <td>{{ $item->matkul->nama_matkul }}</td>
                                <td>{{ $item->dosen->nama_dosen }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('l') }}</td>
                                <td>{{ $item->jam_mulai . ' - ' . $item->jam_berakhir }}</td>
                                <td>{{ $item->mulai_absen . ' - ' . $item->akhir_absen }}</td>
                                <td><a class="btn btn-primary btn-sm px-3"
                                        href="{{ url('dashboard/jadwal/' . $item->id . '') }}"><span data-feather="eye"></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
