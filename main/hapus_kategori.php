<?php
include 'function.php'; // Pastikan file koneksi tersedia

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus kategori berdasarkan ID
    $query = "DELETE FROM kategoribarang WHERE idKategori = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('Kategori berhasil dihapus!');
                window.location.href='kategori.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus kategori!');
                window.location.href='kategori.php';
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "<script>
            alert('ID kategori tidak ditemukan!');
            window.location.href='kategori.php';
          </script>";
}
?>
