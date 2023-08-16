<table>
    <tbody>
        <tr>
            <td>
                <span><b>Class</b></span>
            </td>
            <td>
                <span>: {{ $jadwal->kelas->nama_kelas }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span><b>Course</b></span>
            </td>
            <td>
                <span>: {{ $jadwal->matkul->nama_matkul }}</span>
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
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12</th>
            <th>13</th>
            <th>14</th>
            <th>15</th>
            <th>16</th>
            <th>17</th>
            <th>18</th>
            <th>Hadir</th>
            <th>Terlambat</th>
            <th>Izin
            <th>Alpa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <th>{{ $loop->index + 1 }}</th>
                <th>{{ $item->nim }}</th>
                <th>{{ $item->nama_mahasiswa }}</th>

                @for ($i = 0; $i < 18; $i++)
                    @if (isset($item->presensi[$i]) && $item->presensi[$i]->pekan == $i + 1)
                        <td>{{ $item->presensi[$i]->status }}</td>
                    @else
                        <td>-</td>
                    @endif
                @endfor
                <th>{{ $item->hadir }}</th>
                <th>{{ $item->terlambat }}</th>
                <th>{{ $item->izin }}</th>
                <th>{{ $item->tidakHadir }}</th>
            </tr>
        @endforeach
    </tbody>
</table>