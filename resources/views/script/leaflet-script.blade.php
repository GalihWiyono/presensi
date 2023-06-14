<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
    integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="https://cdn.rawgit.com/hayeswise/Leaflet.PointInPolygon/v1.0.0/wise-leaflet-pip.js"></script>

<script type='text/javascript'>
    let map, markers = [];
    var popup = L.popup();

    function initMap() {
        map = L.map('map', {
            center: {
                lat: -6.371713048673189,
                lng: 106.82387449539353
            },
            zoom: 18
        });

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        L.Control.geocoder().addTo(map);
    }

    initMap();
    getLocation();

    function getLocation() {
        console.log("get Location called");
        if (!navigator.geolocation) {
            console.log("Your browser doesn't support geolocation feature!")
        } else {
            if (sessionStorage.getItem("statusKelas") != "Online") {
                navigator.geolocation.getCurrentPosition(getPosition, positionError)
            }
        };
    }

    var marker, circle, lat, long, accuracy;

    function getPosition(position) {
        // console.log(position)
        lat = position.coords.latitude
        long = position.coords.longitude
        accuracy = position.coords.accuracy

        if (marker) {
            map.removeLayer(marker)
        }

        if (circle) {
            map.removeLayer(circle)
        }

        marker = L.marker([lat, long])
        circle = L.circle([lat, long], {
            radius: accuracy
        })

        var featureGroup = L.featureGroup([marker, circle]).addTo(map)

        map.fitBounds(featureGroup.getBounds())

        console.log("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy)

        var check = checkIfInsidePolygon(marker);
        window.onload = function() {
            if (!check) {
                if (window.jQuery) {
                    sessionStorage.setItem("statusKelas", null)
                    sessionStorage.setItem("location", null);
                    $('#lokasiOutsideModal').modal('show');
                }
            } else {
                console.log('make session');
                sessionStorage.setItem("statusKelas", 'Offline')
                var lokasi = {
                    'Lat': lat,
                    'Long': long
                }
                sessionStorage.setItem("location", JSON.stringify(lokasi));
                $('#statusBtn').removeClass('btn-outline-dark');
                $('#statusBtn').addClass('btn-dark');
                $("#statusBtn").html('Offline');
            }
        }
    }

    function positionError() {
        window.onload = function() {
            console.log("onload");
            var link = '';
            switch (platform.name) {
                case "Chrome":
                    link = "https://support.google.com/chrome/answer/142065?hl=id&co=GENIE.Platform%3DAndroid"
                    break;
                case "Firefox":
                    link =
                        "https://support.mozilla.org/en-US/kb/permissions-manager-give-ability-store-passwords-set-cookies-more?redirectslug=how-do-i-manage-website-permissions&redirectlocale=en-US#w_how-do-i-manage-permissions-for-a-single-website"
                    break;
                case "Edge":
                    link =
                        "https://support.microsoft.com/id-id/microsoft-edge/lokasi-dan-privasi-di-microsoft-edge-31b5d154-0b1b-90ef-e389-7c7d4ffe7b04"
                    break;
                case "Safari":
                    link = "https://support.apple.com/id-id/HT207092"
                    break;
                default:
                    link = "https://support.google.com/chrome/answer/142065?hl=id&co=GENIE.Platform%3DAndroid"
            }
            if (window.jQuery) {
                console.log("run modal");
                $("#lokasiLink").attr("href", link);
                $('#lokasiModal').modal('show');
            }
        }
    }

    var latlngs = [
        [-6.370732092011403, 106.82317715080158],
        [-6.370700104262608, 106.8239118615445],
        [-6.371158595138264, 106.82394940150945],
        [-6.37122257057671, 106.82319860221016]
    ];

    var polygon1 = L.polygon(latlngs, {
        color: 'red'
    }).addTo(map);

    var latlngs2 = [
        [-6.372299489260075, 106.82378854256548],
        [-6.372299489260075, 106.82410495084163],
        [-6.372715328542758, 106.824158579363],
        [-6.372704666001258, 106.82379390541763]
    ];

    var polygon2 = L.polygon(latlngs2, {
        color: 'red'
    }).addTo(map);

    //test
    var latlngsTest = [
        [-6.212544497743618, 106.59501766365489],
        [-6.212533831914748, 106.59541451471308],
        [-6.212939133259963, 106.59546814323448],
        [-6.212912468707373, 106.59500693795059]
    ];

    var polygonTest = L.polygon(latlngsTest, {
        color: 'red'
    }).addTo(map);
    //test

    map.on('click', onMapClick);

    function onMapClick(e) {
        var marker = L.marker(e.latlng).addTo(map);
        var check = checkIfInsidePolygon(marker);
        console.log("Log in onMapCLick: " + check);
        console.log(e.latlng);
    }

    function checkIfInsidePolygon(marker) {
        var check = (polygon1.contains(marker.getLatLng()) || polygon2.contains(marker.getLatLng())) ? true : false;
        var check = (polygonTest.contains(marker.getLatLng())) ? true : false;
        console.log(check);
        return check;
    }

    $(document).on('click', '#btnOnline', function() {
        sessionStorage.setItem("statusKelas", "Online")
        sessionStorage.setItem("location", "Online");
        console.log('session set to online');
        console.log(sessionStorage.getItem("statusKelas"));
        $('#statusBtn').addClass('btn-outline-dark');
        $('#statusBtn').removeClass('btn-dark');
        $("#statusBtn").html('Online');
        location.reload();
    });

    $(document).on('click', '#statusBtn', function() {
        if (sessionStorage.getItem("statusKelas") == "Online") {
            $('#statusKelasOfflineModal').modal('show');
        } else {
            $('#statusKelasOnlineModal').modal('show');
        }
    });

    $(document).on('click', '#btnChangeToOnline', function() {
        console.log("btn change to online clicked");
        $("#btnOnline").click()
    });


    $(document).on('click', '#btnChangeToOffline', function() {
        console.log("btn change to offline clicked");
        sessionStorage.setItem("statusKelas", "Offline");
        location.reload();
    });
</script>
