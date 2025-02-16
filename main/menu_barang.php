<?php
include 'function.php'; // Pastikan koneksi database tersedia

// Cek koneksi database
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses Tambah Barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_barang'])) {
    $namaBarang = trim($_POST['nama_barang']);
    $deskripsiBarang = trim($_POST['deskripsi_barang']);
    $jumlah = (int) $_POST['jumlah'];
    $kondisiBarang = trim($_POST['kondisi_barang']);
    $kategoriID = (int) $_POST['kategoriID'];

    if (!empty($namaBarang) && !empty($kategoriID)) {
        $query = "INSERT INTO databarang (namaBarang, deskripsiBarang, jumlah, kondisiBarang, kategoriID) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssisi", $namaBarang, $deskripsiBarang, $jumlah, $kondisiBarang, $kategoriID);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: menu_barang.php?success=" . urlencode("Barang berhasil ditambahkan"));
            exit();
        } else {
            echo "Gagal menambahkan barang: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Nama Barang dan Kategori wajib diisi!";
    }
}

// Proses Edit Barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_barang'])) {
    $kodeBarang = (int) $_POST['kodeBarang'];
    $namaBarang = trim($_POST['namaBarang']);
    $deskripsiBarang = trim($_POST['deskripsiBarang']);
    $jumlah = (int) $_POST['jumlah'];
    $kondisiBarang = trim($_POST['kondisiBarang']);
    $kategoriID = (int) $_POST['kategoriID'];

    if (!empty($namaBarang) && !empty($kategoriID)) {
        $query = "UPDATE databarang SET namaBarang=?, deskripsiBarang=?, jumlah=?, kondisiBarang=?, kategoriID=? 
                  WHERE kodeBarang=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssisii", $namaBarang, $deskripsiBarang, $jumlah, $kondisiBarang, $kategoriID, $kodeBarang);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: menu_barang.php?update=" . urlencode("Barang berhasil diperbarui"));
            exit();
        } else {
            echo "Gagal mengupdate barang: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Proses Hapus Barang
if (isset($_GET['delete'])) {
    $kodeBarang = (int) $_GET['delete'];

    // Hapus data terkait di tabel requestbarang terlebih dahulu
    $deleteRelatedQuery = "DELETE FROM requestbarang WHERE kodeBarang=?";
    $stmtRelated = mysqli_prepare($conn, $deleteRelatedQuery);
    mysqli_stmt_bind_param($stmtRelated, "i", $kodeBarang);
    mysqli_stmt_execute($stmtRelated);
    mysqli_stmt_close($stmtRelated);

    // Hapus data di tabel databarang
    $deleteQuery = "DELETE FROM databarang WHERE kodeBarang=?";
    $stmtDelete = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmtDelete, "i", $kodeBarang);

    if (mysqli_stmt_execute($stmtDelete)) {
        // Redirect tanpa parameter ?delete=
        header("Location: menu_barang.php");
        exit();
    } else {
        echo "Gagal menghapus barang: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmtDelete);
}



// Fetch data barang untuk tabel
$query = "SELECT databarang.*, kategoriBarang.kategoriNama 
          FROM databarang 
          LEFT JOIN kategoriBarang ON databarang.kategoriID = kategoriBarang.idKategori";
$result = mysqli_query($conn, $query);
?>





<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kelola Barang - Inventory</title>
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
                            Kelola Barang
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Kelola Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Barang</li>
                    </ol>

                    <button type="button" class="btn btn-dark mb-3" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Barang +
                    </button>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Data Barang
                        </div>
                        <div class="card-body">
                        <table id="datatablesSimple">
                        <thead>
    <tr>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Deskripsi</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Kategori</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
        <td><?php echo $row['kodeBarang']; ?></td>
        <td><?php echo $row['namaBarang']; ?></td>
        <td><?php echo $row['deskripsiBarang']; ?></td>
        <td><?php echo $row['jumlah']; ?></td>
        <td><?php echo $row['kondisiBarang']; ?></td>
        <td><?php echo $row['kategoriNama']; ?></td>
        <td>
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['kodeBarang']; ?>">
                Edit
            </button>
            <a href="menu_barang.php?delete=<?php echo $row['kodeBarang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">
                Hapus
            </a>

        </td>
    </tr>
    <?php } ?>
</tbody>

</table>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="menu_barang.php">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Nama Barang</span>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Deskripsi</span>
                        <input type="text" name="deskripsi_barang" class="form-control" required>
                    </div>

                    <!-- Dropdown Pilih Kategori -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Kategori</span>
                        <select name="kategoriID" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $kategoriResult = mysqli_query($conn, "SELECT * FROM kategoriBarang");
                            while ($kategori = mysqli_fetch_assoc($kategoriResult)) {
                                echo "<option value='{$kategori['idKategori']}'>{$kategori['kategoriNama']}</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="input-group mb-3">
                        <span class="input-group-text">Jumlah</span>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Kondisi</span>
                        <input type="text" name="kondisi_barang" class="form-control" required>
                    </div>
                    <button type="submit" name="tambah_barang" class="btn btn-dark">Submit</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$result = mysqli_query($conn, "SELECT * FROM databarang");
while ($row = mysqli_fetch_assoc($result)) {
?>
<!-- Modal Edit Barang -->
<div class="modal fade" id="editModal<?php echo $row['kodeBarang']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="menu_barang.php">
                <div class="modal-body">
                    <input type="hidden" name="kodeBarang" value="<?php echo $row['kodeBarang']; ?>">

                    <div class="input-group mb-3">
                        <span class="input-group-text">Nama Barang</span>
                        <input type="text" name="namaBarang" class="form-control" value="<?php echo $row['namaBarang']; ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Deskripsi</span>
                        <input type="text" name="deskripsiBarang" class="form-control" value="<?php echo $row['deskripsiBarang']; ?>" required>
                    </div>

                    <!-- Dropdown Pilih Kategori (Edit) -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Kategori</span>
                        <select name="kategoriID" class="form-control" required>
                            <?php
                            $kategoriResult = mysqli_query($conn, "SELECT * FROM kategoriBarang");
                            while ($kategori = mysqli_fetch_assoc($kategoriResult)) {
                                $selected = ($row['kategoriID'] == $kategori['idKategori']) ? 'selected' : '';
                                echo "<option value='{$kategori['idKategori']}' $selected>{$kategori['kategoriNama']}</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="input-group mb-3">
                        <span class="input-group-text">Jumlah</span>
                        <input type="number" name="jumlah" class="form-control" value="<?php echo $row['jumlah']; ?>" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Kondisi</span>
                        <input type="text" name="kondisiBarang" class="form-control" value="<?php echo $row['kondisiBarang']; ?>" required>
                    </div>

                    <button type="submit" name="edit_barang" class="btn btn-warning">Update</button>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
