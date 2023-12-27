<?php
session_start();

if (isset($_GET['cetak'])) {
    include_once("../koneksi.php");
    // memanggil library FPDF
    require('../fpdf/fpdf.php');
    // intance object dan memberikan pengaturan halaman PDF
    $pdf = new FPDF('l', 'mm', 'A5');
    // membuat halaman baru
    $pdf->AddPage();
    // setting jenis font yang akan digunakan
    $pdf->SetFont('Arial', 'B', 16);
    // mencetak string
    $pdf->Cell(190, 7, 'Duwiaaw Library', 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, 'DAFTAR DATA ANGGOTA', 0, 1, 'C');
    // Memberikan space kebawah agar tidak terlalu rapat
    $pdf->Cell(10, 7, '', 0, 1);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 6, 'ID', 1, 0, 'C');
    $pdf->Cell(40, 6, 'Nama Lengkap', 1, 0, );
    $pdf->Cell(35, 6, 'Nomer Telepon', 1, 0,);
    $pdf->Cell(30, 6, 'Gender', 1, 0, 'C');
    $pdf->Cell(70, 6, 'Alamat', 1, 1, );
    $pdf->SetFont('Arial', '', 10);
    $data_member = mysqli_query($con, "select * from member");
    $hitung=0;
    while ($row = mysqli_fetch_array($data_member)) {

        $tinggi_sel = ceil($pdf->GetStringWidth($row['address']) / 70) * 6;
        
        $pdf->Cell(15, $tinggi_sel, $row['id'], 1, 0, 'C');
        $pdf->Cell(40, $tinggi_sel, $row['fullname'], 1, 0);
        $pdf->Cell(35, $tinggi_sel, $row['phone_number'], 1, 0,);
        $pdf->Cell(30, $tinggi_sel, $row['gender'], 1, 0, 'C' );
        $pdf->Multicell(70, 6, $row['address'], 1, 1);
        $hitung++;
        if($hitung%13==0){
            $pdf->SetY(118);
            // Select Arial italic 8
            $pdf->SetFont('Arial', 'I', 8);
            // Print centered page number
            $pdf->Cell(0, 10, 'Page '.$pdf->PageNo(), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 10);
        }
    }
    $pdf->Output();
}
else {
$_SESSION['navigation'] = "2";
include_once("../navbar.php");


if (isset($_POST['create'])) {
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone_number = $_POST['country_code'] . $_POST['phone_number'];
    $gender = $_POST['gender'];

    $allowedPhotoExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
    $uploadedExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    $error=false;
    $errfullname = false;
    $erraddress = false;
    $errphone = false;
    $errphoto = false;

    // Validasi Inputan 
    if (!preg_match('/^[a-zA-Z\s.]+$/', $fullname) || preg_match('/[<>]/', $fullname) || preg_match('/[;\'"()|&%*$^]/', $fullname)) {
        $error = true;
        $errfullname = true;
    }
    if (preg_match('/[<>]/', $address) || preg_match('/[;\'"()|&%*$^]/', $address)){
        $error = true;
        $erraddress= true;
    }
    if (strlen($phone_number) > 15) {
        $error = true;
        $errphone = true;
    }

    if (!in_array($uploadedExtension, $allowedPhotoExtensions)) {
        $error = true;
        $errphoto = true;
    }
    // =========================
    // Check Hasil validasi
    if($error===true){
        $_SESSION['error'] = "Ada kesalahan pada data yang diinputkan :";
        if($errfullname===true){
            $_SESSION['error'] .= "<br> - Nama lengkap hanya boleh mengandung huruf, spasi dan titik saja.";
        }
        if($erraddress===true){
            $_SESSION['error'] .= "<br> - Alamat tidak boleh mengandung karakter HTML atau Query SQL.";
        }
        if($errphone===true){
            $_SESSION['error'] .= "<br> - Nomer telepon tidak boleh lebih dari 15 digit ";
        }
        if($errphoto===true){
            $_SESSION['error'] .= "<br> - Ekstensi file foto tidak diizinkan. Silakan upload file dengan ekstensi: " . implode(', ', $allowedPhotoExtensions); "";
        }
        echo '<script type="text/javascript">window.location.href = "../member/?tambah";</script>';
    } 
    else {
        // Upload photo
        $targetDirectory = "../../assets/images/member/";

        // Create the directory if it doesn't exist
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }
        $currentTime = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
        $photoFileName = $fullname . '_' . $currentTime . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        $targetFilePath = $targetDirectory . $photoFileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            // Photo upload success, now insert into database
            $createmember = mysqli_query($con, "INSERT INTO member (fullname, address, phone_number, gender, photo_filename) VALUES ('$fullname','$address', '$phone_number', '$gender', '$photoFileName')");
            $_SESSION['success'] = "Berhasil menambah data anggota baru";
            echo '<script type="text/javascript">window.location.href = "../member";</script>';
        } else {
            $_SESSION['error'] = "Gagal mengupload foto. Silakan coba lagi.";
        }
    }
}

if (isset($_POST['update'])) {
    $id_update = $_POST['id_member_update'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $phone_number = $_POST['country_code'] . $_POST['phone_number'];
    $gender = $_POST['gender'];

    $allowedPhotoExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
    $uploadedExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    $error=false;
    $errfullname = false;
    $erraddress = false;
    $errphone = false;
    $errphoto = false;

    // Validasi Inputan 
    if (!preg_match('/^[a-zA-Z\s.]+$/', $fullname) || preg_match('/[<>]/', $fullname) || preg_match('/[;\'"()|&%*$^]/', $fullname)) {
        $error = true;
        $errfullname = true;
    }
    if (preg_match('/[<>]/', $address) || preg_match('/[;\'"()|&%*$^]/', $address)){
        $error = true;
        $erraddress= true;
    }
    if (strlen($phone_number) > 15) {
        $error = true;
        $errphone = true;
    }
    // =========================
    // Check Hasil validasi
    if($error===true){
        $_SESSION['error'] = "Ada kesalahan pada data yang diinputkan :";
        if($errfullname===true){
            $_SESSION['error'] .= "<br> - Nama lengkap hanya boleh mengandung huruf, spasi dan titik saja.";
        }
        if($erraddress===true){
            $_SESSION['error'] .= "<br> - Alamat tidak boleh mengandung karakter HTML atau Query SQL.";
        }
        if($errphone===true){
            $_SESSION['error'] .= "<br> - Nomer telepon tidak boleh lebih dari 15 digit ";
        }
        ?>

<form id="myForm" role="form" method="post" action="../member/">
    <input type="hidden" name="id_member" value="<?=$id_update?>">
    <input type="hidden" name="edit" value="edit_value">
</form>'
<script type="text/javascript">
document.getElementById('myForm').submit();
</script>
<?php 
    } 
    else {

        // Upload photo
        $targetDirectory = "../../assets/images/member/";

         // mengecek tarket direktori
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }
        $currentTime = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
        $photoFileName = $fullname . '_' . $currentTime . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        $targetFilePath = $targetDirectory . $photoFileName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {

        $getPhotoFilename = mysqli_query($con, "SELECT photo_filename FROM member WHERE id = '$id_update'");
        $row = mysqli_fetch_assoc($getPhotoFilename);
        $photoFilename = $row['photo_filename'];
        $photoPath = "../../assets/images/member/" . $photoFilename;
                if (file_exists($photoPath)&& is_file($photoPath)) {
                    unlink($photoPath);
                }
            
            
        $update = mysqli_query($con, "UPDATE member SET fullname = '$fullname', address = '$address', phone_number = '$phone_number', gender = '$gender',photo_filename='$photoFileName' WHERE id = '$id_update'");
            if ($update) {
                $_SESSION['success'] = "Berhasil mengubah data anggota";
                echo '<script>window.location.href = "../member/";</script>';
            } else {
                $_SESSION['error'] = "Gagal mengubah data barang";
            }
        } else {
        $update = mysqli_query($con, "UPDATE member SET fullname = '$fullname', address = '$address', phone_number = '$phone_number', gender = '$gender' WHERE id = '$id_update'");
            if ($update) {
                $_SESSION['success'] = "Berhasil mengubah data anggota";
                echo '<script>window.location.href = "../member/";</script>';
            } else {
                $_SESSION['error'] = "Gagal mengubah data barang";
            }
        }
    }
}


if (isset($_POST['delete'])) {
    $id = $_POST['id_member'];

    try {
        // Fetch the photo filename from the database
        $getPhotoFilename = mysqli_query($con, "SELECT photo_filename FROM member WHERE id = '$id'");
        $row = mysqli_fetch_assoc($getPhotoFilename);
        $photoFilename = $row['photo_filename'];

        // Attempt to delete the member record
        $delete_member = mysqli_query($con, "DELETE FROM member WHERE id = '$id'");

        if ($delete_member) {
            // Delete the associated photo file
            $photoPath = "../../assets/images/member/" . $photoFilename;
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }

            $_SESSION['success'] = "Berhasil menghapus data anggota";
            echo '<script>window.location.href = "../member";</script>';
        } else {
            throw new Exception("Gagal menghapus data anggota");
        }
    } catch (Exception $e) {
        // Handle the exception and set an appropriate error message
        $_SESSION['error'] = "Gagal menghapus data anggota karena terhubung dengan data peminjaman";
        echo '<script>window.location.href = "../member";</script>';
    }
}



?>




<h3 class="">Data Anggota</h3>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <?php
                if(isset($_POST['delete'])){
                    if (isset($_SESSION['error'])) { echo '
                    <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>
                    '; unset($_SESSION['error']); } 
                }
                if(!isset($_GET['tambah'])&&!isset($_POST['edit'])&&!isset($_POST['details'])){ ?>
                <a href="?tambah" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1">Tambah Anggota</a>
                <a href="?cetak" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1 mx-1"><svg
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                    </svg></a>
                <form action="../member/" method="post">
                    <div class="mb-3 " style="display: flex; align-items: center;">
                        <input type="text" class="form-control input" name="name_search"
                            placeholder="Cari berdasarkan nama" style="flex: 1;">
                        <button name="search" class="btn btn-primary mx-2"><svg xmlns="http://www.w3.org/2000/svg"
                                class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg></button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Alamat</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nomer Telepon</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Gender</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($_POST['search'])){
                                $search_name=$_POST['name_search'];
                                $result = mysqli_query($con, "SELECT * FROM member WHERE fullname LIKE '%$search_name%'");
                            } else {
                                $result = mysqli_query($con, "SELECT * FROM member ");
                            }
                            $iteration = 0;
                            while ($member = mysqli_fetch_array($result)) {
                                $iteration ++;
                            ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0"><?= $iteration ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1"><?= $member['fullname'] ?></h6>
                                </td>
                                <td class="border-bottom-0"
                                    style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                    <p class="mb-0 fw-normal"><?= $member['address'] ?></p>
                                </td>

                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 fs-4"><?= $member['phone_number'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php 
                                        $gender = strtoupper(substr($member['gender'], 0));
                                        if($member['gender']==="perempuan"){?>
                                        <span class="badge bg-primary rounded-3 fw-semibold"
                                            style="width: 140px;"><?= $gender ?></span>
                                        <?php } else { ?>
                                        <span class="badge bg-success rounded-3 fw-semibold"
                                            style="width: 140px;"><?= $gender ?></span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <form role="form" method="post" action="../member/">
                                        <input type="hidden" name="id_member" value="<?= $member['id'] ?>">
                                        <button type="submit" name="edit" class="btn btn-primary"><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-pencil" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                <path d="M13.5 6.5l4 4" />
                                            </svg></button>
                                        <button type="submit" name="delete" class="btn btn-danger mx-1"><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-trash" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg></button>
                                        <button type="submit" name="details" class="btn btn-secondary"><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-eye" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path
                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg></button>
                                    </form>
                                </td>
                            </tr>
                            <?php 
                            }
                            if($iteration===0){?>
                            <td class="border-bottom-0 text-center" colspan="6">
                                <h3 class="fw-semibold mb-0 text-center">Anggota tidak ditemukan</h3>
                            </td>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } 
                if(isset($_GET['tambah'])&&!isset($_POST['edit'])){  ?>
                <h5 class="card-title fw-semibold mb-4">Forms Tambah </h5>
                <?php 
                    if(isset($_GET['tambah'])){
                        if (isset($_SESSION['error'])) { echo '
                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>
                        '; unset($_SESSION['error']); } 
                    }
                ?>
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="post" action="./index.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto</label>
                                <div id="preview-container" class="mb-3" style="display: none; ">
                                    <img id="preview" class="img-thumbnail rounded-circle" alt="Preview"
                                        style="width: 200px; height: 200px;">
                                </div>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                    required>
                                <div id="photoHelp" class="form-text">Hanaya menerima foto ber extensi ( 'jpg', 'jpeg',
                                    'png', 'gif', 'bmp', 'webp' )</div>
                            </div>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullname"
                                    placeholder="Masukkan nama lengkap" name="fullname" required>
                                <div id="fullnameHelp" class="form-text">Masukkan Nama Lengkap sesuai KTP
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" placeholder="Masukkan alamat lengkap" name="address"
                                    required></textarea>
                                <div id="fullnameHelp" class="form-text">Masukkan Alamat Lengkap sesuai KTP
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomer Telepon</label>
                                <div style="display: flex; align-items: center;">
                                    <select class="form-control input" name="country_code" style="width: 80px;"
                                        required>
                                        <option value="+62">+62</option>
                                        <option value="+1">+1</option>
                                        <option value="+44">+44</option>
                                    </select>
                                    <input type="number" class="form-control" id="phone_number" style="flex: 1;"
                                        placeholder="Masukkan nomer telepon" name="phone_number" required>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Gender</label>
                                <select class="form-control input" name="gender" required>
                                    <option value="laki-laki">Laki - Laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <button type="submit" name="create" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <?php }  
                if(isset($_POST['edit'])){ 
                    $editmember = mysqli_query($con, "SELECT * FROM member WHERE id=$_POST[id_member] ");  
                    $old_data = mysqli_fetch_array($editmember)  
                ?>

                <h5 class="card-title fw-semibold mb-4">Forms Edit</h5>
                <?php 
                    if(isset($_POST['edit'])){
                        if (isset($_SESSION['error'])) { echo '
                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>
                        '; unset($_SESSION['error']); } 
                    }
                ?>
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="post" action="./index.php" enctype="multipart/form-data">
                            <input type="hidden" name="id_member_update" value="<?= $old_data['id'] ?>">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto</label>
                                <div class="image-preview-container mb-3">

                                    <?php if(isset($old_data['photo_filename'])){ ?>

                                    <img class="img-thumbnail rounded-circle image-preview" alt="Preview"
                                        src="../../assets/images/member/<?= $old_data['photo_filename'] ?>"
                                        style="width: 200px; height: 200px;">
                                    <?php } else { ?>
                                    <img class="img-thumbnail rounded-circle image-preview" alt="Preview"
                                        src="../../assets/images/profile/user-1.jpg"
                                        style="width: 200px; height: 200px;">
                                    <?php } ?>
                                </div>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                    onchange="previewImage(this)">
                                <div id="photoHelp" class="form-text">Hanya menerima foto dengan ekstensi ('jpg','jpeg',
                                    'png', 'gif', 'bmp', 'webp')</div>
                            </div>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullname"
                                    placeholder="Masukkan nama lengkap" name="fullname"
                                    value="<?= $old_data['fullname']?>" required>
                                <div id="fullnameHelp" class="form-text">Masukkan Nama Lengkap sesuai KTP
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" placeholder="Masukkan alamat lengkap" name="address"
                                    required><?= $old_data['address']?></textarea>
                                <div id="fullnameHelp" class="form-text">Masukkan Alamat Lengkap sesuai KTP
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomer Telepon</label>
                                <?php 
                                $country_code = substr($old_data['phone_number'], 0, 3);
                                $phone_number = substr($old_data['phone_number'], 3);
                                ?>
                                <div style="display: flex; align-items: center;">
                                    <select class="form-control input" name="country_code" style="width: 80px;"
                                        required>
                                        <option value="<?= $country_code ?>"><?= $country_code ?></option>
                                        <option value="+62">+62</option>
                                        <option value="+12">+12</option>
                                        <option value="+44">+44</option>
                                    </select>
                                    <input type="number" class="form-control" id="phone_number" style="flex: 1;"
                                        placeholder="Masukkan nomer telepon" name="phone_number"
                                        value="<?= $phone_number ?>" required>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Gender</label>
                                <select class="form-control input" name="gender" required>
                                    <?php
                                    if($old_data['gender']!=="perempuan"){
                                        echo '<option value='.$old_data['gender'].'>Laki - Laki</option>';
                                    }else {
                                        echo '<option value='.$old_data['gender'].'>Perempuan</option>';
                                    }
                                    ?>
                                    <option value="laki-laki">Laki - Laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <?php }
                if(isset($_POST['details'])){ 
                    $detailsmember = mysqli_query($con, "SELECT * FROM member WHERE id=$_POST[id_member] ");  
                    $details = mysqli_fetch_array($detailsmember) 
                ?>
                <h5 class="card-title fw-semibold mb-4">Detail Anggota</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <div class="mb-3">
                                <?php if(isset($details['photo_filename'])){ ?>
                                <img class="img-thumbnail rounded-circle" alt="Preview"
                                    src="../../assets/images/member/<?= $details['photo_filename'] ?>"
                                    style="width: 200px; height: 200px;">
                                <?php } else { ?>
                                <img class="img-thumbnail rounded-circle" alt="Preview"
                                    src="../../assets/images/profile/user-1.jpg" style="width: 200px; height: 200px;">
                                <?php } ?>

                            </div>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Nama Lengkap </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['fullname'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Alamat Lengkap </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['address'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Nomer Telepon </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['phone_number'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Gender </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['gender'] ?></h4>
                        </div>
                    </div>
                </div>
                <a href="../member/" class="btn btn-primary">Kembali</a>



                <?php }?>
            </div>
        </div>
    </div>
</div>


<?php
include_once("../footer.php");
                                }
?>