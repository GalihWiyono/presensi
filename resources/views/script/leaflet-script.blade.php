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

    if (!navigator.geolocation) {
        console.log("Your browser doesn't support geolocation feature!")
    } else {
        navigator.geolocation.getCurrentPosition(getPosition, positionError)
    };

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

        checkIfInsidePolygon(marker);
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

    var latlngsTest = [
        [-6.212544497743618, 106.59501766365489],
        [-6.212533831914748, 106.59541451471308],
        [-6.212939133259963, 106.59546814323448],
        [-6.212912468707373, 106.59500693795059]
    ];

    var polygonTest = L.polygon(latlngsTest, {
        color: 'red'
    }).addTo(map);

    map.on('click', onMapClick);

    function onMapClick(e) {
        var marker = L.marker(e.latlng).addTo(map);
        checkIfInsidePolygon(marker);
        console.log(e.latlng);
    }

    function checkIfInsidePolygon(marker) {
        var check = (polygon1.contains(marker.getLatLng()) || polygon2.contains(marker.getLatLng())) ? true : false;
        console.log(check);
        // if (!check) {
        //     $('#lokasiOutsideModal').modal('show');
        // }
        return check;
    }
</script>
