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

    $(document).on('click', '#changePassword', function() {
        let user_id = $(this).attr('data-user-id');
        let nim = $(this).attr('data-nim');
        $('#nim_password').val(nim);
        $('#user_id_password').val(user_id);
    });

    $(document).on('click', '#togglePasword', function() {
        let type = $("#admin_password").attr('type');
        console.log(type);
        if (type == "password") {
            $("#admin_password").prop("type", "text");
        } else {
            $("#admin_password").prop("type", "password");
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
