<?php
include("sessionstart.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin" or $_SESSION["tip"] == "client") {
        $select_comanda_temporara_client0 = "SELECT * FROM comenzi_temporare WHERE idcont = '$idCont'";
        $result_select_comanda_temporara_client0 = mysqli_query($conn, $select_comanda_temporara_client0);
        if ($result_select_comanda_temporara_client0) {
            $row = mysqli_fetch_assoc($result_select_comanda_temporara_client0);
            $produse_comanda_temporara0 = $row["produse"];
            $total_comanda_temporara0 = $row["total_comanda"];
            $nume_destinatar_comanda_temporara0 = $row["nume_destinatar"];
            $prenume_destinatar_comanda_temporara0 = $row["prenume_destinatar"];
            $email_destinatar_comanda_temporara0 = $row["email_destinatar"];
            $telefon_destinatar_comanda_temporara0 = $row["telefon_destinatar"];
            $adresa_destinatar_comanda_temporara0 = $row["adresa_destinatar"];
            $sector_comanda_temporara0 = $row["sector"];
            $metoda_plata_comanda_temporara0 = $row["metoda_plata"];
            $status_comanda_temporara0 = $row["status"];
            $cod_comanda_comanda_temporara0 = $row["cod_comanda"];
            date_default_timezone_set('Europe/Bucharest');
            $data_comanda_finala0 = date('Y-m-d H:i:s');

            if (empty($email_destinatar_comanda_temporara0)) {
                header('Location: delivery.php');
            } else {
                mysqli_autocommit($conn, FALSE);
                $flag_validation_card_payment = true;
                $insert_comanda_finala0 = "INSERT INTO comenzi (idcont, produse, total_comanda, nume_destinatar, prenume_destinatar, email_destinatar, telefon_destinatar, adresa_destinatar, sector, metoda_plata, data_comanda, status, cod_comanda) 
                VALUES ('$idCont','$produse_comanda_temporara0','$total_comanda_temporara0','$nume_destinatar_comanda_temporara0','$prenume_destinatar_comanda_temporara0','$email_destinatar_comanda_temporara0','$telefon_destinatar_comanda_temporara0','$adresa_destinatar_comanda_temporara0','$sector_comanda_temporara0','$metoda_plata_comanda_temporara0','$data_comanda_finala0', '$status_comanda_temporara0', '$cod_comanda_comanda_temporara0')";
                $result_insert_comanda_finala0 = mysqli_query($conn, $insert_comanda_finala0);
                if (!$result_insert_comanda_finala0) {
                    $flag_validation_card_payment = false;
                }
                $delete_comenzi_temporare = mysqli_query($conn, "DELETE FROM comenzi_temporare where idcont = '$idCont'");
                if (!$delete_comenzi_temporare) {
                    $flag_validation_card_payment = false;
                }
                $delete_cos =  mysqli_query($conn, "DELETE FROM cos where idcont = '$idCont'");
                if (!$delete_cos) {
                    $flag_validation_card_payment = false;
                }
                if ($flag_validation_card_payment) {
                    if (mysqli_commit($conn)) {
                        $_SESSION["success-payment-card-admin"] = "Comanda a fost finalizată. Verificați e-mailul pentru mai multe detalii! Vă mulțumim!";
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = '';
                        $mail->Password = '';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port = 465;

                        $mail->setFrom('george30012001@gmail.com', 'Old But Gold Restaurant');
                        $mail->addAddress($email_destinatar_comanda_temporara0, " " . $nume_destinatar_comanda_temporara0 . " " . $prenume_destinatar_comanda_temporara0 . " ");

                        $mail->isHTML(true);
                        $mail->Subject = 'Comanda - Old But Gold -' . " " . $cod_comanda_comanda_temporara0 . " ";

                        $mail->Body = '<center><div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Comanda dumneavoastra a fost plasata cu succes!</p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding:5vh 3vh;width:70vh;font-size:3.5vh;color:#001220;"><center><p style="margin:0;padding:0;">Comanda va fi livrata in 40-60 de minute, in functie de adresa livrarii!Urmariti pe site statusul comenzii.Cod comanda:<b>' . $cod_comanda_comanda_temporara0 . '</b></p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding-left:3vh;padding-right:3vh;padding-bottom:5vh;width:70vh;font-size:3.5vh;color:#001220;"><center><img src="cid:logo" style="width:15.5vh;height:15.5vh;"></center></div>
                <div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455.</p></center></div>
                </center>';
                        $mail->AltBody = 'Comanda dumneavoastra a fost plasata cu succes! Urmariti pe site statusul comenzii. Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455. ';
                        $mail->addEmbeddedImage('imagini/old-but-gold-high-resolution-logo-transparent.png', 'logo', 'old-but-gold-high-resolution-logo-transparent.png');
                        $mail->send();
                        echo "<script>window.location.href='delivery.php';</script>";
                    }
                } else {
                    $_SESSION['eroare_inserare'] = "A apărut o eroare!";
                    mysqli_rollback($conn);
                    echo "<script>window.location.href='delivery.php';</script>";
                    exit();
                }
            }
        }
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
}
