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
        $('#course').attr('action', '/dashboard/academic/course/' + id);

        $("#title-course").text("Edit Course");
        $("#send-course").text("Edit Course");
        $("#send-course").attr('class', 'btn btn-warning');
    });

    $(document).on('click', '#reset-course', function() {
        $('#course').attr('action', '/dashboard/academic/course/');
        $("#title-course").text("Add Course");
        $("#send-course").text("Add Course");
        $("#send-course").attr('class', 'btn btn-primary');
    });
</script>
