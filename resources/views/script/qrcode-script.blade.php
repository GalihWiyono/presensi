<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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
            fps: 30,
            qrbox: qrboxFunction
        },
        /* verbose= */
        false);

    html5QrcodeScanner.render(onScanSuccess);

    function onScanSuccess(decodedText, decodedResult) {
        const dataKelas = JSON.parse(decodedText);
        insertData(dataKelas);
        $('#presensiModal').modal('show');
        $('#html5-qrcode-button-camera-stop').click();
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
    }

    function insertData(data) {
        $('#id').val(data.id);
        $('#mataKuliah').val(data.mataKuliah);
        $('#dosen').val(data.dosen);
        $('#kelas').val(data.kelas);
        $('#hari').val(data.hari);
        $('#jam').val(data.jam_mulai + " - " + data.jam_berakhir);
        $('#jam_presensi').val(data.mulai_absen + " - " + data.akhir_absen);
    }
</script>
