<table>
    <tbody>
        <tr>
            <td>
                <span><b>Class</b></span>
            </td>
            <td>
                <span>: {{ $jadwal->first()->kelas->nama_kelas }}</span>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>NIM</th>
            <th>Nama Mahasiswa</th>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Izin
            <th>Alpa</th>
            <th>Total Kompensasi</th>
            <th>Ket. SP</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <th>{{ $loop->index + 1 }}</th>
                <th>{{ $item->nim }}</th>
                <th>{{ $item->nama_mahasiswa }}</th>
                <th>{{ $item->hadir }}</th>
                <th>{{ $item->terlambat }}</th>
                <th>{{ $item->izin }}</th>
                <th>{{ $item->tidakHadir }}</th>
                <th>{{ $item->total_kompensasi }} Menit</th>
                <th>{{ $item->sp }}</th>
            </tr>
        @endforeach
    </tbody>
</table>
