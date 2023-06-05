<script type='text/javascript'>
    console.log("script detail kelas running");

    $('#kelas').on('change', function(e) {
        var select = $(this),
            form = select.closest('form');
        form.submit();
    });
</script>
