<?php
require 'function.php';

// Proses ketika tombol Setujui atau Reject ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $idRequest = $_POST['idRequest'];
        $updateQuery = "UPDATE requestbarang SET status_submit = 4 WHERE idRequest = '$idRequest'";
        mysqli_query($conn, $updateQuery);
    } 
    header("Location: index_staff.php"); // Redirect untuk menghindari resubmission
    exit();
}

// Ambil data dari tabel requestbarang dengan status_submit = 1
$query = "SELECT idRequest, kodeBarang, namaBarang, jumlah, alasan, tanggalRequest, status_submit FROM requestbarang WHERE status_submit = 2 ORDER BY tanggalRequest DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard - Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index_staff.php">Inventory</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
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
                        <a class="nav-link" href="401.php">
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
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>

                    <!-- Card Status Request -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <a href="index_staff.php" class="text-decoration-none">
                                <div class="card bg-light text-dark mb-4">
                                    <div class="card-body">Menunggu</div>
                                    <div class="card-footer text-dark">View Details <i class="fas fa-angle-right"></i></div>
                                </div>
                            </a>
                        </div>    
                        <div class="col-xl-3 col-md-6">
                            <a href="request_disetujui_staff.php" class="text-decoration-none">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Disetujui</div>
                                    <div class="card-footer text-white">View Details <i class="fas fa-angle-right"></i></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <a href="request_reject_staff.php" class="text-decoration-none">
                                <div class="card bg-light text-dark mb-4">
                                    <div class="card-body">Reject</div>
                                    <div class="card-footer text-dark">View Details <i class="fas fa-angle-right"></i></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <a href="request_selesai_staff.php" class="text-decoration-none">
                                <div class="card bg-light text-dark mb-4">
                                    <div class="card-body">Selesai</div>
                                    <div class="card-footer text-dark">View Details <i class="fas fa-angle-right"></i></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Request Barang -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Request Barang
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-striped table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Alasan</th>
                                            <th>Tanggal Request</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['kodeBarang']); ?></td>
                                            <td><?php echo htmlspecialchars($row['namaBarang']); ?></td>
                                            <td><?php echo $row['jumlah']; ?></td>
                                            <td><?php echo htmlspecialchars($row['alasan']); ?></td>
                                            <td><?php echo $row['tanggalRequest']; ?></td>
                                            <td>
                                                <?php 
                                                $status = $row['status_submit'];
                                                $badge = '';
                                                if ($status == 1) {
                                                    $badge = '<span class="badge bg-warning text-dark">Menunggu</span>';
                                                } elseif ($status == 2) {
                                                    $badge = '<span class="badge bg-primary">Disetujui</span>';
                                                } elseif ($status == 3) {
                                                    $badge = '<span class="badge bg-danger">Reject</span>';
                                                } elseif ($status == 4) {
                                                    $badge = '<span class="badge bg-success">Selesai</span>';
                                                }
                                                echo $badge;
                                                ?>
                                            </td>
                                        
                                        <td>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['idRequest']; ?>">Ambil Barang</button>
                                        </td>
                                        </tr>
                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="modalDetail<?php echo $row['idRequest']; ?>" tabindex="-1" aria-labelledby="modalDetailLabel<?php echo $row['idRequest']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Request Barang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Kode Barang:</strong> <?php echo htmlspecialchars($row['kodeBarang']); ?></p>
                                                    <p><strong>Nama Barang:</strong> <?php echo htmlspecialchars($row['namaBarang']); ?></p>
                                                    <p><strong>Jumlah:</strong> <?php echo $row['jumlah']; ?></p>
                                                    <p><strong>Alasan:</strong> <?php echo htmlspecialchars($row['alasan']); ?></p>
                                                    <p><strong>Tanggal Request:</strong> <?php echo $row['tanggalRequest']; ?></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form method="POST">
                                                        <input type="hidden" name="idRequest" value="<?php echo $row['idRequest']; ?>">
                                                        <button type="submit" name="approve" class="btn btn-success">Ambil Barang</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
