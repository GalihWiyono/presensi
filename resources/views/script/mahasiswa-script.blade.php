<script type='text/javascript'>
    console.log("script mahasiswa running");

    $(document).on('click', '#deleteBtn', function() {
        let id = $(this).attr('data-id');
        let user_id = $(this).attr('data-user-id');
        $('#nim_delete').val(id);
        $('#user_id_delete').val(user_id);
    });

    $(document).on('click', '#editBtn', function() {
        let user_id = $(this).attr('data-user-id');
        let nim = $(this).attr('data-nim');
        let nama = $(this).attr('data-nama');
        let tanggal = $(this).attr('data-tanggal');
        let gender = $(this).attr('data-gender');
        let kelas = $(this).attr('data-kelas');
        $('#nim_edit').val(nim);
        $('#nama_mahasiswa_edit').val(nama);
        $('#tanggal_lahir_edit').val(tanggal);
        $('#gender_edit').val(gender);
        $('#kelas_id_edit').val(kelas);
        $('#user_id_edit').val(user_id);
    });
</script>
