<?php
session_start();

    session_unset();
    session_destroy();
    session_start();        
    $_SESSION['success'] = "Anda sudah berhasil logout";
    header("Location: ../auth");