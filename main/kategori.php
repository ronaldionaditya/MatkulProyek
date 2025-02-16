<?php
include 'function.php';

// Proses Tambah Kategori
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi_kategori = $_POST['deskripsi_kategori'];
    $jenis_kategori = $_POST['jenis_kategori'];
    mysqli_query($conn, "INSERT INTO kategoribarang (kategoriNama, deskripsiKategori, jenisKategori) VALUES ('$nama_kategori', '$deskripsi_kategori', '$jenis_kategori')");
    header("Location: kategori.php");
    exit();
}

// Proses Edit Kategori
if (isset($_POST['edit_kategori'])) {
    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];
    $deskripsi_kategori = $_POST['deskripsi_kategori'];
    $jenis_kategori = $_POST['jenis_kategori'];
    mysqli_query($conn, "UPDATE kategoribarang SET kategoriNama='$nama_kategori', deskripsiKategori='$deskripsi_kategori', jenisKategori='$jenis_kategori' WHERE idKategori='$id_kategori'");
    header("Location: kategori.php");
    exit();
}

// Ambil Data Kategori
$result = mysqli_query($conn, "SELECT * FROM kategoribarang");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kelola Kategori - Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.php">Start Bootstrap</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </nav>
    
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="kategori.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Kategori Barang
                        </a>
                        <a class="nav-link" href="menu_barang.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            List Barang
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Kelola Kategori</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kategori</li>
                    </ol>

                    <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Kategori +
                    </button>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Data Kategori
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Jenis Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?= $row['idKategori']; ?></td>
                                        <td><?= $row['kategoriNama']; ?></td>
                                        <td><?= $row['deskripsiKategori']; ?></td>
                                        <td><?= $row['jenisKategori']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['idKategori']; ?>">Edit</button>
                                            <a href="hapus_kategori.php?id=<?= $row['idKategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit<?= $row['idKategori']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Kategori</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_kategori" value="<?= $row['idKategori']; ?>">
                                                        <div class="mb-3">
                                                            <label>Nama Kategori</label>
                                                            <input type="text" name="nama_kategori" class="form-control" value="<?= $row['kategoriNama']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Deskripsi</label>
                                                            <input type="text" name="deskripsi_kategori" class="form-control" value="<?= $row['deskripsiKategori']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Jenis Kategori</label>
                                                            <input type="text" name="jenis_kategori" class="form-control" value="<?= $row['jenisKategori']; ?>" required>
                                                        </div>
                                                        <button type="submit" name="edit_kategori" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Kategori</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="kategori.php">
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text">Nama Kategori</span>
                            <input type="text" name="nama_kategori" class="form-control" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Deskripsi</span>
                            <input type="text" name="deskripsi_kategori" class="form-control" required>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Jenis Kategori</span>
                            <input type="text" name="jenis_kategori" class="form-control" required>
                        </div>
                        <button type="submit" name="tambah_kategori" class="btn btn-dark">Submit</button>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
