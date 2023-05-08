<script type='text/javascript'>
    console.log("script detail kelas running");

    $('#sesiSelect').on('change', function(e) {
        var select = $(this),
            form = select.closest('form');
        form.append('<input type="hidden" id="sesi" name="sesi" value="'+select.val()+'" />');
        form.submit();
    });
</script>
