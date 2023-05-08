@extends('/layout/main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Database Mahasiswa</h1>
    </div>

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <div class="form-outline">
                <input type="search" id="form1" class="form-control" placeholder="Cari Mahasiswa" />
            </div>
            <div class="">
                <a class="btn btn-primary px-4" href="">Tambah Mahasiswa</a>
            </div>
        </div>
        <div class="div table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tanggal Lahir</th>
                        <th>Gender</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswa as $item)
                        <tr>
                            <th>{{ $loop->index + 1 }}</th>
                            <td>{{ $item->nim }}</td>
                            <td>{{ $item->nama_mahasiswa }}</td>
                            <td>{{ $item->tanggal_lahir }}</td>
                            <td>{{ $item->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                            <td>{{ $item->kelas->nama_kelas }}</td>
                            <td>
                                <a class="btn btn-secondary btn-sm px-4"
                                    href="{{ url('dashboard/kelas/' . $item->id . '') }}">Edit</a>
                                <a class="btn btn-danger btn-sm px-3"
                                    href="{{ url('dashboard/kelas/' . $item->id . '') }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
