@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Daftar Kelas</h1>
    </div>

    <div class="container">
        <div class="div table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Nama Kelas</th>
                        <th>Hari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $item)
                        <tr>
                            <th>{{ $loop->index+1 }}</th>
                            <td>{{ $item->matkul->nama_matkul }}</td>
                            <td>{{ $item->kelas->nama_kelas }}</td>
                            <td>{{ $item->hari . ' ' . $item->waktu_mulai }}</td>
                            <td><a class="btn btn-secondary btn-sm" href="{{ url('dashboard/kelas/'.$item->id.'') }}">Check</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
