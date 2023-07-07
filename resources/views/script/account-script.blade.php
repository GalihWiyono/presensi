<script type='text/javascript'>
    console.log("script account mahasiswa running");

    $(document).on('click', '#togglePasword1', function() {
        let type = $("#old_password").attr('type');
        console.log(type);
        if (type == "password") {
            $("#old_password").prop("type", "text");
        } else {
            $("#old_password").prop("type", "password");
        }
    });

    $(document).on('click', '#togglePasword2', function() {
        let type = $("#new_password").attr('type');
        console.log(type);
        if (type == "password") {
            $("#new_password").prop("type", "text");
        } else {
            $("#new_password").prop("type", "password");
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
