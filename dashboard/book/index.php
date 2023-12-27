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
    $pdf->Cell(190, 7, 'DAFTAR DATA BUKU', 0, 1, 'C');
    // Memberikan space kebawah agar tidak terlalu rapat
    $pdf->Cell(10, 7, '', 0, 1);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 6, 'ID', 1, 0, 'C');
    $pdf->Cell(50, 6, 'Judul', 1, 0, );
    $pdf->Cell(55, 6, 'Pengarang', 1, 0,);
    $pdf->Cell(40, 6, 'Penerbit', 1, 0, );
    $pdf->Cell(30, 6, 'Jumlah Buku', 1, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $data_book = mysqli_query($con, "select * from book");
    $hitung=0;
    while ($row = mysqli_fetch_array($data_book)) {

        
        $pdf->Cell(15, 6, $row['id'], 1, 0, 'C');
        $pdf->Cell(50, 6, $row['title'], 1, 0);
        $pdf->Cell(55, 6, $row['author'], 1, 0,);
        $pdf->Cell(40, 6, $row['publisher'], 1, 0,  );
        $pdf->cell(30, 6, $row['amount'], 1, 1,'C');
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
$_SESSION['navigation'] = "3";
include_once("../navbar.php");


if (isset($_POST['create'])) {
    $title= $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $amount = $_POST['amount'];

    $allowedPhotoExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
    $uploadedExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    $error=false;
    $errtitle= false;
    $errauthor = false;
    $errpublisher = false;
    $erramount = false ;
    $errphoto = false;

    // Validasi Inputan 
    if (preg_match('/[<>]/', $title) || preg_match('/[;\'"()|&%*$^]/', $title)) {
        $error = true;
        $errtitle= true;
    }
    if (!preg_match('/^[a-zA-Z\s.]+$/', $author) || preg_match('/[<>]/', $author) || preg_match('/[;\'"()|&%*$^]/', $author)){
        $error = true;
        $errauthor= true;
    }
    if (!preg_match('/^[a-zA-Z\s.]+$/', $publisher) || preg_match('/[<>]/', $publisher) || preg_match('/[;\'"()|&%*$^]/', $publisher)){
        $error = true;
        $errpublisher= true;
    }
    
    if (strlen($amount) > 3) {
        $error = true;
        $erramount = true;
    }

    if (!in_array($uploadedExtension, $allowedPhotoExtensions)) {
        $error = true;
        $errphoto = true;
    }
    // =========================
    // Check Hasil validasi
    if($error===true){
        $_SESSION['error'] = "Ada kesalahan pada data yang diinputkan :";
        if($errtitle===true){
            $_SESSION['error'] .= "<br> - Judul buku tidak boleh mengandung karakter HTML atau Query SQL.";
        }
        if($errauthor===true){
            $_SESSION['error'] .= "<br> - Nama pengarang hanya boleh mengandung huruf, spasi dan titik.";
        }
        if($errpublisher===true){
            $_SESSION['error'] .= "<br> - Nama penerbit hanya boleh mengandung huruf, spasi dan titik.";
        }
        if($erramount===true){
            $_SESSION['error'] .= "<br> - Jumlah buku tidak boleh lebih dari 3 digit.";
        }
        if($errphoto===true){
            $_SESSION['error'] .= "<br> - Ekstensi file foto tidak diizinkan. Silakan upload file dengan ekstensi: " . implode(', ', $allowedPhotoExtensions); "";
        }
        echo '<script type="text/javascript">window.location.href = "../book/?tambah";</script>';
    } 
    else {
        // Upload photo
        $targetDirectory = "../../assets/images/book/";
        // Create the directory if it doesn't exist
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }
        $currentTime = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
        $photoFileName = $title . '_' . $currentTime . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        $targetFilePath = $targetDirectory . $photoFileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
            $createbook = mysqli_query($con, "INSERT INTO book (title, author, publisher, amount, photo_filename) VALUES ('$title','$author', '$publisher', '$amount', '$photoFileName')");
            $_SESSION['success'] = "Berhasil menambah data buku baru";
            echo '<script type="text/javascript">window.location.href = "../book";</script>';
        } else {
            $_SESSION['error'] = "Gagal mengupload foto. Silakan coba lagi.";
        }
    }
}

if (isset($_POST['update'])) {
    $id_update = $_POST['id_book_update'];
    $title= $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $amount = $_POST['amount'];

    $allowedPhotoExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
    $uploadedExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));


    $error=false;
    $errtitle= false;
    $errauthor = false;
    $errpublisher = false;
    $erramount = false ;
    $errphoto = false;

    // Validasi Inputan 
    if (preg_match('/[<>]/', $title) || preg_match('/[;\'"()|&%*$^]/', $title)) {
        $error = true;
        $errtitle= true;
    }
    if (!preg_match('/^[a-zA-Z\s.]+$/', $author) || preg_match('/[<>]/', $author) || preg_match('/[;\'"()|&%*$^]/', $author)){
        $error = true;
        $errauthor= true;
    }
    if (!preg_match('/^[a-zA-Z\s.]+$/', $publisher) || preg_match('/[<>]/', $publisher) || preg_match('/[;\'"()|&%*$^]/', $publisher)){
        $error = true;
        $errpublisher= true;
    }
    
    if (strlen($amount) > 3) {
        $error = true;
        $erramount = true;
    }
    // =========================
    // Check Hasil validasi
    if($error===true){
        $_SESSION['error'] = "Ada kesalahan pada data yang diinputkan :";
        if($errtitle===true){
            $_SESSION['error'] .= "<br> - Judul buku tidak boleh mengandung karakter HTML atau Query SQL.";
        }
        if($errauthor===true){
            $_SESSION['error'] .= "<br> - Nama pengarang hanya boleh mengandung huruf, spasi dan titik.";
        }
        if($errpublisher===true){
            $_SESSION['error'] .= "<br> - Nama penerbit hanya boleh mengandung huruf, spasi dan titik.";
        }
        if($erramount===true){
            $_SESSION['error'] .= "<br> - Jumlah buku tidak boleh lebih dari 3 digit.";
        }
        ?>

<form id="myForm" role="form" method="post" action="../book/">
    <input type="hidden" name="id_book" value="<?=$id_update?>">
    <input type="hidden" name="edit" value="edit_value">
</form>'
<script type="text/javascript">
document.getElementById('myForm').submit();
</script>
<?php 
    } 
    else {
        // Upload photo
        $targetDirectory = "../../assets/images/book/";
        // mengecek tarket direktori
        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }
        $currentTime = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
        $photoFileName = $title . '_' . $currentTime . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
        $targetFilePath = $targetDirectory . $photoFileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {

            $getPhotoFilename = mysqli_query($con, "SELECT photo_filename FROM book WHERE id = '$id_update'");
            $row = mysqli_fetch_assoc($getPhotoFilename);
            $photoFilename = $row['photo_filename'];

            $photoPath = "../../assets/images/book/" . $photoFilename;

            // hapus foto lama jika ada di direktori
            if (file_exists($photoPath)&& is_file($photoPath)) {
                unlink($photoPath);
            }

        $update = mysqli_query($con, "UPDATE book SET title= '$title', author = '$author', publisher = '$publisher', amount = '$amount',photo_filename='$photoFileName' WHERE id = '$id_update'");
            if ($update) {
                $_SESSION['success'] = "Berhasil mengubah data buku";
                echo '<script>window.location.href = "../book/";</script>';
            } else {
                $_SESSION['error'] = "Gagal mengubah data buku";
            }
        } else {
            $update = mysqli_query($con, "UPDATE book SET title= '$title', author = '$author', publisher = '$publisher', amount = '$amount' WHERE id = '$id_update'");
            if ($update) {
                $_SESSION['success'] = "Berhasil mengubah data buku";
                echo '<script>window.location.href = "../book/";</script>';
            } else {
                $_SESSION['error'] = "Gagal mengubah data buku";
            }
        }
    }
}


if (isset($_POST['delete'])) {
    $id = $_POST['id_book'];

    try {
        // Fetch the photo filename from the database
        $getPhotoFilename = mysqli_query($con, "SELECT photo_filename FROM book WHERE id = '$id'");
        $row = mysqli_fetch_assoc($getPhotoFilename);
        $photoFilename = $row['photo_filename'];

        // Cobalah untuk menjalankan query penghapusan
        $delete_book = mysqli_query($con, "DELETE FROM book WHERE id = '$id'");

        if ($delete_book) {
            // Delete the associated photo file
            $photoPath = "../../assets/images/book/" . $photoFilename;
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
            $_SESSION['success'] = "Berhasil menghapus data buku";
            echo '<script>window.location.href = "../book";</script>';
        } else {
            throw new Exception("Gagal menghapus data buku");
        }
    } catch (Exception $e) {
        // Tangkap kesalahan dan tetapkan pesan sesuai
        $_SESSION['error'] = "Gagal menghapus data buku karena terhubung dengan data peminjaman";
        echo '<script>window.location.href = "../book";</script>';
    }
}
?>

<h3 class="">Data Buku</h3>
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
                <a href="?tambah" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1">Tambah Buku</a>
                <a href="?cetak" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1 mx-1"><svg
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                    </svg></a>
                <form action="../book/" method="post">
                    <div class="mb-3 " style="display: flex; align-items: center;">
                        <input type="text" class="form-control input" name="title_search"
                            placeholder="Cari berdasarkan judul buku" style="flex: 1;">
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
                                    <h6 class="fw-semibold mb-0">Judul</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Pengarang</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Penerbit</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Jumlah Buku</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($_POST['search'])){
                                $search_title=$_POST['title_search'];
                                $result = mysqli_query($con, "SELECT * FROM book WHERE title LIKE '%$search_title%'");
                            } else {
                                $result = mysqli_query($con, "SELECT * FROM book ");
                            }
                            $iteration = 0;
                            while ($buku = mysqli_fetch_array($result)) {
                                $iteration ++;
                            ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0"><?= $iteration ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1"><?= $buku['title'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal"><?= $buku['author'] ?></p>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 fs-4"><?= $buku['publisher'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 fs-4 text-center"><?= $buku['amount'] ?></h6>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <form role="form" method="post" action="../book/">
                                        <input type="hidden" name="id_book" value="<?= $buku['id'] ?>">
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
                                <h3 class="fw-semibold mb-0 text-center">Buku tidak ditemukan</h3>
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
                                    <img id="preview" class="img-thumbnail " alt="Preview"
                                        style="width: 200px; height: 200px;">
                                </div>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                    required>
                                <div id="photoHelp" class="form-text">Hanaya menerima foto ber extensi ( 'jpg', 'jpeg',
                                    'png', 'gif', 'bmp', 'webp' )</div>
                            </div>
                            <div class="mb-3">
                                <label for="title" class=" form-label">Judul Buku</label>
                                <input type="text" class="form-control" id="title" placeholder="Masukkan judul buku"
                                    name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="author" class=" form-label">Pengarang</label>
                                <input type="text" class="form-control" id="author"
                                    placeholder="Masukkan nama pengarang" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label for="publisher" class=" form-label">Penerbit</label>
                                <input type="text" class="form-control" id="title" placeholder="Masukkan nama penerbit"
                                    name="publisher" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class=" form-label">Jumlah</label>
                                <input type="number" class="form-control" id="amount" placeholder="1" name="amount"
                                    required>
                            </div>
                            <button type="submit" name="create" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
                <?php }  
                if(isset($_POST['edit'])){ 
                    $editbook = mysqli_query($con, "SELECT * FROM book WHERE id=$_POST[id_book] ");  
                    $old_data = mysqli_fetch_array($editbook)  
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
                            <input type="hidden" name="id_book_update" value="<?= $old_data['id'] ?>">
                            <div class="mb-3">
                                <label for="photo" class="form-label">Foto</label>
                                <div class="image-preview-container mb-3">

                                    <?php if(isset($old_data['photo_filename'])){ ?>

                                    <img class="img-thumbnail  image-preview" alt="Preview"
                                        src="../../assets/images/book/<?= $old_data['photo_filename'] ?>"
                                        style="width: 200px; height: 200px;">
                                    <?php } else { ?>
                                    <img class="img-thumbnail image-preview" alt="Preview"
                                        src="../../assets/images/book/default.png" style="width: 200px; height: 200px;">
                                    <?php } ?>
                                </div>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                    valus="../../assets/images/member/" onchange="previewImage(this)">
                                <div id="photoHelp" class="form-text">Hanya menerima foto dengan ekstensi ('jpg','jpeg',
                                    'png', 'gif', 'bmp', 'webp')</div>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Buku</label>
                                <input type="text" class="form-control" id="title" placeholder="Masukkan judul buku"
                                    name="title" value="<?= $old_data['title']?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Pengarang</label>
                                <input type="text" class="form-control" id="author"
                                    placeholder="Masukkan nama pengarang" name="author"
                                    value="<?= $old_data['author']?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="publisher" class="form-label">Penerbit</label>
                                <input type="text" class="form-control" id="publisher"
                                    placeholder="Masukkan nama penerbit" name="publisher"
                                    value="<?= $old_data['publisher']?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">jumlah Buku</label>
                                <input type="number" class="form-control" id="amount" placeholder="1" name="amount"
                                    value="<?= $old_data['amount']?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
                <?php }
                if(isset($_POST['details'])){ 
                    $detailsbook = mysqli_query($con, "SELECT * FROM book WHERE id=$_POST[id_book] ");  
                    $details = mysqli_fetch_array($detailsbook) 
                ?>
                <h5 class="card-title fw-semibold mb-4">Detail Buku</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <div class="mb-3">
                                <?php if(isset($details['photo_filename'])){ ?>
                                <img class="img-thumbnail" alt="Preview"
                                    src="../../assets/images/book/<?= $details['photo_filename'] ?>"
                                    style="width: 200px; height: 200px;">
                                <?php } else { ?>
                                <img class="img-thumbnail " alt="Preview" src="../../assets/images/book/default.png"
                                    style="width: 200px; height: 200px;">
                                <?php } ?>

                            </div>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Judul Buku </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['title'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Pengarang </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['author'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Penerbit </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['publisher'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Jumlah Bukus </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['amount'] ?></h4>
                        </div>
                    </div>
                </div>
                <a href="../book/" class="btn btn-primary">Kembali</a>



                <?php }?>
            </div>
        </div>
    </div>
</div>


<?php
include_once("../footer.php");
                }
?>