<script type='text/javascript'>
    console.log("script detail kelas running");

    $(document).ready(function() {
        var sessionStatusKelas = sessionStorage.getItem("statusKelas");
        if (sessionStatusKelas == "Online") {
            $("#btnForPresensi").html('{{ __("Presence") }}');
            $("#btnForPresensi").attr("data-bs-target", "#presensiModal");
        } else {
            $("#btnForPresensi").html('{{ __("Generate QRCode") }}');
            $("#btnForPresensi").attr("data-bs-target", "#qrModal");
        }
    });

    $('#week').on('change', function(e) {
        var select = $(this),
            form = select.closest('form');
        form.submit();
    });

    $(document).on('click', '#editPresensi', function() {
        let nim = $(this).attr('data-nim');
        let sesi_id = $(this).attr('data-sesi');
        let nama = $(this).attr('data-nama');
        let status = $(this).attr('data-status');
        let waktu = $(this).attr('data-waktu');
        let [hours, mins] = waktu.split(":");
        $('#sesi_edit').val(sesi_id);
        $('#nim_edit').val(nim);
        $('#nama_mahasiswa_edit').val(nama);
        $('#status_edit').val(status);
        $('#waktu_presensi_edit').val(hours + ":" + mins);
        $('#waktu_presensi_hidden').val(hours + ":" + mins);
    });

    $('#status_edit').on('change', function(e) {
        var select = $("#status_edit").val();
        var time = $('#waktu_presensi_hidden').val();

        if (select == "Tidak Hadir" || select == "Izin") {
            $('#waktu_presensi_edit').prop('readonly', true);
            $('#waktu_presensi_edit').val("")
        } else {
            $('#waktu_presensi_edit').prop('readonly', false);
            $('#waktu_presensi_edit').val(time);
        }
    });

    $('#status_show').on('change', function(e) {
        var select = $("#status_show").val();

        if (select == "Tidak Hadir" || select == "Izin") {
            $('#waktu_presensi_show').prop('readonly', true);
            $('#waktu_presensi_show').val("")
        } else {
            $('#waktu_presensi_show').prop('readonly', false);
        }
    });

    $(document).on('click', '#searchNim', function() {
        $('#searchNim').attr("disabled", true);
        let nim = $('#nim_search').val();
        let sesi = $('#sesi_id_search').val();
        let kelas = $('#kelas_id_search').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/dashboard/kelas/check",
            data: {
                nim: nim,
                sesi_id: sesi,
                kelas_id: kelas
            },
            method: 'POST',
            success: function(response) {
                console.log(response);
                if (response.status == "Valid" && response.errorMessage == "") {
                    $('#addPresensiModal').modal('hide');
                    $('#nim_search').val('');
                    $('#searchNim').attr("disabled", false);
                    insertAddPresensiData(response.data);
                    $('#showDataMahasiswaModal').modal('show');
                } else {
                    $('#nim_search').addClass('is-invalid');
                    $('#messageError').text(response.errorMessage)
                    $('#searchNim').attr("disabled", false);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#addPresensiModal').on('hidden.bs.modal', function() {
        $('#nim_search').removeClass('is-invalid');
        $(this).find('form').trigger('reset');
    })

    function insertAddPresensiData(data) {
        $('#nim_show').val(data.nim);
        $('#nama_show').val(data.nama);
        $('#kelas_show').val(data.kelas);
    }

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
