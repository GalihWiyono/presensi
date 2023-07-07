<script type='text/javascript'>
    console.log("script dosen running");

    $(document).on('click', '#deleteBtn', function() {
        let id = $(this).attr('data-id');
        let user_id = $(this).attr('data-user-id');
        $('#nip_delete').val(id);
        $('#user_id_delete').val(user_id);
    });

    $(document).on('click', '#editBtn', function() {
        let user_id = $(this).attr('data-user-id');
        let nip = $(this).attr('data-nip');
        let nama = $(this).attr('data-nama');
        let tanggal = $(this).attr('data-tanggal');
        let gender = $(this).attr('data-gender');
        $('#nip_edit').val(nip);
        $('#nama_dosen_edit').val(nama);
        $('#tanggal_lahir_edit').val(tanggal);
        $('#gender_edit').val(gender);
        $('#user_id_edit').val(user_id);
    });

    $(document).on('click', '#changePassword', function() {
        let user_id = $(this).attr('data-user-id');
        let nip = $(this).attr('data-nip');
        $('#nip_password').val(nip);
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
