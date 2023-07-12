<script type='text/javascript'>
        $(document).on('click', '#addNewDate', function() {
        let id = $(this).attr('data-id');
        let new_date = $(this).attr('data-new-date');
        let start_absen = $(this).attr('data-start-absen');
        let end_absen = $(this).attr('data-end-absen');
        let start_class = $(this).attr('data-start-time');
        let end_class = $(this).attr('data-end-time');
        $('#id').val(id);
        $('#new_date').val(new_date);
        $('#jam_mulai').val(start_class);
        $('#jam_berakhir').val(end_class);
        $('#mulai_absen').val(start_absen);
        $('#akhir_absen').val(end_absen);
    });
</script>