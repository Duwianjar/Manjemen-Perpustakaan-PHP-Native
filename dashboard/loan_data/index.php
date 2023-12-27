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
    $pdf->Cell(190, 7, 'DAFTAR DATA PEMINJAMAN', 0, 1, 'C');
    // Memberikan space kebawah agar tidak terlalu rapat
    $pdf->Cell(10, 7, '', 0, 1);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(15, 6, 'ID', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Peminjam', 1, 0, );
    $pdf->Cell(45, 6, 'Buku', 1, 0,);
    $pdf->Cell(35, 6, 'Tanggal Pinjam', 1, 0, 'C');
    $pdf->Cell(35, 6, 'Tanggal Kembali', 1, 0, 'C');
    $pdf->Cell(30, 6, 'Status', 1, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $data_borrow = mysqli_query($con, "select * from borrowing");
    $hitung=0;
    while ($row = mysqli_fetch_array($data_borrow)) {

        $borrowerdata = mysqli_query($con, "SELECT * FROM member WHERE id=$row[borrower_id] "); 
        $borrower = mysqli_fetch_assoc($borrowerdata);
        $bookdata = mysqli_query($con, "SELECT * FROM book WHERE id=$row[book_id] "); 
        $book = mysqli_fetch_assoc($bookdata);


        $pdf->Cell(15, 6, $row['id'], 1, 0, 'C');
        
        $pdf->Cell(30, 6, $borrower['fullname'], 1, 0);
        $pdf->Cell(45, 6, $book['title'], 1, 0,);
        $pdf->Cell(35, 6, $row['loan_date'], 1, 0,'C'  );
        $pdf->cell(35, 6, $row['return_date'], 1, 0,'C');
        $pdf->cell(30, 6, $row['status'], 1, 1,'C');
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
$_SESSION['navigation'] = "5";
include_once("../navbar.php");

if (isset($_POST['return'])) {

    $bookdatas = mysqli_query($con, "SELECT * FROM book WHERE id=$_POST[id_book] "); 
    $books = mysqli_fetch_assoc($bookdatas);
    $amountbook = $books['amount']+1;
    
        $update = mysqli_query($con, "UPDATE borrowing SET status= 'Sudah Kembali' WHERE id = '$_POST[id_borrowing]'");
            if ($update) {
                $updateamount = mysqli_query($con, "UPDATE book SET amount='$amountbook' WHERE id = $_POST[id_book]");
                $_SESSION['success'] = "Berhasil mengembalikan buku";
                echo '<script>window.location.href = "../loan_data/";</script>';
            } else {
                $_SESSION['error'] = "Gagal mengubah data buku";
            }

}


?>




<h3 class="">Data Buku</h3>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <?php if(!isset($_POST['details'])){ ?>
                <a href="../borrowing/" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1">Peminjaman Baru</a>
                <a href="?cetak" class="btn btn-primary w-20 py-8 fs-4 mb-4 rounded-1 mx-1"><svg
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                        <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                        <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                    </svg></a>
                <a href="?late" class="btn btn-danger w-20 py-8 fs-4 mb-4 rounded-1"><svg
                        xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-time" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M11.795 21h-6.795a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4" />
                        <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                        <path d="M15 3v4" />
                        <path d="M7 3v4" />
                        <path d="M3 11h16" />
                        <path d="M18 16.496v1.504l1 1" />
                    </svg></a>

                <form action="../loan_data/" method="post">
                    <div class="mb-3 " style="display: flex; align-items: center;">
                        <input type="text" class="form-control input" name="value_search"
                            placeholder="Cari berdasarkan nama peminjam dan judul buku" style="flex: 1;">
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

                <div id="fullnameHelp" class="form-text mx-3">Urutan berdasarkan data terbaru
                </div>
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Peminjam</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Buku</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Tanggal Pinjam</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Tanggal Pengembalian</h6>
                                </th>
                                <th class="border-bottom-0 ">
                                    <h6 class="fw-semibold mb-0 mx-4">Status</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 text-center">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($_POST['search'])){
                                $search_title=$_POST['value_search'];
                                $result = mysqli_query($con, "SELECT * FROM borrowing JOIN book on borrowing.book_id=book.id JOIN member on borrowing.borrower_id=member.id WHERE book.title LIKE '%$search_title%' OR member.fullname LIKE '%$search_title%' ORDER BY borrowing.id DESC");
                            } else if(isset($_GET['late'])){
                                $result = mysqli_query($con, "SELECT * FROM borrowing WHERE return_date < CURDATE()  AND status = 'Belum Kembali' ORDER BY id DESC");
                            } else {
                                $result = mysqli_query($con, "SELECT * FROM borrowing ORDER BY id DESC");
                            }
                            $iteration = 0;
                            while ($borrowing = mysqli_fetch_array($result)) {
                                $iteration ++;
                            ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0"><?= $iteration ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <?php $borrowerdata = mysqli_query($con, "SELECT * FROM member WHERE id=$borrowing[borrower_id] "); 
                                    $borrower = mysqli_fetch_assoc($borrowerdata);?>
                                    <h6 class="fw-semibold mb-1"><?= $borrower['fullname'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <?php $bookdata = mysqli_query($con, "SELECT * FROM book WHERE id=$borrowing[book_id] "); 
                                    $book = mysqli_fetch_assoc($bookdata);?>
                                    <h6 class="fw-semibold mb-1"><?= $book['title'] ?></h6>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <h6 class="fw-semibold mb-1 "><?= $borrowing['loan_date'] ?></h6>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <h6 class="fw-semibold mb-1"><?= $borrowing['return_date'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($borrowing['status']==="Belum Kembali"){ 
                                           $returnDateTime = new DateTime($borrowing['return_date']);
                                           $currentDate = new DateTime();
                                           $dateDifference = $currentDate->diff($returnDateTime);
                                           $daysDifference = $dateDifference->days;
                                        ?>
                                        <span class="badge bg-danger rounded-3 fw-semibold"
                                            style="width: 145px;"><?php echo $borrowing['status']; if($returnDateTime< $currentDate){?>
                                            <br> ( Terlambat <?=$daysDifference?> hari ) <?php } ?> </span>
                                        <?php } else { ?>
                                        <span class="badge bg-success rounded-3 fw-semibold"
                                            style="width: 145px;"><?= $borrowing['status'] ?></span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="border-bottom-0 text-center">

                                    <div class="form-container">
                                        <form role="form" method="post" action="../loan_data/"
                                            onsubmit="return confirm('Apakah Anda yakin buku sudah dikembalikan?');">
                                            <input type="hidden" name="id_borrowing" value="<?= $borrowing['id'] ?>">
                                            <input type="hidden" name="id_book" value="<?= $borrowing['book_id'] ?>">
                                            <?php if($borrowing['status']==="Belum Kembali"){?>
                                            <button type="submit" name="return" class="btn btn-primary"><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-restore" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3.06 13a9 9 0 1 0 .49 -4.087" />
                                                    <path d="M3 4.001v5h5" />
                                                    <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                </svg></button>
                                            <?php } else { ?>
                                            <button type="submit" class="btn btn-success" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-checks" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 12l5 5l10 -10" />
                                                    <path d="M2 12l5 5m5 -5l5 -5" />
                                                </svg>
                                            </button>


                                            <?php } ?>
                                        </form>
                                        <form role="form" method="post" action="../loan_data/">
                                            <input type="hidden" name="id_borrowing" value="<?= $borrowing['id'] ?>">

                                            <button type="submit" name="details" class="btn btn-secondary"><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg></button>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            
                            }
                            
                            if($iteration===0){?>
                            <td class="border-bottom-0 text-center" colspan="6">
                                <h3 class="fw-semibold mb-0 text-center">Data peminjaman tidak ditemukan</h3>
                            </td>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php }
                if(isset($_POST['details'])){ 
                    $detailsborrowing = mysqli_query($con, "SELECT * FROM borrowing WHERE id=$_POST[id_borrowing] ");  
                    $details = mysqli_fetch_array($detailsborrowing); 
                ?>
                <h5 class="card-title fw-semibold mb-4">Detail Peminjaman</h5>
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 mx-4 px-4">Peminjam </h4>
                            <?php $borrowerdata = mysqli_query($con, "SELECT * FROM member WHERE id=$details[borrower_id] "); 
                                      $borrower = mysqli_fetch_assoc($borrowerdata);?>
                            <h4 class="fw-semibold mb-0 fs-6 col-3"> Buku yang dipinjam</h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <?php $bookdata = mysqli_query($con, "SELECT * FROM book WHERE id=$details[book_id] "); 
                                    $book = mysqli_fetch_assoc($bookdata);?>
                            <?php if(isset($borrower['photo_filename'])){ ?>
                            <img class="img-thumbnail rounded-circle col-6" alt="Preview"
                                src="../../assets/images/member/<?= $borrower['photo_filename'] ?>"
                                style="width: 200px; height: 200px;">
                            <?php } else { ?>
                            <img class="img-thumbnail rounded-circle col-6" alt="Preview"
                                src="../../assets/images/profile/user-1.jpg" style="width: 200px; height: 200px;">
                            <?php } ?>
                            <h4 class="col-1"></h4>
                            <?php if(isset($book['photo_filename'])){ ?>
                            <img class="img-thumbnail mx-4" alt="Preview"
                                src="../../assets/images/book/<?= $book['photo_filename'] ?>"
                                style="width: 200px; height: 200px;">
                            <?php } else { ?>
                            <img class="img-thumbnail mx-4" alt="Preview" src="../../assets/images/book/default.png"
                                style="width: 200px; height: 200px;">
                            <?php } ?>
                        </div>
                        <div class="mb-5 d-flex">

                            <h4 class="fw-semibold mb-0 fs-6 col-3 mx-4 px-4"> <?= $borrower['fullname'] ?> </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-3"><?= $book['title'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Tanggal Pinjam </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['loan_date']?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Tanggal Kembali </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['return_date'] ?></h4>
                        </div>
                        <div class="mb-3 d-flex">
                            <h4 class="fw-semibold mb-0 fs-6 col-3 "> Status Pengembalian</h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-1 "> : </h4>
                            <h4 class="fw-semibold mb-0 fs-6 col-6"> <?= $details['status'] ?>

                                <?php if($details['status']==="Belum Kembali"){ 
                                           $returnDateTime = new DateTime($details['return_date']);
                                           $currentDate = new DateTime();
                                           $dateDifference = $currentDate->diff($returnDateTime);
                                           $daysDifference = $dateDifference->days;
                                           if($returnDateTime< $currentDate){
                                        ?>
                                ( Terlambat <?=$daysDifference?> hari )
                                <?php }}?>
                            </h4>
                        </div>
                    </div>
                </div>
                <a href="../loan_data/" class="btn btn-primary">Kembali</a>



                <?php }?>
            </div>
        </div>
    </div>
</div>


<?php
include_once("../footer.php");
                            }
?>