<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include('sessionstart.php');
if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
    } else if ($_SESSION["tip"] == "client") {
    }
} else {
    echo "<script>alert('Intră în cont pentru a putea comanda!');window.location.href='delivery.php'</script>";
    exit();
}
date_default_timezone_set('Europe/Bucharest');
$ora_actuala = date('H:i');

$ora_setata1 = "10:00";
$ora_setata2 = "13:00";

$ora_setata_livrare1 = "23:00";
$ora_setata_livrare2 = "10:00";

if ($ora_actuala >= $ora_setata_livrare1 || $ora_actuala < $ora_setata_livrare2) {
    $_SESSION["delivery_interval_alert"] = "Nu puteți plasa comenzi în intervalul 23:00-10:00! Vă mulțumim de înțelegere!";
    echo "<script>window.location.href='delivery.php'</script>";
    exit();
} else {
}

$cart_verify = mysqli_query($conn, "SELECT * FROM cos WHERE idcont = '$idCont'");
if (mysqli_num_rows($cart_verify) == 0) {
    echo "<script>alert('Nu aveți produse în coș');window.location.href='delivery.php';</script>";
}

$verifiy_comenzi_temporare = mysqli_query($conn, "SELECT * FROM comenzi_temporare WHERE idcont = '$idCont'");
if (mysqli_num_rows($verifiy_comenzi_temporare) !== 0) {
    mysqli_query($conn, "DELETE FROM comenzi_temporare where idcont = '$idCont' ");
}

if ($ora_actuala >= $ora_setata1 && $ora_actuala <= $ora_setata2) {
} else {
    mysqli_query($conn, "DELETE cos FROM cos INNER JOIN produse ON cos.idprodus = produse.idprodus WHERE idcont = '$idCont' AND categorie = 'micdejun' ");
}

$select_verify_min_price = "SELECT produse.numeprodus, produse.pret ,produse.descriereprodus, produse.imagine,cos.cantitate,cos.idcos from conturi
INNER JOIN cos USING (idcont) INNER JOIN produse USING (idprodus) where idcont = '$idCont'";
$result_select_verify_min_price = mysqli_query($conn, $select_verify_min_price);
if (mysqli_num_rows($result_select_verify_min_price) > 0) {
    $total_price_verify = 0;
    if ($result_select_verify_min_price) {
        while ($row = mysqli_fetch_assoc($result_select_verify_min_price)) {

            $in_cart_price = $row["pret"] * $row["cantitate"];
            $total_price_verify += $in_cart_price;
        }
    }
    if ($total_price_verify < 40) {
        $_SESSION["min_price_alert"] = "Pentru a putea efectua o comandă, coșul dvs. de cumpărături trebuie să valoreze minim 40 RON!";
        echo "<script>window.location.href='delivery.php';</script>";
        exit();
    }
}


if (isset($_POST["finalizeaza_comanda"])) {
    $nume_destinatar = mysqli_real_escape_string($conn, $_POST["nume_destinatar"]);
    $prenume_destinatar = mysqli_real_escape_string($conn, $_POST["prenume_destinatar"]);
    $email_destinatar = mysqli_real_escape_string($conn, $_POST["email_destinatar"]);
    $telefon_destinatar = mysqli_real_escape_string($conn, $_POST["telefon_destinatar"]);
    $adresa_destinatar = mysqli_real_escape_string($conn, $_POST["adresa_destinatar"]);
    $sector = mysqli_real_escape_string($conn, $_POST["sector"]);
    $metoda_plata = mysqli_real_escape_string($conn, $_POST["metoda_plata"]);
    $status = "Pregătire";
    $total = $_SESSION["total_price"];
    $produse_comanda = $_SESSION["produse_comanda_client"];
    date_default_timezone_set('Europe/Bucharest');
    $data_comanda = date('Y-m-d H:i:s');
    $cod_comanda = bin2hex(random_bytes(8));

    if (empty($nume_destinatar) or empty($prenume_destinatar) or empty($email_destinatar) or empty($telefon_destinatar) or empty($adresa_destinatar) or empty($sector) or empty($metoda_plata)) {
        $_SESSION['checkout_characters_limit_alert'] = "A apărut o eroare!";
        echo "<script>window.location.href='checkout.php'</script>";
        exit();
    }

    $sectoare = ["sector 1", "sector 2", "sector 3", "sector 4", "sector 5", "sector 6"];
    $metode_plata = ["cash", "card"];

    if (!in_array($sector, $sectoare)) {
        $_SESSION['checkout_characters_limit_alert'] = "A apărut o eroare!";
        echo "<script>window.location.href='checkout.php'</script>";
        exit();
    }

    if (!in_array($metoda_plata, $metode_plata)) {
        $_SESSION['checkout_characters_limit_alert'] = "A apărut o eroare!";
        echo "<script>window.location.href='checkout.php'</script>";
        exit();
    }

    if (!filter_var($email_destinatar, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["checkout_characters_limit_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='checkout.php';</script>";
        exit();
    }

    if (strlen($nume_destinatar) > 99 or strlen($prenume_destinatar) > 99 or strlen($email_destinatar) > 254 or strlen($telefon_destinatar) != 10 or strlen($adresa_destinatar) > 900) {
        $_SESSION['checkout_characters_limit_alert'] = "Ați depășit limita de caractere pentrul unul sau mai multe atribute!";
        echo "<script>window.location.href='checkout.php'</script>";
        exit();
    }

    if ($metoda_plata == "cash") {
        mysqli_autocommit($conn, FALSE);
        $validation_flag_one = true;
        $insert_comanda = mysqli_query($conn, "INSERT INTO comenzi (idcont, produse, total_comanda, nume_destinatar, prenume_destinatar, email_destinatar, telefon_destinatar, adresa_destinatar, sector, metoda_plata, data_comanda, status, cod_comanda) 
        VALUES ('$idCont','$produse_comanda','$total','$nume_destinatar','$prenume_destinatar','$email_destinatar','$telefon_destinatar','$adresa_destinatar','$sector','$metoda_plata','$data_comanda', '$status', '$cod_comanda')");
        if (!$insert_comanda) {
            $validation_flag_one = false;
        }
        if ($validation_flag_one) {
            if (mysqli_commit($conn)) {
                $_SESSION["success-payment"] = "Comanda a fost finalizată. Verificați e-mailul pentru mai multe detalii! Vă mulțumim!";
                $delete_cart = "DELETE FROM cos WHERE idcont = '$idCont'";
                mysqli_query($conn, $delete_cart);
                mysqli_commit($conn);
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '';
                $mail->Password = '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('george30012001@gmail.com', 'Old But Gold Restaurant');
                $mail->addAddress($email_destinatar, " " . $nume_destinatar . " " . $prenume_destinatar . " ");

                $mail->isHTML(true);
                $mail->Subject = 'Comanda - Old But Gold -' . " " . $cod_comanda . " ";

                $mail->Body = '<center><div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Comanda dumneavoastra a fost plasata cu succes!</p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding:5vh 3vh;width:70vh;font-size:3.5vh;color:#001220;"><center><p style="margin:0;padding:0;">Comanda va fi livrata in 40-60 de minute, in functie de adresa livrarii!Urmariti pe site statusul comenzii.Cod comanda:<b>' . $cod_comanda . '</b></p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding-left:3vh;padding-right:3vh;padding-bottom:5vh;width:70vh;font-size:3.5vh;color:#001220;"><center><img src="cid:logo" style="width:15.5vh;height:15.5vh;"></center></div>
                <div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455.</p></center></div>
                </center>';
                $mail->AltBody = 'Comanda dumneavoastra a fost plasata cu succes! Urmariti pe site statusul comenzii. Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455. ';
                $mail->addEmbeddedImage('imagini/old-but-gold-high-resolution-logo-transparent.png', 'logo', 'old-but-gold-high-resolution-logo-transparent.png');
                $mail->send();
                echo "<script>window.location.href='delivery.php';</script>";
            } else {
                mysqli_error($con);
                $_SESSION['eroare_inserare'] = "A apărut o eroare!";
                echo "<script>window.location.href='delivery.php';</script>";
                exit();
            }
        } else {
            $_SESSION['eroare_inserare'] = "A apărut o eroare!";
            mysqli_rollback($conn);
            echo "<script>window.location.href='delivery.php';</script>";
            exit();
        }
    } else if ($metoda_plata = "card") {
        $select_comenzi_temporare = mysqli_query($conn, "SELECT * FROM comenzi_temporare where idcont ='$idCont'");
        if (mysqli_num_rows($select_comenzi_temporare) > 0) {
            mysqli_query($conn, "DELETE FROM comenzi_temporare where idcont = '$idCont' ");
        }
        mysqli_autocommit($conn, FALSE);
        $validation_flag_two = true;
        $insert_comanda_temporara = mysqli_query($conn, "INSERT INTO comenzi_temporare (idcont, produse, total_comanda, nume_destinatar, prenume_destinatar, email_destinatar, telefon_destinatar, adresa_destinatar, sector, metoda_plata, status, cod_comanda) 
        VALUES ('$idCont','$produse_comanda','$total','$nume_destinatar','$prenume_destinatar','$email_destinatar','$telefon_destinatar','$adresa_destinatar','$sector','$metoda_plata', '$status', '$cod_comanda')");
        if (!$insert_comanda_temporara) {
            $validation_flag_two = false;
        }
        if ($validation_flag_two) {
            if (mysqli_commit($conn)) {
                header("Location: plata-stripe.php");
            } else {
                mysqli_error($con);
                $_SESSION['eroare_inserare'] = "A apărut o eroare!";
                echo "<script>window.location.href='delivery.php';</script>";
                exit();
            }
        } else {
            $_SESSION['eroare_inserare'] = "A apărut o eroare!";
            mysqli_rollback($conn);
            echo "<script>window.location.href='delivery.php';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" type="text/css" href="checkout.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <title>Checkout</title>
</head>

<body>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#preloader').delay(100).queue(function() {
                    $(this).remove();
                });
            }, 400);
        });
    </script>

    <?php
    if (isset($_SESSION['checkout_characters_limit_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['checkout_characters_limit_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['checkout_characters_limit_alert']);
    }
    ?>


    <div class="container-preloader" id="preloader">
        <div class="animation-preloader">
            <div class="txt-loading">
                <img src="imagini/old-but-gold-high-resolution-logo-transparent.png">
            </div>
            <i class="fa fa-spinner fa-spin" style="font-size:30vh;color:#009473;"></i>
        </div>
        <div class="loader-section"></div>
    </div>

    <div class="back-content">

        <section class="main-container">

            <div class="products-container">
                <?php
                $select_products_in_cart = "SELECT produse.numeprodus, produse.pret ,produse.descriereprodus, produse.imagine,cos.cantitate,cos.idcos from conturi
                  INNER JOIN cos USING (idcont) INNER JOIN produse USING (idprodus) where idcont = '$idCont'";
                $result_select_products_in_cart = mysqli_query($conn, $select_products_in_cart);
                if (mysqli_num_rows($result_select_products_in_cart) > 0) {
                    $total_price = 0;
                    if ($result_select_products_in_cart) {
                        while ($row = mysqli_fetch_assoc($result_select_products_in_cart)) {
                            $numeprodus_cos = $row["numeprodus"];
                            $descriereprodus_cos = $row["descriereprodus"];
                            $cantitateprodus_cos = $row["cantitate"];
                            $pretprodus_cos = $row["pret"] * $cantitateprodus_cos;
                            $imagineprodus_cos = $row["imagine"];
                            $idcos = $row["idcos"];
                            $total_price += $pretprodus_cos;
                            $produse_cos_client[] = $row["numeprodus"] . ' (' . 'x' . $row['cantitate'] . ') ';
                ?>
                            <div class="one-product-container">
                                <div style="flex:2;height:14vh;"><img src="imagini/delivery/<?php echo $imagineprodus_cos; ?>" alt=""></div>
                                <div style="flex:2;display:flex;justify-content:center;align-items:center;flex-direction:column;gap:0.2vh;">
                                    <div>
                                        <p style="text-align:center;margin:0;">
                                            <?php echo $numeprodus_cos; ?>
                                        </p>
                                    </div>
                                    <div>x
                                        <?php echo $cantitateprodus_cos; ?>
                                    </div>
                                </div>
                                <div style="flex:1;">
                                    <?php echo $pretprodus_cos; ?> RON
                                </div>
                            </div>


                        <?php
                        }
                        ?>
                        <div style="position:absolute;top:0.5vh;color:#001220;font-size:3vh;border:none;">Total:
                            <?php echo $total_price; ?> RON
                        </div>
                <?php
                        $produse_comanda_client = implode(', ', $produse_cos_client);
                        $_SESSION["produse_comanda_client"] = $produse_comanda_client;
                    }
                } else {
                    echo "Nu aveți produse in coșul de cumpărături!";
                }


                ?>
            </div>

            <div class="checkout-form-container">
                <form action="" method="post" enctype="multipart/form-data" class="checkout-form">
                    <div class="checkout-form-left-side">
                        <div style="font-size:3vh;">Datele livrării</div>
                        <div class="input-label">
                            <label for="nume"><i class="fa fa-user"></i> Nume</label>
                            <input type="text" id="nume" name="nume_destinatar" maxlength="80" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $numeClient; ?>">
                        </div>
                        <div class="input-label">
                            <label for="prenume"><i class="fa fa-user"></i> Prenume</label>
                            <input type="text" id="prenume" required name="prenume_destinatar" maxlength="80" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $_SESSION["prenume"]; ?>">
                        </div>
                        <div class="input-label">
                            <label for="numar"><i class="fa fa-phone"></i> Număr de telefon</label>
                            <input type="text" id="numar" required name="telefon_destinatar" maxlength="10" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $telefonCont; ?>">
                        </div>
                        <div class="input-label">
                            <label for="email"><i class="fa fa-envelope"></i> Email</label>
                            <input type="email" id="email" required name="email_destinatar" maxlength="95" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $emailCont; ?>">
                        </div>
                        <div class="input-label">
                            <label for="adresa"><i class="fa fa-address-card-o"></i> Adresă</label>
                            <input type="text" id="adresa" required name="adresa_destinatar" maxlength="790" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $adresaCont; ?>">
                        </div>
                        <div class="input-label">
                            <label for="localitate"><i class="fa fa-institution"></i> Localitate</label>
                            <input type="text" value="Bucuresti" id="localitate" readonly>
                        </div>
                        <div class="input-label">
                            <label for="sector"><i class="fa fa-institution"></i> Sector</label>
                            <select name="sector" id="sector">
                                <option name="sector" value="sector 1">Sector 1</option>
                                <option name="sector" value="sector 2">Sector 2</option>
                                <option name="sector" value="sector 3">Sector 3</option>
                                <option name="sector" value="sector 4">Sector 4</option>
                                <option name="sector" value="sector 5">Sector 5</option>
                                <option name="sector" value="sector 6">Sector 6</option>
                            </select>
                        </div>
                        <div class="input-label">
                            <label><i class="fa fa-credit-card-alt"></i> Metoda de plată</label>
                            <div style="display:flex;justify-content:left;align-items:left;gap:3vh;">
                                <div>
                                    <input style="width:3vh;height:2vh;" type="radio" id="metoda_cash" name="metoda_plata" id="cash" value="cash" " checked>
                                    <label>Cash</label>
                                </div>
                                <div>
                                    <input style=" width:3vh;height:2vh;" id="metoda_card" value="card" name="metoda_plata" type="radio" id="card" ">
                                    <label>Card</label>
                                </div>
                            </div>
                        </div>

                        <div style=" display:flex;justify-content:center;align-items:center;gap:2vh;">
                            <a href="delivery.php" class="finish-and-pay-button">Înapoi</a>
                            <button class="finish-and-pay-button" type="submit" name="finalizeaza_comanda" id="finish_button" onclick="procesare()">Finalizează</button>
                        </div>
                    </div>
                </form>
            </div>

        </section>

    </div>

    <div class="procesare" style="position: fixed;z-index:99999;width:100%;height:100%;background-color:black;display:none;background-color:#001220;justify-content:center;align-items:center;flex-direction:column;" id="procesare">
        <p style="color:rgb(219, 241, 219);font-size:10vh;">Procesare <i class="fa fa-spinner fa-spin"></i></p>
    </div>
</body>

<script>
    function procesare() {

        let nume = document.getElementById('nume').value;
        let prenume = document.getElementById('prenume').value;
        let email = document.getElementById('email').value.trim();
        let emailvalidare = document.getElementById('email');
        let numar = document.getElementById('numar').value;
        let adresa = document.getElementById('adresa').value;

        if (nume.trim() !== '' && prenume.trim() !== '' && email !== '' && emailvalidare.checkValidity() && numar.trim() !== '' && adresa.trim() !== '') {
            document.getElementById("procesare").style.display = 'flex';
        }
    }
</script>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#success').remove();
        }, 2500);
    });
</script>

</html>