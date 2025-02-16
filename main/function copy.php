<?php

$servername = "localhost"; // Sesuaikan dengan konfigurasi server Anda
$username = "root"; // Sesuaikan dengan username database
$password = ""; // Sesuaikan dengan password database
$dbname = "nama_database"; // Sesuaikan dengan nama database

//koneksi database
$conn = mysqli_connect($servername, $username, $password, $dbname);

if($conn){
	echo 'berhasil connected ke database';
}

//Menambah kategori baru
print_r($_POST);
if(isset($_POST['tambah_kategori'])){
	$kategoriNama = $_POST['nama_kategori'];
	$deskripsiKategori = $_POST['deskripsi_kategori'];
	$jenisKategori = $_POST['jenis_kategori'];

    $addtotable = mysqli_query($conn, "insert into kategoribarang (kategoriNama, deskripsiKategori, jenisKategori) values ('$kategoriNama','$deskripsiKategori','$jenisKategori')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo "Gagal: " . mysqli_error($conn);
        header('location:index.php');
    }
}


?> 


<?php

$servername = "localhost"; // Sesuaikan dengan konfigurasi server Anda
$username = "root"; // Sesuaikan dengan username database
$password = ""; // Sesuaikan dengan password database
$dbname = "inventory_master"; // Sesuaikan dengan nama database

// Koneksi database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
} else {
    echo 'Berhasil connected ke database';
}

// Menambah kategori baru
if (isset($_POST['tambah_kategori'])) {
    $kategoriNama = $_POST['nama_kategori'];
    $deskripsiKategori = $_POST['deskripsi_kategori'];
    $jenisKategori = $_POST['jenis_kategori'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO kategoribarang (kategoriNama, deskripsiKategori, jenisKategori) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $kategoriNama, $deskripsiKategori, $jenisKategori);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Gagal: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>
