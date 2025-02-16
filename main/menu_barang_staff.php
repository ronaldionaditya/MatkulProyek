<?php
include 'function.php'; // Pastikan koneksi database tersedia

// Proses Request Barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_barang'])) {
    date_default_timezone_set('Asia/Jakarta'); // Set zona waktu ke WIB
    $kodeBarang = trim($_POST['kodeBarang']); 
    $namaBarang = trim($_POST['namaBarang']);
    $jumlah = (int) $_POST['jumlah'];
    $alasan = trim($_POST['alasan']);
    $tanggalRequest = date("Y-m-d H:i:s"); // Waktu real-time
    $statusSubmit = 1; // Set langsung menjadi 1 setelah request diajukan

    if (!empty($kodeBarang) && $jumlah > 0 && !empty($alasan) && !empty($namaBarang)) {
        // Mulai transaksi untuk keamanan
        mysqli_begin_transaction($conn);

        try {
            // Insert data ke tabel requestbarang dengan status_submit = 1
            $query1 = "INSERT INTO requestbarang (kodeBarang, jumlah, namaBarang, alasan, tanggalRequest, status_submit) 
                       VALUES (?, ?, ?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($conn, $query1);
            mysqli_stmt_bind_param($stmt1, "sisssi", $kodeBarang, $jumlah, $namaBarang, $alasan, $tanggalRequest, $statusSubmit);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_close($stmt1);

            // Commit transaksi
            mysqli_commit($conn);

            header("Location: menu_barang_staff.php?request_success=" . urlencode("Permintaan barang berhasil diajukan"));
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback jika ada error
            echo "Gagal mengajukan permintaan barang: " . $e->getMessage();
        }
    } else {
        echo "Semua field wajib diisi dengan benar!";
    }
}

// Fetch data barang untuk tabel
$query = "SELECT d.*, k.kategoriNama 
          FROM databarang d 
          LEFT JOIN kategoriBarang k ON d.kategoriID = k.idKategori";
$result = mysqli_query($conn, $query);
?>



<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Barang - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index_staff.php">Inventory</a>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index_staff.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="kategori_staff.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Kategori Barang
                        </a>
                        <a class="nav-link" href="menu_barang_staff.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Data Barang
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index_staff.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Barang</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Data Barang
                        </div>
                        <div class="card-body">
    <div class="table-responsive">
        <table id="datatablesSimple" class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th style="width: 10%;">Kode Barang</th>
                    <th style="width: 15%;">Nama Barang</th>
                    <th style="width: 20%;">Deskripsi</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 15%;">Kondisi</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['kodeBarang']; ?></td>
                    <td><?php echo $row['namaBarang']; ?></td>
                    <td><?php echo $row['deskripsiBarang']; ?></td>
                    <td><?php echo $row['jumlah']; ?></td>
                    <td><?php echo $row['kondisiBarang']; ?></td>
                    <td><?php echo $row['kategoriNama']; ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm request-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#requestBarangModal"
                            data-kode="<?php echo $row['kodeBarang']; ?>"
                            data-nama="<?php echo $row['namaBarang']; ?>">
                            Request
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Request Barang -->
    <div class="modal fade" id="requestBarangModal" tabindex="-1" aria-labelledby="requestBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestBarangModalLabel">Request Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="menu_barang_staff.php" method="POST">
                        <div class="mb-3">
                            <label for="kodeBarang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control" id="kodeBarang" name="kodeBarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="namaBarang" name="namaBarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="request_barang" class="btn btn-primary">Kirim Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const requestButtons = document.querySelectorAll(".request-btn");
            requestButtons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("kodeBarang").value = this.getAttribute("data-kode");
                    document.getElementById("namaBarang").value = this.getAttribute("data-nama");
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
