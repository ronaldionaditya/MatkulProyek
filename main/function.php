<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_master";

// Koneksi database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Tambah kategori baru
if (isset($_POST['tambah_kategori'])) {
    $kategoriNama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $deskripsiKategori = mysqli_real_escape_string($conn, $_POST['deskripsi_kategori']);
    $jenisKategori = mysqli_real_escape_string($conn, $_POST['jenis_kategori']);

    $sql = "INSERT INTO kategoribarang (kategoriNama, deskripsiKategori, jenisKategori) 
            VALUES ('$kategoriNama', '$deskripsiKategori', '$jenisKategori')";

    if (mysqli_query($conn, $sql)) {
        header('Location: kategori.php');
        exit();
    } else {
        echo "Gagal menambahkan kategori: " . mysqli_error($conn);
    }
}
?>
