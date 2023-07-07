<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>


<script type='text/javascript'>
    console.log('script running')

    let qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
        let minEdgePercentage = 0.60; // 60%
        let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
        let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
        return {
            width: qrboxSize,
            height: qrboxSize
        };
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 500,
            qrbox: qrboxFunction
        },
        /* verbose= */
        false);

    html5QrcodeScanner.render(onScanSuccess);

    function onScanSuccess(decodedText, decodedResult) {
        const dataKelas = JSON.parse(decodedText);
        verifyQR(dataKelas);
        $('#html5-qrcode-button-camera-stop').click();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
    }

    function insertData(data) {
        $('#id').val(data.id);
        $('#sesi_id').val(data.sesi_id);
        $('#mataKuliah').val(data.matkul);
        $('#dosen').val(data.dosen);
        $('#kelas').val(data.kelas);
        $('#pekan').val("Pekan " + data.pekan + " - " + data.tanggal);
        $('#jam').val(data.jam_mulai + " - " + data.jam_berakhir);
        $('#jam_presensi').val(data.mulai_absen + " - " + data.akhir_absen);

        $("#presensiBtn").prop("disabled", checkWaktu(data));
    }

    function checkWaktu(data) {
        var format = 'hh:mm:ss'
        var currentTime = moment(),
            beforeTime = moment(data.mulai_absen, format),
            afterTime = moment(data.akhir_absen, format)

        if (moment(data.tanggal).isSame(moment(), 'day') && currentTime.isBetween(beforeTime, afterTime)) {
            return false;
        }
        return false;
    }

    function verifyQR(data) {
        console.log("verify");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/dashboard/presensi/check",
            data: {
                id: data.id,
                unique: data.unique,
            },
            method: 'POST',
            success: function(response) {
                console.log(response);
                if (response.status == "Valid") {
                    insertData(response.data);
                    $('#presensiModal').modal('show');
                } else {
                    $('#qrInvalid').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
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
