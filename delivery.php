<?php
include('sessionstart.php');

if (isset($_POST['submitAdd'])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $productName = mysqli_real_escape_string($conn, $_POST['nume-produs']);
    $productDesc = mysqli_real_escape_string($conn, $_POST['descriere-produs']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['pret-produs']);
    $productCategory = mysqli_real_escape_string($conn, $_POST['categorie-produs']);
    $image = $_FILES['imagine-produs']['name'];
    $image_tmp_name = $_FILES['imagine-produs']['tmp_name'];
    $image_folder = 'imagini/delivery/' . $image;

    $insertProduct = "INSERT into produse (numeprodus,descriereprodus,pret,imagine,categorie) 
    VALUES ('$productName','$productDesc','$productPrice','$image','$productCategory')";
    $result_insert = mysqli_query($conn, $insertProduct);

    if ($result_insert) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

if (isset($_POST["submit_update_qty"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $update_product_qty = mysqli_real_escape_string($conn, $_POST['update_qty']);
    $idcos_update = mysqli_real_escape_string($conn, $_POST['idcos']);

    if(!is_numeric($update_product_qty) or !is_numeric($idcos_update)){
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $select_cos = mysqli_query($conn, "SELECT * FROM cos WHERE idcos = '$idcos_update' AND idcont ='$idCont'");
    if (mysqli_num_rows($select_cos) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $update_qty = "UPDATE  cos SET cantitate = '$update_product_qty' WHERE idcos = '$idcos_update' AND idcont = '$idCont'";
    $result_update_qty = mysqli_query($conn, $update_qty);

    if (isset($result_update_qty)) {
        $_SESSION["add_to_cart_alert"] = "Cantitatea produsului a fost modificată!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

if (isset($_POST['delete_produs_cos'])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $idcos_delete = mysqli_real_escape_string($conn, $_POST['idcos_delete']);

    $select_cos = mysqli_query($conn, "SELECT * FROM cos WHERE idcos = '$idcos_delete' AND idcont ='$idCont'");
    if (mysqli_num_rows($select_cos) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $delete_cos = "DELETE FROM cos WHERE idcos='$idcos_delete' AND idcont = '$idCont'";
    $result_delete_cos = mysqli_query($conn, $delete_cos);
    if ($result_delete_cos) {
        $_SESSION["add_to_cart_alert"] = "Produsul a fost șters din coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

if (isset($_POST["delete_toate_produse_cos"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $delete_toate_cos = "DELETE FROM cos WHERE idcont = '$idCont'";
    $result_delete_toate_cos = mysqli_query($conn, $delete_toate_cos);
    if ($result_delete_toate_cos) {
        $_SESSION["add_to_cart_alert"] = "Toate produsele din coș au fost șterse!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

if (isset($_POST["adauga_in_cos_micdejun"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_micdejun = mysqli_real_escape_string($conn, $_POST["cantitate_produs_micdejun"]);
    $idprodus_micdejun = mysqli_real_escape_string($conn, $_POST["idprodus_micdejun"]);

    if (!is_numeric($idprodus_micdejun) or !is_numeric($cantitate_produs_micdejun)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_micdejun = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_micdejun'");
    if (mysqli_num_rows($verificare_produs_micdejun) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs = "SELECT * FROM cos WHERE idprodus = '$idprodus_micdejun' AND idcont = '$idCont' ";
    $rezultat_verificare = mysqli_query($conn, $verificare_produs);

    if (mysqli_num_rows($rezultat_verificare) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_micdejun' WHERE idprodus = '$idprodus_micdejun'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_micdejun','$cantitate_produs_micdejun')";
        mysqli_query($conn, $inserare_produs);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_pizza"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_pizza = mysqli_real_escape_string($conn, $_POST["cantitate_produs_pizza"]);
    $idprodus_pizza = mysqli_real_escape_string($conn, $_POST["idprodus_pizza"]);

    if (!is_numeric($idprodus_pizza) or !is_numeric($cantitate_produs_pizza)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_pizza = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_pizza'");
    if (mysqli_num_rows($verificare_produs_pizza) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs2 = "SELECT * FROM cos WHERE idprodus = '$idprodus_pizza' AND idcont = '$idCont' ";
    $rezultat_verificare2 = mysqli_query($conn, $verificare_produs2);

    if (mysqli_num_rows($rezultat_verificare2) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_pizza' WHERE idprodus = '$idprodus_pizza'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs2 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_pizza','$cantitate_produs_pizza')";
        mysqli_query($conn, $inserare_produs2);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_paste"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_paste = mysqli_real_escape_string($conn, $_POST["cantitate_produs_paste"]);
    $idprodus_paste = mysqli_real_escape_string($conn, $_POST["idprodus_paste"]);

    if (!is_numeric($idprodus_paste) or !is_numeric($cantitate_produs_paste)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_paste = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_paste'");
    if (mysqli_num_rows($verificare_produs_paste) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs3 = "SELECT * FROM cos WHERE idprodus = '$idprodus_paste' AND idcont = '$idCont' ";
    $rezultat_verificare3 = mysqli_query($conn, $verificare_produs3);

    if (mysqli_num_rows($rezultat_verificare3) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_paste' WHERE idprodus = '$idprodus_paste'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs3 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_paste','$cantitate_produs_paste')";
        mysqli_query($conn, $inserare_produs3);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_burger"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_burger = mysqli_real_escape_string($conn, $_POST["cantitate_produs_burger"]);
    $idprodus_burger = mysqli_real_escape_string($conn, $_POST["idprodus_burger"]);

    if (!is_numeric($idprodus_burger) or !is_numeric($cantitate_produs_burger)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_burger = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_burger'");
    if (mysqli_num_rows($verificare_produs_burger) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs4 = "SELECT * FROM cos WHERE idprodus = '$idprodus_burger' AND idcont = '$idCont' ";
    $rezultat_verificare4 = mysqli_query($conn, $verificare_produs4);

    if (mysqli_num_rows($rezultat_verificare4) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_burger' WHERE idprodus = '$idprodus_burger'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs4 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_burger','$cantitate_produs_burger')";
        mysqli_query($conn, $inserare_produs4);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_gratar"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_gratar = mysqli_real_escape_string($conn, $_POST["cantitate_produs_gratar"]);
    $idprodus_gratar = mysqli_real_escape_string($conn, $_POST["idprodus_gratar"]);

    if (!is_numeric($idprodus_gratar) or !is_numeric($cantitate_produs_gratar)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_gratar = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_gratar'");
    if (mysqli_num_rows($verificare_produs_gratar) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs5 = "SELECT * FROM cos WHERE idprodus = '$idprodus_gratar' AND idcont = '$idCont' ";
    $rezultat_verificare5 = mysqli_query($conn, $verificare_produs5);

    if (mysqli_num_rows($rezultat_verificare5) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_gratar' WHERE idprodus = '$idprodus_gratar'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs5 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_gratar','$cantitate_produs_gratar')";
        mysqli_query($conn, $inserare_produs5);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_salate"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_salate = mysqli_real_escape_string($conn, $_POST["cantitate_produs_salate"]);
    $idprodus_salate = mysqli_real_escape_string($conn, $_POST["idprodus_salate"]);

    if (!is_numeric($idprodus_salate) or !is_numeric($cantitate_produs_salate)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_salate = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_salate'");
    if (mysqli_num_rows($verificare_produs_salate) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs6 = "SELECT * FROM cos WHERE idprodus = '$idprodus_salate' AND idcont = '$idCont' ";
    $rezultat_verificare6 = mysqli_query($conn, $verificare_produs6);

    if (mysqli_num_rows($rezultat_verificare6) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_salate' WHERE idprodus = '$idprodus_salate'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs6 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_salate','$cantitate_produs_salate')";
        mysqli_query($conn, $inserare_produs6);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_ciorbe"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_ciorbe = mysqli_real_escape_string($conn, $_POST["cantitate_produs_ciorbe"]);
    $idprodus_ciorbe = mysqli_real_escape_string($conn, $_POST["idprodus_ciorbe"]);

    if (!is_numeric($idprodus_ciorbe) or !is_numeric($cantitate_produs_ciorbe)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_ciorbe = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_ciorbe'");
    if (mysqli_num_rows($verificare_produs_ciorbe) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs7 = "SELECT * FROM cos WHERE idprodus = '$idprodus_ciorbe' AND idcont = '$idCont' ";
    $rezultat_verificare7 = mysqli_query($conn, $verificare_produs7);

    if (mysqli_num_rows($rezultat_verificare7) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_ciorbe' WHERE idprodus = '$idprodus_ciorbe'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs7 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_ciorbe','$cantitate_produs_ciorbe')";
        mysqli_query($conn, $inserare_produs7);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_garnituri"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_garnituri = mysqli_real_escape_string($conn, $_POST["cantitate_produs_garnituri"]);
    $idprodus_garnituri = mysqli_real_escape_string($conn, $_POST["idprodus_garnituri"]);

    if (!is_numeric($idprodus_garnituri) or !is_numeric($cantitate_produs_garnituri)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_garnituri = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_garnituri'");
    if (mysqli_num_rows($verificare_produs_garnituri) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs8 = "SELECT * FROM cos WHERE idprodus = '$idprodus_garnituri' AND idcont = '$idCont' ";
    $rezultat_verificare8 = mysqli_query($conn, $verificare_produs8);

    if (mysqli_num_rows($rezultat_verificare8) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_garnituri' WHERE idprodus = '$idprodus_garnituri'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs8 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_garnituri','$cantitate_produs_garnituri')";
        mysqli_query($conn, $inserare_produs8);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_deserturi"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_deserturi = mysqli_real_escape_string($conn, $_POST["cantitate_produs_deserturi"]);
    $idprodus_deserturi = mysqli_real_escape_string($conn, $_POST["idprodus_deserturi"]);

    if (!is_numeric($idprodus_deserturi) or !is_numeric($cantitate_produs_deserturi)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_deserturi = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_deserturi'");
    if (mysqli_num_rows($verificare_produs_deserturi) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs9 = "SELECT * FROM cos WHERE idprodus = '$idprodus_deserturi' AND idcont = '$idCont' ";
    $rezultat_verificare9 = mysqli_query($conn, $verificare_produs9);

    if (mysqli_num_rows($rezultat_verificare9) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_deserturi' WHERE idprodus = '$idprodus_deserturi'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs9 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_deserturi','$cantitate_produs_deserturi')";
        mysqli_query($conn, $inserare_produs9);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_bauturi"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_bauturi = mysqli_real_escape_string($conn, $_POST["cantitate_produs_bauturi"]);
    $idprodus_bauturi = mysqli_real_escape_string($conn, $_POST["idprodus_bauturi"]);

    if (!is_numeric($idprodus_bauturi) or !is_numeric($cantitate_produs_bauturi)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_bauturi = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_bauturi'");
    if (mysqli_num_rows($verificare_produs_bauturi) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs10 = "SELECT * FROM cos WHERE idprodus = '$idprodus_bauturi' AND idcont = '$idCont' ";
    $rezultat_verificare10 = mysqli_query($conn, $verificare_produs10);

    if (mysqli_num_rows($rezultat_verificare10) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_bauturi' WHERE idprodus = '$idprodus_bauturi'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs10 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_bauturi','$cantitate_produs_bauturi')";
        mysqli_query($conn, $inserare_produs10);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}

if (isset($_POST["adauga_in_cos_oferta"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: delivery.php");
        exit();
    }
    $cantitate_produs_oferta = mysqli_real_escape_string($conn, $_POST["cantitate_produs_oferta"]);
    $idprodus_oferta = mysqli_real_escape_string($conn, $_POST["idprodus_oferta"]);

    if (!is_numeric($idprodus_oferta) or !is_numeric($cantitate_produs_oferta)) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs_oferta = mysqli_query($conn, "SELECT idprodus FROM produse WHERE idprodus = '$idprodus_oferta'");
    if (mysqli_num_rows($verificare_produs_oferta) === 0) {
        $_SESSION["delivery_interval_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $verificare_produs11 = "SELECT * FROM cos WHERE idprodus = '$idprodus_oferta' AND idcont = '$idCont' ";
    $rezultat_verificare11 = mysqli_query($conn, $verificare_produs11);

    if (mysqli_num_rows($rezultat_verificare11) > 0) {
        mysqli_query($conn, "UPDATE cos SET cantitate = cantitate+'$cantitate_produs_oferta' WHERE idprodus = '$idprodus_oferta'");
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        $inserare_produs11 = "INSERT INTO cos (idcont, idprodus, cantitate)
        VALUES ('$idCont','$idprodus_oferta','$cantitate_produs_oferta')";
        mysqli_query($conn, $inserare_produs11);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost adăugat în coș!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="delivery.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <title>Delivery</title>
</head>

<body>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#preloader').delay(100).queue(function() {
                    $(this).remove();
                });
            }, 700);
        });
    </script>

    <script>
        function showTopBar() {
            let topbar = document.getElementById("topbar");
            if (topbar.style.width === '' || topbar.style.width === "0vh") {
                topbar.style.width = '100%';
            } else {
                topbar.style.width = '0vh';
            }
        }
        window.addEventListener('resize', function() {
            if (window.innerWidth > 979) {
                topbar.style.width = '100%';
            } else {
                topbar.style.width = '';
            }
        });
    </script>

    <div class="container-preloader" id="preloader">
        <div class="animation-preloader">
            <div class="txt-loading">
                <img src="imagini/old-but-gold-high-resolution-logo-transparent.png">
            </div>
            <i class="fa fa-spinner fa-spin" style="font-size:30vh;color:#009473;"></i>
        </div>
        <div class="loader-section"></div>
    </div>

    <?php
    if (isset($_SESSION['add_to_cart_alert'])) {
    ?>
        <div class="success-message2" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i>
            <?php echo $_SESSION['add_to_cart_alert'] ?>
        </div>
    <?php
        unset($_SESSION['add_to_cart_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['min_price_alert'])) {
    ?>
        <div class="success-message-container" id="success-payment">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['min_price_alert']; ?></div>
        </div>
    <?php
        unset($_SESSION['min_price_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['delivery_interval_alert'])) {
    ?>
        <div class="success-message-container" id="success-payment">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['delivery_interval_alert']; ?></div>
        </div>
    <?php
        unset($_SESSION['delivery_interval_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['eroare_inserare'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['eroare_inserare'] ?></div>
        </div>

    <?php
        unset($_SESSION['eroare_inserare']);
    }
    ?>


    <?php
    if (isset($_SESSION['tip'])) {
        if ($_SESSION["tip"] == "admin") {
        } else if ($_SESSION["tip"] == "client") {
        }
    } else {
    ?>
        <div class="forumBackContent" id="loginForum">
            <center id="center">
                <form method="post" id="login" class="login-forum">
                    <div style="display: flex;justify-content: space-between;align-items:normal;">
                        <h1 style="color: rgb(219, 241, 219);font-size: 4vh;">Intră in cont</h1>
                        <span class="close">&times;</span>
                    </div>
                    <div>
                        <label class="label1" for="emaillogin">Email</label>
                        <input class="input1" type="emaillogin" id="emaillogin" name="emaillogin" placeholder="Introdu adresa de email..." required>
                    </div>

                    <div>
                        <label class="label1" for="password">Parola</label>
                        <input class="input1" type="password" id="password" name="password" placeholder="Introdu parola..." required>
                    </div>
                    <center>
                        <button class="button-login" type="submit" name="submitLogIn">Logare</button>
                    </center>
                    <div class="create-forgot">
                        <a href="creeaza-cont.php"><u>Creează cont</u></a>
                        <a href="resetare-parola.php"><u>Ai uitat parola?</u></a>
                    </div>
                </form>
            </center>
        </div>
    <?php
    }
    ?>


    <?php
    if (isset($_SESSION["tip"])) {
        if ($_SESSION["tip"] == "admin") {
    ?>
            <div class="forumBackContent2" id="addForum">
                <center id="center2">
                    <form method="post" id="addForm" class="add-form" enctype="multipart/form-data">
                        <div style="display: flex;justify-content: space-between;align-items:normal;">
                            <h1 style="color: rgb(219, 241, 219);font-size: 4vh;">Detalii Produs</h1>
                            <span class="close2">&times;</span>
                        </div>

                        <div>
                            <label class="label1">Nume Produs</label>
                            <input class="input1" name="nume-produs" type="text" placeholder="Introdu titlul produsului..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>

                        <div>
                            <label class="label1">Descriere</label>
                            <textarea rows="2" cols="50" class="input1" name="descriere-produs" maxlength="80" placeholder="Introdu descrierea produsului(maxim 80 de caractere)..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')"></textarea>
                        </div>

                        <div>
                            <label class="label1">Preț</label>
                            <input class="input1" name="pret-produs" type="number" placeholder="Introdu prețul produsului..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>

                        <div style="display:flex;justify-content:left;align-items:center;gap:2vh;margin-bottom:2.5vh;">
                            <label class="label1">Categorie</label>
                            <select name="categorie-produs" id="" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                                <option name="categorie-produs" value="micdejun">Mic Dejun</option>
                                <option name="categorie-produs" value="pizza">Pizza</option>
                                <option name="categorie-produs" value="paste">Paste</option>
                                <option name="categorie-produs" value="burger">Burger</option>
                                <option name="categorie-produs" value="gratar">Grătar</option>
                                <option name="categorie-produs" value="salate">Salate</option>
                                <option name="categorie-produs" value="ciorbe">Ciorbe</option>
                                <option name="categorie-produs" value="garnituri">Garnituri</option>
                                <option name="categorie-produs" value="deserturi">Deserturi</option>
                                <option name="categorie-produs" value="bauturi">Băuturi</option>
                                <option name="categorie-produs" value="oferta">Ofertă</option>
                            </select>
                        </div>

                        <div style="display:flex;flex-direction:row;align-items: center;gap:1vh;">
                            <label class="label1">Imagine produs</label>
                            <input class="input1" name="imagine-produs" style="width: 30vh;margin:0;padding:0;float:left;color: rgb(219, 241, 219);" type="file" accept="image/png, image/jpg, image/jpeg" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>
                        <center>
                            <div>
                                <button class="button-add" type="submit" name="submitAdd">Adaugă Produs</button>
                            </div>
                        </center>
                    </form>
                </center>
            </div>

    <?php
        }
    }
    ?>
    <button style="position:fixed;left:3vh;top:10vh;display:none;z-index:9999;background-color:transparent;border:none;" onclick="showTopBar()" class="menu_top_bar"><img src="imagini/icons/list.png" style="width:4vh;z-index:9;" alt=""></button>
    <div class="top-bar" id="topbar">
        <div class="logo-container">
            <img class="logo" src="imagini/old-but-gold-high-resolution-logo-transparent.png">
        </div>
        <div class="middle-navigation">
            <a href="index.php" class="middleanchor">
                <div class="round"><img class="middleIcons" src="imagini/icons/home.png"></div>
                <span>Acasă</span>
            </a>

            <div class="dropdown">
                <a href="meniu.php" class="middleanchor">
                    <div class="round"><img class="middleIcons" src="imagini/icons/menu.png"></div>
                    <button class="dropdownbtn">Meniu<span style="font-size:1vh;">▼</span></button>
                </a>
                <div class="dropdownContent" style="min-width: 25vh;">
                    <a href="meniu-mancare.php">Meniu mâncare</a>
                    <a href="meniu-bauturi.php" style="border-top:solid 0.2vh black">Meniu băuturi</a>
                </div>
            </div>

            <a class="middleanchor" href="delivery.php">
                <div class="round"><img class="middleIcons" src="imagini/icons/shopping-bag.png"></div>
                <span>Delivery</span>
            </a>

            <a class="middleanchor" href="rezervari.php">
                <div class="round"> <img class="middleIcons" src="imagini/icons/booking.png"></div>
                <span>Rezervare</span>
            </a>

            <div class="dropdown">
                <a class="middleanchor" href="evenimente.php">
                    <div class="round"><img class="middleIcons" src="imagini/icons/events.png"></div>
                    <button class="dropdownbtn">Evenimente<span style="font-size:1vh;">▼</span></button>
                </a>
                <div class="dropdownContent">
                    <a href="evenimente.php">Evenimente viitoare</a>
                    <a style="border-top:solid 0.2vh black" href="evenimente-incheiate.php">Evenimente încheiate</a>
                </div>
            </div>

            <a class="middleanchor" href="recenzii.php">
                <div class="round"><img class="middleIcons" src="imagini/icons/feedback.png"></div>
                <span>Recenzii</span>
            </a>

        </div>
        <?php
        if (isset($_SESSION['tip'])) {
            if ($_SESSION['tip'] == 'client') {
        ?>
                <div class="already-login-buttons" style="text-decoration: none;">
                    <a href="contul-meu.php" style="text-decoration:none;">
                        <div class="round"><img class="middleIcons" src="imagini/icons/user.png"></div>
                        <span>
                            <?php echo "Utilizator:" . " " . $numeClient . " " . $prenumeClient . " " ?>
                        </span>
                    </a>
                    <a href="logout.php" style="text-decoration: none;">
                        <div class="round"><img class="middleIcons" src="imagini/icons/leave.png"></div>
                        <span>Ieși din cont</span>
                    </a>
                </div>
            <?php
            } else if ($_SESSION['tip'] == 'admin') {
            ?>
                <div class="already-login-buttons">
                    <a href="contul-meu.php" style="text-decoration:none;">
                        <div class="round"><img class="middleIcons" src="imagini/icons/user.png"></div>
                        <?php echo "Admin:" . " " . $numeClient . " " . $prenumeClient . " " ?>
                    </a>
                    <a href="logout.php" style="text-decoration: none;">
                        <div class="round"><img class="middleIcons" src="imagini/icons/leave.png"></div>
                        <span>Ieși din cont</span>
                    </a>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="login-buttons">
                <a id="loginBtn">
                    <div class="round"><img class="middleIcons" src="imagini/icons/enter.png"></div>
                    <span>Intră în cont</span>
                </a>
                <a href="creeaza-cont.php" style="text-decoration: none;">
                    <div class="round"><img class="middleIcons" src="imagini/icons/create.png"></div>
                    <span>Creează cont</span>
                </a>
            </div>
        <?php
        }
        ?>
    </div>

    <?php
    if (isset($_SESSION['success-payment'])) {
    ?>
        <div class="success-message-container" id="success-payment">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success-payment'] ?></div>
        </div>
    <?php
        unset($_SESSION['success-payment']);
    }
    ?>

    <?php
    if (isset($_SESSION['success-payment-card-user'])) {
    ?>
        <div class="success-message-container" id="success-payment-card-user">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success-payment-card-user'] ?></div>
        </div>
    <?php
        unset($_SESSION['success-payment-card-user']);
    }
    ?>

    <?php
    if (isset($_SESSION['success-payment-card-admin'])) {
    ?>
        <div class="success-message-container" id="success-payment-card-admin">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success-payment-card-admin'] ?></div>
        </div>
    <?php
        unset($_SESSION['success-payment-card-admin']);
    }
    ?>


    <div class="mainContent">

        <center>
            <div class="sort-buttons">
                <?php
                if (isset($_SESSION["tip"])) {
                    if ($_SESSION["tip"] == "admin" || $_SESSION["tip"] == "client") {
                ?>
                        <a id="openCart" class="my-cart">
                            <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.1vh;height:2.1vh;"></div>
                            <span>Coșul tău</span>
                            <?php

                            $select_cantitate_client = "SELECT SUM(cantitate) AS cantitate_totala FROM cos WHERE idcont = '$idCont'";
                            $result_select_cantitate_client = mysqli_query($conn, $select_cantitate_client);
                            if ($result_select_cantitate_client) {
                                $row_qty = mysqli_fetch_assoc($result_select_cantitate_client);
                                $cantitate_totala = $row_qty["cantitate_totala"];
                                if ($cantitate_totala > 0) {
                            ?>
                                    <div style="position:absolute;left:4vh;top:0.3vh;color:white;padding:0.2vh 0.7vh;background-color:red;border-radius:100vh;font-size:1.3vh;">
                                        <?php echo $cantitate_totala; ?>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                        </a>
                <?php
                    }
                }
                ?>
                <a href="?sort=asc" class="sort">
                    <div class="round2"><img src="imagini/icons/up.png" style="width:1vh;height:1vh;"></div>
                    <span>Sortează crescător</span>
                </a>
                <a href="?sort=desc" class="sort">
                    <div class="round2"><img src="imagini/icons/down.png" style="width:1vh;height:1vh;"></div>
                    <span>Sortează descrescător</span>
                </a>
                <?php
                if (isset($_SESSION["tip"])) {
                    if ($_SESSION["tip"] == "admin") {
                ?>
                        <a id="addButton" class="sort" onclick="addProduct()">
                            <div class="round2"><img src="imagini/icons/add.png" style="width:1vh;height:1vh;"></div>
                            <span>Adaugă produs</span>
                        </a>
                <?php
                    }
                }
                ?>
            </div>
        </center>



        <div class="absolute-content">
            <div class="menu-tabs-container">
                <div class="tabs">
                    <a class="menulink" onclick="openMenu(event, 'micDejun')" id="micDejunButon">
                        <img src="imagini/bacon.png">
                        <span>Mic Dejun</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'pizza')" id="pizzaButon">
                        <img src="imagini/pizza.png">
                        <span>Pizza</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'paste')" id="pasteButon">
                        <img src="imagini/spaghetti.png">
                        <span>Paste</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'burger')" id="burgerButon">
                        <img src="imagini/burger.png">
                        <span>Burger</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'gratar')" id="gratarButon">
                        <img src="imagini/barbecue.png">
                        <span>Grătar</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'salate')" id="salateButon">
                        <img src="imagini/salad.png">
                        <span>Salate</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'ciorbe')" id="ciorbeButon">
                        <img src="imagini/soup.png">
                        <span>Ciorbe</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'garnituri')" id="garnituriButon">
                        <img src="imagini/fried-potatoes.png">
                        <span>Garnituri</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'deserturi')" id="deserturiButon">
                        <img src="imagini/cupcake.png">
                        <span>Deserturi</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'bauturi')" id="bauturiButon">
                        <img src="imagini/can.png">
                        <span>Băuturi</span>
                    </a>
                    <a class="menulink" onclick="openMenu(event, 'special')" id="specialButon">
                        <img src="imagini/discount.png">
                        <span>Oferte</span>
                    </a>
                </div>
            </div>

            <?php
            $sort = "";
            if (isset($_GET["sort"])) {
                $sort = mysqli_real_escape_string($conn,$_GET["sort"]);
                if($sort != "asc" and $sort != "desc" ){
                    $sort = "asc";
                }
            }
            ?>

            <div class="tabcontent" id="special">
                <?php

                $select11 = "SELECT * FROM produse where categorie = 'oferta' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort, numeprodus ASC";
                $result11 = mysqli_query($conn, $select11);

                if (mysqli_num_rows($result11) > 0) {
                    while ($row = mysqli_fetch_assoc($result11)) {
                        $idprodus11 = $row["idprodus"];
                        $numeprodus11 = $row["numeprodus"];
                        $descriereprodus11 = $row["descriereprodus"];
                        $pretprodus11 = $row["pret"];
                        $categorie11 = $row["categorie"];
                        $imagineprodus11 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus11 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus11 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus11 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus11 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus11 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus11 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_oferta" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_oferta" value="<?php echo $idprodus11; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_oferta">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div style="font-size: 3.5vh;color:rgb(219, 241, 219);">
                        Momentan, nu există oferte disponibile!
                    </div>
                <?php
                }
                ?>

            </div>

            <div class="tabcontent" id="micDejun">
                <p style="position:absolute;color:white;left:16vh;top:0.9vh;margin:0;font-size:2vh;color:rgb(219, 241, 219);" class="micdejunanunt">Produsele din categoria "Mic Dejun" se pot comanda doar între orele 10:00 - 13:00!</p>
                <?php

                $select = "SELECT * FROM produse where categorie = 'micdejun' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort, numeprodus ASC";
                $result = mysqli_query($conn, $select);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $idprodus = $row["idprodus"];
                        $numeprodus = $row["numeprodus"];
                        $descriereprodus = $row["descriereprodus"];
                        $pretprodus = $row["pret"];
                        $categorie = $row["categorie"];
                        $imagineprodus = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_micdejun" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_micdejun" value="<?php echo $idprodus; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_micdejun" id="add_to_cart_micdejun">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }



                ?>

            </div>

            <div class="tabcontent" id="pizza">

                <?php
                $select2 = "SELECT * FROM produse where categorie = 'pizza' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result2 = mysqli_query($conn, $select2);

                if ($result2) {
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $idprodus2 = $row["idprodus"];
                        $numeprodus2 = $row["numeprodus"];
                        $descriereprodus2 = $row["descriereprodus"];
                        $pretprodus2 = $row["pret"];
                        $categorie2 = $row["categorie"];
                        $imagineprodus2 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus2 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus2 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus2 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus2 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus2 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus2 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_pizza" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_pizza" value="<?php echo $idprodus2; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_pizza">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>

            </div>

            <div class="tabcontent" id="paste">
                <?php
                $select3 = "SELECT * FROM produse where categorie = 'paste' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result3 = mysqli_query($conn, $select3);

                if ($result3) {
                    while ($row = mysqli_fetch_assoc($result3)) {
                        $idprodus3 = $row["idprodus"];
                        $numeprodus3 = $row["numeprodus"];
                        $descriereprodus3 = $row["descriereprodus"];
                        $pretprodus3 = $row["pret"];
                        $categorie3 = $row["categorie"];
                        $imagineprodus3 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus3 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus3 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus3 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus3 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus3 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus3 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_paste" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_paste" value="<?php echo $idprodus3; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_paste">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                            <?php
                                }
                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>

                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="burger">
                <?php
                $select4 = "SELECT * FROM produse where categorie = 'burger' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result4 = mysqli_query($conn, $select4);

                if ($result4) {
                    while ($row = mysqli_fetch_assoc($result4)) {
                        $idprodus4 = $row["idprodus"];
                        $numeprodus4 = $row["numeprodus"];
                        $descriereprodus4 = $row["descriereprodus"];
                        $pretprodus4 = $row["pret"];
                        $categorie4 = $row["categorie"];
                        $imagineprodus4 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus4 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus4 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus4 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus4 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus4 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus4 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_burger" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_burger" value="<?php echo $idprodus4; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_burger">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }
                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="gratar">
                <?php

                $select5 = "SELECT * FROM produse where categorie = 'gratar' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result5 = mysqli_query($conn, $select5);

                if ($result5) {
                    while ($row = mysqli_fetch_assoc($result5)) {
                        $idprodus5 = $row["idprodus"];
                        $numeprodus5 = $row["numeprodus"];
                        $descriereprodus5 = $row["descriereprodus"];
                        $pretprodus5 = $row["pret"];
                        $categorie5 = $row["categorie"];
                        $imagineprodus5 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus5 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus5 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus5 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus5 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus5 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus5 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_gratar" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_gratar" value="<?php echo $idprodus5; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_gratar">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }



                ?>

            </div>


            <div class="tabcontent" id="salate">
                <?php

                $select6 = "SELECT * FROM produse where categorie = 'salate' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result6 = mysqli_query($conn, $select6);

                if ($result6) {
                    while ($row = mysqli_fetch_assoc($result6)) {
                        $idprodus6 = $row["idprodus"];
                        $numeprodus6 = $row["numeprodus"];
                        $descriereprodus6 = $row["descriereprodus"];
                        $pretprodus6 = $row["pret"];
                        $categorie6 = $row["categorie"];
                        $imagineprodus6 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus6 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus6 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus6 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus6 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus6 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus6 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_salate" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_salate" value="<?php echo $idprodus6; ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_salate">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="ciorbe">
                <?php

                $select7 = "SELECT * FROM produse where categorie = 'ciorbe' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result7 = mysqli_query($conn, $select7);

                if ($result7) {
                    while ($row = mysqli_fetch_assoc($result7)) {
                        $idprodus7 = $row["idprodus"];
                        $numeprodus7 = $row["numeprodus"];
                        $descriereprodus7 = $row["descriereprodus"];
                        $pretprodus7 = $row["pret"];
                        $categorie7 = $row["categorie"];
                        $imagineprodus7 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus7 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus7 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus7 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus7 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus7 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus7 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_ciorbe" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_ciorbe" value="<?php echo $idprodus7 ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_ciorbe">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="garnituri">
                <?php

                $select8 = "SELECT * FROM produse where categorie = 'garnituri' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result8 = mysqli_query($conn, $select8);

                if ($result8) {
                    while ($row = mysqli_fetch_assoc($result8)) {
                        $idprodus8 = $row["idprodus"];
                        $numeprodus8 = $row["numeprodus"];
                        $descriereprodus8 = $row["descriereprodus"];
                        $pretprodus8 = $row["pret"];
                        $categorie8 = $row["categorie"];
                        $imagineprodus8 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus8 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus8 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus8 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus8 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus8 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus8 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_garnituri" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_garnituri" value="<?php echo $idprodus8 ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_garnituri">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>

            </div>

            <div class="tabcontent" id="deserturi">
                <?php

                $select9 = "SELECT * FROM produse where categorie = 'deserturi' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result9 = mysqli_query($conn, $select9);

                if ($result9) {
                    while ($row9 = mysqli_fetch_assoc($result9)) {
                        $idprodus9 = $row9["idprodus"];
                        $numeprodus9 = $row9["numeprodus"];
                        $descriereprodus9 = $row9["descriereprodus"];
                        $pretprodus9 = $row9["pret"];
                        $categorie9 = $row9["categorie"];
                        $imagineprodus9 = $row9["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus9 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus9 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus9 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus9 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus9 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus9 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_deserturi" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_deserturi" value="<?php echo $idprodus9 ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_deserturi">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="bauturi">
                <?php

                $select10 = "SELECT * FROM produse where categorie = 'bauturi' ORDER BY CAST(pret AS DECIMAL(10, 2)) $sort,numeprodus ASC";
                $result10 = mysqli_query($conn, $select10);

                if ($result10) {
                    while ($row = mysqli_fetch_assoc($result10)) {
                        $idprodus10 = $row["idprodus"];
                        $numeprodus10 = $row["numeprodus"];
                        $descriereprodus10 = $row["descriereprodus"];
                        $pretprodus10 = $row["pret"];
                        $categorie10 = $row["categorie"];
                        $imagineprodus10 = $row["imagine"];
                ?>

                        <div class="product-container">
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="admin-edit-delete-buttons">
                                        <a href="edit-produs.php?update-produs=<?php echo $idprodus10 ?>">
                                            <img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                        <a onclick="deleteConfirmation(<?php echo $idprodus10 ?>)" style="background-color:red;cursor:pointer;">
                                            <img src="imagini/icons/bin.png" style="width:2vh;height:2vh;" alt="">
                                        </a>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <div class="food-img-zoom">
                                <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus10 ?>">
                            </div>

                            <div class="product-name">
                                <p>
                                    <?php echo $numeprodus10 ?>
                                </p>
                            </div>

                            <div class="product-description">
                                <p>
                                    <?php echo $descriereprodus10 ?>
                                </p>
                            </div>

                            <div class="product-price">
                                <p>
                                    <?php echo $pretprodus10 ?><span> RON</span>
                                </p>
                            </div>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client" || $_SESSION["tip"] == "admin") {
                            ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <input type="number" min="1" name="cantitate_produs_bauturi" value="1" class="cantitate" max="20" required>
                                        <input type="hidden" name="idprodus_bauturi" value="<?php echo $idprodus10 ?>">
                                        <div class="product-add-to-card">
                                            <button type="submit" name="adauga_in_cos_bauturi">
                                                <div class="round3"><img src="imagini/icons/add-item.png" style="width:2.5vh;height:2.5vh;">
                                                </div>
                                                <span>Adaugă în coș</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php
                                }

                                ?>


                            <?php

                            } else {
                                echo '<div class="product-add-to-card"><p>Intră în cont pentru a putea comanda</p></div>';
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

        </div>

        <div class="bottom-bar">
            <div class="contact">
                <p style="font-size: 4vh;"> Contact </p>
                <div class="location">
                    <img src="imagini/location.png">
                    <p>Timpuri Noi Square</p>
                </div>
                <div class="location">
                    <img src="imagini/email.png">
                    <p>oldbutgold@contact.ro</p>
                </div>
                <div class="location">
                    <img src="imagini/call.png">
                    <p>0711223344</p>
                </div>
            </div>

            <div class="program">
                <p style="font-size:4vh;">Program</p>
                <p>Luni-Joi - 10:30 - 00:00</p>
                <p>Vineri-Sâmbătă - 10:30 - 03:00</p>
                <p>Duminică - 10:30 - 23:00</p>
            </div>

            <div class="social-media">
                <p style="font-size: 4vh;">Social Media</p>
                <div class="sm">
                    <img src="imagini/tiktok.png" onclick="hrefSite('https://www.tiktok.com/en?lang=en')">
                    <p>TikTok</p>
                </div>
                <div class="sm">
                    <img src="imagini/facebook.png" onclick="hrefSite('https://www.facebook.com/')">
                    <p>Facebook</p>
                </div>
                <div class="sm">
                    <img src="imagini/instagram.png" onclick="hrefSite('https://www.instagram.com/')">
                    <p>Instagram</p>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p><span>&#169;</span>Copyright Old But Gold. All rights reserved</p>
        </div>
    </div>

    <?php

    if (isset($_SESSION["tip"])) {
        if ($_SESSION["tip"] == "admin" || $_SESSION["tip"] == "client") {
    ?>
            <div class="forumBackContent3" id="myCart">
                <div class="cart-content">
                    <span class="close3" id="close3">&times;</span>
                    <?php
                    $select11 = "SELECT produse.numeprodus, produse.pret ,produse.descriereprodus, produse.imagine,cos.cantitate,cos.idcos from conturi
            INNER JOIN cos USING (idcont) INNER JOIN produse USING (idprodus) where idcont = '$idCont'";
                    $result11 = mysqli_query($conn, $select11);
                    if (mysqli_num_rows($result11) > 0) {
                        $total_price = 0;
                        if ($result11) {
                            while ($row2 = mysqli_fetch_assoc($result11)) {
                                $numeprodus_cos = $row2["numeprodus"];
                                $descriereprodus_cos = $row2["descriereprodus"];
                                $cantitateprodus_cos = $row2["cantitate"];
                                $pretprodus_cos = $row2["pret"] * $cantitateprodus_cos;
                                $imagineprodus_cos = $row2["imagine"];
                                $idcos = $row2["idcos"];

                    ?>
                                <div class="product-in-cart-container">
                                    <img src="imagini/delivery/<?php echo $imagineprodus_cos ?>" class="in-cart-image" alt="">
                                    <div class="in-cart-product-name">
                                        <p>
                                            <?php echo $numeprodus_cos; ?>
                                        </p>
                                    </div>
                                    <div class="in-cart-product-desc">
                                        <p>
                                            <?php echo $descriereprodus_cos; ?>
                                        </p>
                                    </div>
                                    <div class="in-cart-product-qty">
                                        <form action="" method="post" enctype="multipart/form-data" style="display:flex;justify-content:center;align-items:center;gap:1vh;">
                                            <button name="delete_produs_cos" type="submit" style="background-color:red;"><img src="imagini/icons/bin.png" style="width:2vh;height:2vh;"></button>
                                            <input type="hidden" name="idcos_delete" value="<?php echo $idcos; ?>">
                                        </form>
                                        <form action="" method="post" enctype="multipart/form-data" style="display:flex;justify-content:center;align-items:center;gap:1vh;">
                                            <button type="submit" name="submit_update_qty"><img src="imagini/icons/edit.png" style="width:2vh;height:2vh;" alt=""></button>
                                            <input type="number" name="update_qty" min="1" max="20" required value="<?php echo $cantitateprodus_cos; ?>">
                                            <input type="hidden" name="idcos" value="<?php echo $idcos; ?>">
                                        </form>
                                        <p style="font-size:2.5vh">
                                            <?php echo $pretprodus_cos; ?> <span> RON</span>
                                        </p>
                                    </div>
                                </div>
                            <?php
                                $total_price += $pretprodus_cos;
                                $_SESSION["total_price"] = $total_price;
                            }
                            ?>
                            <div style="position:absolute;top:3vh;display:flex;justify-content:center;align-items:center;flex-direction:column">
                                <a href="checkout.php" class="finish-order">
                                    <img src="imagini/icons/checkout.png" style="width:2.5vh;height:2.5vh;">
                                    <span>Finalizează Comanda</span>
                                </a>
                                <p class="final-price">Total:
                                    <?php echo $total_price; ?> RON
                                </p>
                            </div>
                            <div style="display:flex;justify-content:center;align-items:center;flex-direction:column">
                                <form action="" method="post" enctype="multipart/form-data" style="display:flex;justify-content:center;align-items:center;gap:1vh;">
                                    <button name="delete_toate_produse_cos" type="submit" style="gap:0.5vh;display:flex;justify-content:center;align-items:center;background-color:red;font-size: 2.5vh;border:none;color:rgb(221, 232, 221);padding:0.6vh 0.6vh;border-radius:0.7vh;cursor:pointer;"><img src="imagini/icons/bin.png" style="width:2.3vh;height:2.3vh;">Șterge tot</button>
                                </form>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="empty-cart">
                            <p style="margin:0;text-align:center;">Coșul tău de cumpărături este gol!</p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
    <?php
        }
    }
    ?>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-payment').remove();
            }, 5000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success').remove();
            }, 3000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-payment-card-user').remove();
            }, 5000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-payment-card-admin').remove();
            }, 5000);
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success_login_alert').remove();
            }, 2000);
        });
    </script>

    <script>
        let dezactivareButoane = document.querySelectorAll('[id="add_to_cart_micdejun"]');
        let dataCurenta = new Date();
        let oraCurenta = dataCurenta.getHours();

        dezactivareButoane.forEach(function(button) {
            if (oraCurenta >= 10 && oraCurenta < 13) {
                button.disabled = false;
            } else {
                button.disabled = true;
                button.style.cursor = "not-allowed";
                button.addEventListener("mouseover", function() {
                    button.style.backgroundColor = "#001220";
                })
            }
        })
    </script>

    <script>
        function deleteConfirmation(id) {

            let confirmation = confirm("Ești sigur ca vrei să ștergi acest produs?")


            if (confirmation) {
                window.location.href = "delete-produs.php?delete_produs_delivery=" + id;
            }
        }
    </script>

    <script>
        function addProduct() {
            let addForum = document.getElementById("addForum");
            let addButton = document.getElementById("addButton");
            let close2 = document.getElementsByClassName("close2")[0];
            let center2 = document.getElementById("center2");

            addForum.style.display = "block";


            close2.onclick = function() {
                addForum.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target === addForum || event.target === center2) {
                    addForum.style.display = "none";
                }
            }
        }
    </script>

    <script>
        let openCart = document.getElementById("openCart");
        let myCart = document.getElementById("myCart");
        let close2 = document.getElementsByClassName("close3")[0];



        openCart.onclick = function() {
            myCart.style.display = "flex";
        }

        close3.onclick = function() {
            myCart.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === myCart) {
                myCart.style.display = "none";
            }
        }
    </script>


    <script>
        function openMenu(evt, menuName) {
            let i, tabcontent, menuLink;

            tabcontent = document.getElementsByClassName("tabcontent");

            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            menuLink = document.getElementsByClassName("menulink");

            for (i = 0; i < menuLink.length; i++) {
                menuLink[i].className = menuLink[i].className.replace(" active", "");
            }
            document.getElementById(menuName).style.display = "grid";
            evt.currentTarget.className += " active";

            sessionStorage.setItem("lastTab", menuName);
        }

        var lastTab = sessionStorage.getItem("lastTab");

        if (!lastTab) {
            document.getElementById("micDejunButon").click();
        } else {
            document.getElementById(lastTab + "Buton").click();
        }

        function hrefSite(url) {
            window.open(url, '_blank');
        }

        let loginForum = document.getElementById("loginForum");
        let loginBtn = document.getElementById("loginBtn");
        let close = document.getElementsByClassName("close")[0];
        let center = document.getElementById("center");

        loginBtn.onclick = function() {
            loginForum.style.display = "block";
        }

        close.onclick = function() {
            loginForum.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === loginForum || event.target === center) {
                loginForum.style.display = "none";
            }
        }
    </script>

</body>

</html>