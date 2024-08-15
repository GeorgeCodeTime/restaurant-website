<?php
include 'sessionstart.php';
require 'restrict.php';

if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
        if (isset($_GET['delete_produs_delivery'])) {
            $idprodus_delete = $_GET['delete_produs_delivery'];

            $verify_produs = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_delete'");
            if (mysqli_num_rows($verify_produs) === 0) {
                echo "<script>alert('Nu există produs cu acest ID!');window.location.href='delivery.php'</script>";
                exit();
            }

            $delete_produs = "DELETE FROM produse WHERE idprodus='$idprodus_delete'";
            $result_delete_produs = mysqli_query($conn, $delete_produs);
            if ($result_delete_produs) {
                $_SESSION["add_to_cart_alert"] = "Produsul a fost șters cu succes!";
                echo "<script>window.location.href='delivery.php';</script>";
                exit();
            } else {
                die(mysqli_error($conn));
            }
        }
    } else if ($_SESSION["tip"] == "client") {
        echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
}
