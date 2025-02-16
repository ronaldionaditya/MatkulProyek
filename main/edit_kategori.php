<?php
include 'function.php'; // Pastikan file ini berisi koneksi ke database

// Pastikan ada ID yang diterima
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kategori berdasarkan ID
    $query = "SELECT * FROM kategoribarang WHERE idKategori = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Cek apakah data ditemukan
        if (!$row) {
            echo "<script>alert('Kategori tidak ditemukan!'); window.location='kategori.php';</script>";
            exit();
        }
    } else {
        echo "Query gagal: " . mysqli_error($conn);
        exit();
    }
} else {
    // Jika tidak ada ID yang dikirim, kembalikan ke kategori.php
    echo "<script>alert('ID tidak valid!'); window.location='kategori.php';</script>";
    exit();
}

// Proses update kategori
if (isset($_POST['update_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $deskripsi_kategori = mysqli_real_escape_string($conn, $_POST['deskripsi_kategori']);
    $jenis_kategori = mysqli_real_escape_string($conn, $_POST['jenis_kategori']);

    $update_query = "UPDATE kategoribarang SET 
                        kategoriNama = ?, 
                        deskripsiKategori = ?, 
                        jenisKategori = ? 
                    WHERE idKategori = ?";
    $stmt = mysqli_prepare($conn, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $nama_kategori, $deskripsi_kategori, $jenis_kategori, $id);
        mysqli_stmt_execute($stmt);

        echo "<script>alert('Kategori berhasil diperbarui!'); window.location='kategori.php';</script>";
        exit();
    } else {
        echo "Gagal memperbarui kategori: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Kategori</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control" value="<?= isset($row['kategoriNama']) ? $row['kategoriNama'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <input type="text" name="deskripsi_kategori" class="form-control" value="<?= isset($row['deskripsiKategori']) ? $row['deskripsiKategori'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Kategori</label>
                <input type="text" name="jenis_kategori" class="form-control" value="<?= isset($row['jenisKategori']) ? $row['jenisKategori'] : ''; ?>" required>
            </div>
            
            <!-- ✅ Tambahkan tombol Save -->
            <button type="submit" name="update_kategori" class="btn btn-success">Save</button>
            <a href="kategori.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
