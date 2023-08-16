<script type='text/javascript'>
    console.log("script course running");

    $(document).on('click', '#deleteBtn', function() {
        let id = $(this).attr('data-id');
        let matkul = $(this).attr('data-matkul');
        $('#nama_matkul_delete').val(matkul);
        $('#id_matkul_delete').val(id);
    });

    $(document).on('click', '#editBtn', function() {
        let id = $(this).attr('data-id');
        let matkul = $(this).attr('data-matkul');
        $('#id_matkul').val(id)
        $('#nama_matkul').val(matkul);
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
