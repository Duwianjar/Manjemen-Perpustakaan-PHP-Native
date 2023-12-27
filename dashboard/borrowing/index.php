<?php
session_start();
$_SESSION['navigation'] = "4";
include_once("../navbar.php");


if (isset($_POST['create'])) {
    $borrower_id= $_POST['borrower_id'];
    $book_id = $_POST['book_id'];
    $loan_date = $_POST['loan_date'];
    $return_date = $_POST['return_date'];

    $bookdatas = mysqli_query($con, "SELECT * FROM book WHERE id=$book_id "); 
    $books = mysqli_fetch_assoc($bookdatas);
    $amountbook = $books['amount']-1;

    
    $createborrowing = mysqli_query($con, "INSERT INTO borrowing (borrower_id, book_id, loan_date, return_date, status) VALUES ('$borrower_id','$book_id', '$loan_date', '$return_date','Belum Kembali')");
    if ($createborrowing) {
        $updateamount = mysqli_query($con, "UPDATE book SET amount='$amountbook' WHERE id = $book_id");
        $_SESSION['success'] = "Berhasil menambah data peminjaman baru";
        echo '<script type="text/javascript">
        window.location.href = "../loan_data";
        </script>';
    }
}


?>




<h3 class="">Transaksi </h3>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4"> Form Peminjaman</h5>
                <?php 
                        if (isset($_SESSION['error'])) { echo '
                        <div class="alert alert-danger col-8 mx-auto text-center p-2 border rounded text-center">' . $_SESSION['error'] . '</div>
                        '; unset($_SESSION['error']); } 
                ?>
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="post" action="./index.php">
                            <div class="mb-3">
                                <label for="borrower_id" class=" form-label">Nama Peminjam</label>
                                <?php $allmember = mysqli_query($con, "SELECT * FROM member ");?>
                                <select class="form-control input" name="borrower_id" required>
                                    <?php
                                    $iterationmember=0;
                                    while ($member = mysqli_fetch_array($allmember)) { 
                                    $iterationmember++;?>
                                    <option value="<?= $member['id']?>">
                                        <?= $iterationmember.'. '.$member['fullname']?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="book_id" class=" form-label">Nama Buku</label>
                                <?php
                                    $iterationbook=0;
                                    $allbook = mysqli_query($con, "SELECT * FROM book ");?>
                                <select class="form-control input" name="book_id" required>
                                    <?php while ($book = mysqli_fetch_array($allbook)) { 
                                    $iterationbook++;
                                    if($book['amount']!=0){?>
                                    <option value="<?= $book['id']?>">
                                        <?= $iterationbook.'. '. $book['title']. ' - Buku tersedia : ' .$book['amount']?>
                                    </option>
                                    <?php }
                                    }?>
                                </select>
                            </div>
                            <?php $currentDate = date('Y-m-d'); ?>

                            <div class="mb-3">
                                <label for="loan_date" class="form-label">Tanggal Peminjaman</label>
                                <h6 class="fw-semibold mb-1 mx-2 mt-2"><?= $currentDate ?></h6>
                                <input type="hidden" class="form-control" id="loan_date" name="loan_date"
                                    value="<?= $currentDate ?>" readonly required>
                            </div>
                            <?php $returnDate = date('Y-m-d', strtotime('+7 days', strtotime($currentDate))); ?>
                            <div class="mb-3">
                                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                                <h6 class="fw-semibold mb-2 mx-2 mt-2"><?= $returnDate ?></h6>
                                <input type="hidden" class="form-control" id="return_date" name="return_date"
                                    value="<?= $returnDate ?>" readonly required>
                                <div id="return_dateHelp" class="form-text mx-2">7 Hari setelah tanggal peminjaman
                                </div>
                            </div>
                            <button type="submit" name="create" class="btn btn-primary">Simpan Data</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<?php
include_once("../footer.php");
?>