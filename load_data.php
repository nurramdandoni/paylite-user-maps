<?php
// Membuat koneksi ke database
$servername = "localhost"; // Ganti dengan nama host Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "daftar-sekolah"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mengambil data sekolah dari database berdasarkan kriteria dari formulir
$provinsi = $_GET['prov'];
$kabupaten = $_GET['kab'];
$kecamatan = $_GET['kec'];

$sql = "SELECT sekolah, lintang as latitude, bujur as longitude,npsn,alamat_jalan, paylite_use FROM sekolah WHERE propinsi LIKE '%$provinsi%' AND kabupaten_kota LIKE '%$kabupaten%' AND kecamatan LIKE '%$kecamatan%'";

$result = mysqli_query($conn, $sql);

$data = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Menutup koneksi
mysqli_close($conn);

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
