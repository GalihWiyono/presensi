<script type='text/javascript'>
    console.log("script dosen running");

    $(document).on('click', '#deleteBtn', function() {
        let id = $(this).attr('data-id');
        let matkul = $(this).attr('data-matkul');
        let kelas = $(this).attr('data-kelas');
        $('#id_delete').val(id);
        $('#matkul_delete').val(matkul);
        $('#kelas_delete').val(kelas);
    });

    $(document).on('click', '#editBtn', function() {
        let id = $(this).attr('data-id');
        let matkul = $(this).attr('data-matkul');
        let kelas = $(this).attr('data-kelas');
        let dosen = $(this).attr('data-dosen');
        let tanggal_mulai = $(this).attr('data-tanggal-mulai');
        let jam_mulai = $(this).attr('data-jam-mulai');
        let jam_berakhir = $(this).attr('data-jam-berakhir');
        $('#id_edit').val(id);
        $('#matkul_edit').val(matkul);
        $('#kelas_edit').val(kelas);
        $('#dosen_edit').val(dosen);
        $('#tanggal_mulai_edit').val(tanggal_mulai);
        $('#jam_mulai_edit').val(jam_mulai);
        $('#jam_berakhir_edit').val(jam_berakhir);
    });

    $(document).on('click', '#downloadBtn', function() {
        let id = $(this).attr('data-id');
        $('#jadwal_id').val(id);
    });

    $('#download_type').on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var jadwal_id = $('#jadwal_id').val();
        if(valueSelected == "PDF") {
            $('#formDownload').attr('action', '/dashboard/academic/schedule/'+jadwal_id+'/pdf');
        } else {
            $('#formDownload').attr('action', '/dashboard/academic/schedule/'+jadwal_id+'/excel');
        }
    });

    $(document).ready(function() {
        if ($('#toastNotification').hasClass("show")) {
            if ($('#toast-header').hasClass("text-success")) {
                setTimeout(function() {
                    $('#toastNotification').toast('hide');
                }, 2000);
            }

            if ($('#toast-header').hasClass("text-danger")) {
                setTimeout(function() {
                    $('#toastNotification').toast('hide');
                }, 10000);
            }
        }
    });
</script>
