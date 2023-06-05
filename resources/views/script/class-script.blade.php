<script type='text/javascript'>
    console.log("script detail class running");

    $(document).on('click', '#reset-class', function() {
        $('#class').attr('action', '/dashboard/academic/class/');
        $("#title-class").text("Add Class");
        $("#send-class").text("Add Class");
        $("#send-class").attr('class', 'btn btn-primary');
    });

    $(document).on('click', '#presensiBtn', function() {
        let nim = $(this).attr('data-nim');
        $('#presensiModal').modal('show');
    });

</script>
