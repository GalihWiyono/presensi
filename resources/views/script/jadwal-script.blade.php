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
        let hari = $(this).attr('data-hari');
        let jam_mulai = $(this).attr('data-jam-mulai');
        let jam_berakhir = $(this).attr('data-jam-berakhir');
        $('#id_edit').val(id);
        $('#matkul_edit').val(matkul);
        $('#kelas_edit').val(kelas);
        $('#dosen_edit').val(dosen);
        $('#hari_edit').val(hari);
        $('#jam_mulai_edit').val(jam_mulai);
        $('#jam_berakhir_edit').val(jam_berakhir);

    });
</script>