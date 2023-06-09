<script type='text/javascript'>
    console.log("script class running");

    $(document).on('click', '#deleteBtn', function() {
        let id = $(this).attr('data-id');
        let kelas = $(this).attr('data-kelas');
        $('#nama_kelas_delete').val(kelas);
        $('#id_kelas_delete').val(id);
    });

    $(document).on('click', '#editBtn', function() {
        let id = $(this).attr('data-id');
        let kelas = $(this).attr('data-kelas');
        $('#id_kelas').val(id)
        $('#nama_kelas').val(kelas);
        $('#class').attr('action', '/dashboard/academic/class/' + id);

        $("#title-class").text("Edit Class");
        $("#send-class").text("Edit Class");
        $("#send-class").attr('class', 'btn btn-warning');
    });

    $(document).on('click', '#reset-class', function() {
        $('#class').attr('action', '/dashboard/academic/class/');
        $("#title-class").text("Add Class");
        $("#send-class").text("Add Class");
        $("#send-class").attr('class', 'btn btn-primary');
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
