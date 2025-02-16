<?php
require 'function.php';
session_start();

// Data dummy untuk login dengan role
$dummy_users = [
    ['username' => 'admin', 'password' => '123', 'role' => 'admin'],
    ['username' => 'staff', 'password' => '123', 'role' => 'staff']
];

// Cek jika tombol login ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Cek username dan password dengan data dummy
    $login_success = false;
    $user_role = '';
    
    foreach ($dummy_users as $user) {
        if ($user['username'] == $username && $user['password'] == $password) {
            $login_success = true;
            $user_role = $user['role'];
            break;
        }
    }

    if ($login_success) {
        $_SESSION['log'] = 'True';
        $_SESSION['role'] = $user_role;

        // Redirect sesuai role
        if ($user_role == 'admin') {
            header('location:index.php');
        } else if ($user_role == 'staff') {
            header('location:index_staff.php');
        }
        exit();
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - SB Admin</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="username" id="inputUsername" type="text" placeholder="Enter Username" required />
                                            <label for="inputUsername">Username</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button class="btn btn-primary" name="login">Login</button>
                                        </div>
                                    </form>
                                </div>
                               <!-- <div class="card-footer text-center py-3">
                                    <div class="small">role - username - password=
                                        <br>admin - admin - 123
                                        <br>staff - staff - 123
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
