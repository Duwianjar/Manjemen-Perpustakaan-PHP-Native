<?php
session_start();
$_SESSION['navigation'] = "1";
include_once("../navbar.php");
?>




<!--  Row 1 -->
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <a href="../member/" class="">
                            <div class="row alig n-items-start">
                                <div class="col-8">
                                    <?php 
                                $countmember = mysqli_query($con, "SELECT COUNT(*) as total FROM member");
                                $membercount = mysqli_fetch_assoc($countmember);
                                ?>
                                    <h5 class="card-title mb-9 fw-semibold">Total<br> Anggota</h5>
                                    <h4 class="fw-semibold mb-3"><?= $membercount['total']?></h4>

                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-user fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <a href="../book/" class="">
                            <div class="row alig n-items-start">
                                <div class="col-8">
                                    <?php 
                                $countbook = mysqli_query($con, "SELECT COUNT(*) as total FROM book");
                                $bookcount = mysqli_fetch_assoc($countbook);
                                ?>
                                    <h5 class="card-title mb-9 fw-semibold">Total <br>Buku</h5>
                                    <h4 class="fw-semibold mb-3"><?= $bookcount['total']?></h4>

                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-book fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <a href="../loan_data/" class="">
                            <div class="row alig n-items-start">
                                <div class="col-8">
                                    <?php 
                                $countborrowing = mysqli_query($con, "SELECT COUNT(*) as total FROM borrowing");
                                $borrowingcount = mysqli_fetch_assoc($countborrowing);
                                ?>
                                    <h5 class="card-title mb-9 fw-semibold">Total<br> Peminjaman</h5>
                                    <h4 class="fw-semibold mb-3"><?=  $borrowingcount['total']?></h4>

                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-cards fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <a href="../loan_data/?late" class="">
                            <div class="row alig n-items-start">
                                <div class="col-8">
                                    <?php 
                                $countlate = mysqli_query($con, "SELECT COUNT(*) as total FROM borrowing WHERE return_date < CURDATE()  AND status = 'Belum Kembali'");
                                $borrowinglate = mysqli_fetch_assoc($countlate);
                                ?>
                                    <h5 class="card-title mb-9 fw-semibold">Total <br> Terlambat</h5>
                                    <h4 class="fw-semibold mb-3"><?=  $borrowinglate['total']?></h4>

                                </div>
                                <div class="col-4">
                                    <div class="d-flex justify-content-end">
                                        <div
                                            class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-file-description fs-6"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Data Anggota</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $result = mysqli_query($con, "SELECT * FROM member ");
                            $iteration = 1;
                            while ($member = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0"><?= $iteration ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1"><?= $member['fullname'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal"><?= substr($member['address'], 0, 40) . '...' ?></p>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0 fs-4"><?= $member['fullname'] ?></h6>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($member['gender']==="perempuan"){?>
                                        <span class="badge bg-primary rounded-3 fw-semibold"
                                            style="width: 140px;">PEREMPUAN</span>
                                        <?php } else { ?>
                                        <span class="badge bg-success rounded-3 fw-semibold" style="width: 140px;">LAKI
                                            -LAKI</span>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            $iteration ++;
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include_once("../footer.php");
?>