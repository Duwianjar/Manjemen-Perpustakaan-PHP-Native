<?php
include_once("../dashboard/koneksi.php");
session_start();


// jika sudah login maka akan terlempar ke dashboard
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    $authId = $_SESSION['auth_id'];
    $query = "SELECT fullname FROM account WHERE id = $authId";
    $result = mysqli_query($con, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['success'] = "Anda sudah login sebagai <b>{$row['fullname']}</b>. Silakan logout terlebih dahulu untuk mengganti akun.";
    }

    header('Location: ../dashboard/home');
    exit();
}

// Mendaftarkan akun
if (isset($_POST['register'])) {
  $username = $_POST['user'];
  $fullname = $_POST['full'];
  $email = $_POST['email'];
  $password = md5($_POST['pass']);

  
  $error = false;
  $erruser = false;
  $erruser2 = false;
  $errfull = false;
  $errpass = false;

  if (strpos($username, ' ') !== false) {
    $error = true;
    $erruser = true;
  }
  if (preg_match('/[<>]/', $username) || preg_match('/[;\'"()|&%*$^]/', $username)) {
    $error = true;
    $erruser2 = true;
  }
  if (!preg_match('/^[a-zA-Z\s]+$/', $fullname)||preg_match('/[<>]/', $fullname) || preg_match('/[;\'"()|&%*$^]/', $fullname)){
    $error = true;
    $errfull = true;
  }
  if (preg_match('/[<>]/', $_POST['pass']) || preg_match('/[;\'"()|&%*$^]/', $_POST['pass']) || strpos($_POST['pass'], ' ') !== false) {
    $error = true;
    $errpass= true;
  }

  if($error===true){
    $_SESSION['errorr'] = "Ada kesalahan pada data yang diinputkan :";
    if($erruser===true){
      $_SESSION['errorr'] .= "<br> - Username tidak boleh mengandung spasi.";
    }
    if($erruser2===true){
      $_SESSION['errorr'] .= "<br> - Username tidak boleh mengandung karakter HTML atau Query SQL.";
    }
    if($errfull===true){
      $_SESSION['errorr'] .= "<br> - Nama lengkap hanya boleh mengandung huruf dan spasi saja.";
    }
    if($errpass===true){
      $_SESSION['errorr'] .= "<br> - Password tidak boleh mengandung spasi maupun karakter HTML atau Query SQL.";
    }
    echo '<script>window.location.href = "?register";</script>';
  }
  else {
    $resultz = mysqli_query($con, "INSERT INTO account (username, fullname, email, password) VALUES ('$username', '$fullname', '$email', '$password')");
    if ($resultz) {
      $_SESSION['success'] = "Berhasil menambah mendaftarkan akun silahkan login";
    } else {
      $_SESSION['errorr'] = "Gagal menambah data barang baru";
    }
  }
    
  
}



// Chek username dan password
if (isset($_POST['login'])) {
    if ($_POST["captcha_code"] === $_SESSION["captcha_code"]) {
        $query = "SELECT * FROM account";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            // Loop melalui hasil query
            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row['username'];
                $password = $row['password'];
                $enteredPassword = md5($_POST['pass']);
                if ($_POST['user'] === $username && $enteredPassword === $password) {
                    // Login berhasil, atur sesi atau cookie untuk menandai bahwa pengguna telah login
                    $_SESSION['loggedin'] = true;
                    // Redirect ke halaman dashboard.php
                    $_SESSION['login'] = true;
                    $_SESSION['auth_id'] = $row['id'];
                    $query = "SELECT * FROM account WHERE id = $row[id];";
                    $result = mysqli_query($con, $query);
                    $user = mysqli_fetch_assoc($result);
                    $name = $user['fullname'];
                    $_SESSION['success'] = "Selamat datang kembali sodara $name";
                    unset($_SESSION['error']);
                    header('Location: ../dashboard/home/');
                    exit();
                } else {
                    $_SESSION['error'] = 'Username atau password salah. Coba lagi.';
                }
            }
        } else {
            echo "Tidak ada data yang ditemukan.";
        }
    }
    else {
        $_SESSION['error'] = 'Login gagal! Captcha tidak sesuai <b>ULANGI LAGI</b>';
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Duwiaaw Library</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <!-- Other head elements -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512..." crossorigin="anonymous" />

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert alert-danger col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['error'] . '</div>';
                                    unset($_SESSION['error']);
                                }
                                if (isset($_SESSION['warning'])) {
                                    echo '<div class="alert alert-warning col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['warning'] . '</div>';
                                    unset($_SESSION['warning']);
                                }
                                if (isset($_SESSION['success'])) {
                                    echo '<div class="alert alert-success col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['success'] . '</div>';
                                    unset($_SESSION['success']);
                                }
                                if(isset($_GET['register'])){ 
                                  if (isset($_SESSION['errorr'])) {
                                    echo '<div class="alert alert-danger col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['errorr'] . '</div>';
                                    unset($_SESSION['errorr']);
                                  }
                                }?>
                                <h3 class="text-center">
                                    <img src="../assets/images/logos/favicon.png" width="40" alt="" class="mb-2">
                                    Duwiaaw Library
                                </h3>
                                <?php if(!isset($_GET['register'])){ ?>
                                <p class="text-center">Login untuk mengakses halaman dasboard</p>
                                <form role="form" method="post">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="exampleInputEmail1"
                                            aria-describedby="emailHelp" name="user" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                name="pass" required />
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="show-password-button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mb-3" style="display: flex; align-items: left;">
                                        <img id="captchaImage" style="flex: 1;" src="captcha.php" />
                                        <input style="flex: 2;" type="text" class="form-control input ml-2"
                                            name="captcha_code" placeholder="Ketik ulang isi captcha disini" required />
                                        <button style="flex: 0.5;" type="button" class="btn btn-dark ml-2"
                                            onclick="refreshCaptcha()">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <button name="login"
                                        class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Login</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a class="text-primary fw-bold ms-2" href="?register">Buat akun baru</a>
                                    </div>
                                </form>
                                <?php } else { ?>
                                <p class="text-center">Daftar sebagai pengguna baru</p>
                                <form role="form" method="post" action="./index.php">
                                    <div class="mb-3">
                                        <label for="exampleInputtext1" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="exampleInputtext1"
                                            aria-describedby="textHelp" name="user" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputtext1" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="exampleInputtext1"
                                            aria-describedby="textHelp" name="full" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Email </label>
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                            aria-describedby="emailHelp" name="email" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="exampleInputPassword1"
                                                name="pass" required />
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="show-password-button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button name="register"
                                        class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Daftar</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold">Sudah punya akun?<a
                                                class="text-primary fw-bold ms-2" href="?login">Login
                                            </a>
                                        </p>
                                    </div>
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const passwordInput = document.getElementById('exampleInputPassword1');
    const showPasswordButton = document.getElementById('show-password-button');

    showPasswordButton.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });

    function refreshCaptcha() {
        // Mendapatkan elemen gambar captcha
        var captchaImage = document.getElementById('captchaImage');

        // Menambahkan parameter timestamp untuk memaksa browser memuat ulang gambar captcha
        var timestamp = new Date().getTime();
        captchaImage.src = "captcha.php?timestamp=" + timestamp;
    }
    </script>
</body>

</html>