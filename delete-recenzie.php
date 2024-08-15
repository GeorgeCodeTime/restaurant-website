<?php

include 'sessionstart.php';

if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
        if (isset($_GET["deleteid"]) && isset($_GET["idclient"])) {
            $id_delete_recenzie = mysqli_real_escape_string($conn,$_GET["deleteid"]);
            $id_client_recenzie = mysqli_real_escape_string($conn,$_GET["idclient"]);
            $review_and_user_verifiy = mysqli_query($conn, "SELECT * FROM recenzii WHERE idcont = '$id_client_recenzie' AND idrecenzie ='$id_delete_recenzie'");
            if(mysqli_num_rows($review_and_user_verifiy) === 0){
                $_SESSION['deny-delete-by-admin'] = "Nu există recenzie sau cont cu acest ID";
                echo "<script>window.location.href='recenzii.php'</script>";
                exit();
            }
            $result_delete_recenzie = mysqli_query($conn, "DELETE FROM recenzii WHERE idcont = '$id_client_recenzie' AND idrecenzie ='$id_delete_recenzie' ");
            if ($result_delete_recenzie) {
                $_SESSION['success-delete-by-admin'] = "Recenzia a fost ștearsă cu succes!";
                echo "<script>window.location.href='recenzii.php'</script>";
                exit();
            } else {
                die(mysqli_error($conn));
            }
        } else {
            echo "<script>window.location.href='recenzii.php'</script>";
        }
    } else if ($_SESSION["tip"] == "client") {
        if (isset($_GET["deleteid"]) && isset($_GET["idclient"])) {
            
            if ($_GET["idclient"] !== $idCont) {
                echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='recenzii.php'</script>";
                exit();
            }
            $id_delete_recenzie2 = mysqli_real_escape_string($conn,$_GET["deleteid"]);
            $id_client_recenzie2 = mysqli_real_escape_string($conn,$_GET["idclient"]);
            $review_and_user_verifiy2 = mysqli_query($conn, "SELECT * FROM recenzii WHERE idcont = '$id_client_recenzie2' AND idrecenzie ='$id_delete_recenzie2'");
            if(mysqli_num_rows($review_and_user_verifiy2) === 0){
                echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='recenzii.php'</script>";
                exit();
            }
            $result_delete_recenzie2 = mysqli_query($conn, "DELETE FROM recenzii WHERE idcont = '$id_client_recenzie2' AND idrecenzie ='$id_delete_recenzie2' ");
            if ($result_delete_recenzie2) {
                $_SESSION['success-delete'] = "Recenzia dvs. a fost ștearsă cu succes!";
                echo "<script>window.location.href='recenzii.php'</script>";
                exit();
            } else {
                die(mysqli_error($conn));
            }
        } else {
            echo "<script>window.location.href='recenzii.php'</script>";
        }
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
    exit();
}
