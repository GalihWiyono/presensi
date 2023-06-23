<script type='text/javascript'>
    console.log("script detail kelas running");

    $('#kelas').on('change', function(e) {
        var select = $(this),
            form = select.closest('form');
        form.submit();
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

    $(document).on('click', '#editPresensi', function() {
        let sesi = $(this).attr('data-sesi');
        let nim = $(this).attr('data-nim');
        let nama = $(this).attr('data-nama');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/dashboard/academic/check",
            data: {
                nim: nim,
                sesi_id: sesi,
            },
            method: 'POST',
            success: function(response) {
                console.log(response);
                if (response.status == "Valid" && response.errorMessage == "") {
                    insertData(response.data);
                    $('#showDataMahasiswaModal').modal('show');
                } else {
                    $('#messageError').text(response.errorMessage)
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    function insertData(data) {
        $('#id_edit').val(data.id);
        $('#nim_edit').val(data.nim);
        $('#nama_edit').val(data.nama_mahasiswa);
        $('#status_edit').val(data.status);
        $('#waktu_edit').val(data.waktu_presensi);
        $('#waktu_hidden').val(data.waktu_presensi);
    }

    $('#status_edit').on('change', function(e) {
        var select = $("#status_edit").val();
        var time = $('#waktu_hidden').val();

        if (select == "Tidak Hadir" || select == "Izin") {
            $('#waktu_edit').prop('readonly', true);
            $('#waktu_edit').val("")
        } else {
            $('#waktu_edit').prop('readonly', false);
            $('#waktu_edit').val(time)
        }
    });
</script>
