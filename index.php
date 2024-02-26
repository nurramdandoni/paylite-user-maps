<!DOCTYPE html>
<html>
<head>
    <title>Peta Sekolah di Indonesia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 670px;
        }
        #loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
            display: none;
            text-align: center;
        }
        
    </style>
</head>
<body>

<div id="map"></div>
<div id="count">
    <form id="filterForm">
        <div>Provinsi : <input type="text" id="prov" name="prov" value="Jawa Barat"> Kabupaten : <input type="text" id="kab" name="kab" value="Kuningan">Kecamatan : <input type="text" id="kec" name="kec" value=""> <input type="submit" value="Tampilkan!"> <span id="jumlahSekolah">300</span></div>
    </form>
</div>
<div id="loading">
    <p>--- Loading, harap tunggu ---</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var map = L.map('map').setView([-0.789275, 113.921327], 5); // Set the initial map center and zoom level

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    function showLoading() {
        $("#loading").show();
        $("#map, #count").css("opacity", "0.3");
    }

    function hideLoading() {
        $("#loading").hide();
        $("#map, #count").css("opacity", "1");
    }

    function loadData() {
        showLoading();
        var formData = $('#filterForm').serialize();
        $.ajax({
            type: 'GET',
            url: 'load_data.php', // Ganti dengan URL script PHP untuk memuat data
            data: formData,
            success: function(response) {
                hideLoading();
                console.log(response.length);
                $("#jumlahSekolah").text(response.length+" Sekolah");
                // Hapus semua marker yang ada
                map.eachLayer(function(layer) {
                    if (layer instanceof L.Marker) {
                        map.removeLayer(layer);
                    }
                });
                // Tambahkan marker baru berdasarkan data yang diterima dari server
                response.forEach(function(data) {
                    console.log(data);
                    var markerColor = 'red'; // Warna default
                    if (data.paylite_use == 'Y') {
                        markerColor = 'blue'; // Ganti warna menjadi merah jika fieldTertentu adalah 'Y'
                    }
                    var marker = L.marker([data.latitude, data.longitude],{icon: getColorMarker(markerColor)}).addTo(map);
                    marker.bindPopup(data.sekolah+"<br> NPSN : "+data.npsn+"<br> Maps : "+data.latitude+","+data.longitude+"<br> Alamat : "+data.alamat_jalan);
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function getColorMarker(color) {
    return L.icon({
        iconUrl: 'http://localhost/pengguna_paylite/' + color + '.png',
        iconSize: [25, 41], // ukuran marker
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
    });
}

    // Tangani submit form
    $('#filterForm').submit(function(event) {
        event.preventDefault(); // Mencegah pengiriman form secara default
        loadData(); // Panggil fungsi untuk memuat data
    });

    // Memuat data pertama kali
    loadData();
</script>

</body>
</html>
