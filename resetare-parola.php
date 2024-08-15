<?php

include 'sessionstart.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
        echo "<script>alert('Pentru a accesa această pagină trebuie să te deloghezi!');window.location.href='index.php'</script>";
        exit();
    } else if ($_SESSION["tip"] == "client") {
        echo "<script>alert('Pentru a accesa această pagină trebuie să te deloghezi!');window.location.href='index.php'</script>";
        exit();
    }
} else {
}

if (isset($_POST["trimite_email"])) {
    $email_trimitere_cod = mysqli_real_escape_string($conn, $_POST["email_trimitere_cod"]);

    $select_nume_prenume = mysqli_query($conn, "SELECT nume,prenume,email FROM conturi WHERE email = '$email_trimitere_cod'");
    if (mysqli_num_rows($select_nume_prenume) === 0) {
        $_SESSION["email_verifiy"] = "Nu există cont cu acest email!";
        echo "<script>window.location.href='resetare-parola.php'</script>";
        exit();
    }

    $cod_resetare_parola = bin2hex(random_bytes(12));

    $row = mysqli_fetch_assoc($select_nume_prenume);
    $nume_complet = $row["nume"] . " " . $row["prenume"];

    $result_update_cod_resetare = mysqli_query($conn, "UPDATE conturi SET cod_resetare = '$cod_resetare_parola' WHERE email = '$email_trimitere_cod'");
    if ($result_update_cod_resetare) {
        $_SESSION["email_sent_confirmation"] = "Ați primit pe email un cod de resetare a parolei!";
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('george30012001@gmail.com', 'Old But Gold Restaurant');
        $mail->addAddress($email_trimitere_cod, " " . $nume_complet . " ");

        $mail->isHTML(true);
        $mail->Subject = 'Cod resetare parola - Old But Gold -' . " ";

        $mail->Body = '<p style="margin:0;font-size:30px">A fost trimisa o cerere de restare a parolei. Codul de resetare este: <b>' . $cod_resetare_parola . '</b>. Daca aceasta cerere nu a fost efectuata de dvs. intrat in contul de utilizator si apasati pe butonul <i>Anulare cod</i> !</p>';
        $mail->AltBody = 'A fost trimisa o cerere de restare a parolei. Codul de resetare este: ' . $cod_resetare_parola . '. Daca aceasta cerere nu a fost efectuata de dvs. intrat in contul de utilizator si apasati pe butonul <i>Anulare cod! ';
        $mail->send();
        echo "<script>window.location.href='resetare-parola.php'</script>";
        exit();
    } else {
        echo "<script>alert('EROARE');window.location.href='resetare-parola.php'</script>";
    }
}

if (isset($_POST["resetare_parola"])) {
    $cod_email = mysqli_real_escape_string($conn, $_POST["cod_email"]);
    $parola_noua = mysqli_real_escape_string($conn, $_POST["parola_noua"]);
    $parola_noua_resetare = mysqli_real_escape_string($conn, $_POST["parola_noua_repetare"]);

    if (empty($cod_email) or empty($parola_noua) or empty($parola_noua_resetare)) {
        $_SESSION["code_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='resetare-parola.php'</script>";
        exit();
    }

    $result_verificare_existenta_cod_resetare = mysqli_query($conn, "SELECT cod_resetare FROM conturi WHERE cod_resetare = '$cod_email'");
    if (mysqli_num_rows($result_verificare_existenta_cod_resetare) !== 1) {
        $_SESSION["code_alert"] = "Codul introdus nu există!";
        echo "<script>window.location.href='resetare-parola.php'</script>";
        exit();
    } else {
        if (strlen($parola_noua) > 7 && strlen($parola_noua) < 51) {
            if ($parola_noua === $parola_noua_resetare) {
                $parola_noua_criptata = password_hash($parola_noua, PASSWORD_DEFAULT);
                $result_actualizare_parola_noua = mysqli_query($conn, "UPDATE conturi SET parola = '$parola_noua_criptata' WHERE cod_resetare = '$cod_email'");
                if ($result_actualizare_parola_noua) {
                    $result_setare_cod_null = mysqli_query($conn, "UPDATE conturi SET cod_resetare = NULL WHERE cod_resetare = '$cod_email' ");
                    $_SESSION["success_password"] = "Parola a fost actualizată cu succes!";
                    echo "<script>window.location.href='resetare-parola.php'</script>";
                    exit();
                } else {
                    echo "<script>alert('EROARE');window.location.href='resetare-parola.php'</script>";
                }
            } else {
                $_SESSION["password_match"] = "Parolele nu corespund! Încearcă din nou!";
                echo "<script>window.location.href='resetare-parola.php'</script>";
                exit();
            }
        } else {
            $_SESSION["password_min_length"] = "Parola trebuie sa conțină între 8 și 50 de caractere!";
            echo "<script>window.location.href='resetare-parola.php'</script>";
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
    <title>Restare parolă</title>
    <link rel="stylesheet" type="text/css" href="resetare-parola.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    if (isset($_SESSION["success_password"])) {
    ?>
        <div class="success-message-container" id="alert-message">
            <div class="success-message" style="background-color:#001220;color:rgb(219, 241, 219);"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION["success_password"]; ?></div>
        </div>
    <?php
        unset($_SESSION["success_password"]);
    }
    ?>


</body>

<div class="mainContent">
    <div class="position">
        <div class="email-reset-password">
            <form action="" method="post">
                <p>Trimite cod de resetare</p>
                <div style="display:flex;justify-content:center;align-items:left;flex-direction:column;gap:1vh;">
                    <label for=""><i class="fa fa-envelope" aria-hidden="true"></i> Email</label>
                    <input type="email" required placeholder="Introdu email-ul..." name="email_trimitere_cod" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" id="emailresetare">
                </div>
                <div>
                    <?php
                    if (isset($_SESSION["email_sent_confirmation"])) {
                    ?>
                        <div style="text-align:center;background-color:rgb(219, 241, 219);padding:1.5vh 1.5vh;border-radius:1.5vh;">
                            <div style="width:42vh;color:#001220;font-size:2.5vh;"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION["email_sent_confirmation"]; ?></div>
                        </div>
                    <?php
                        unset($_SESSION["email_sent_confirmation"]);
                    }
                    ?>
                </div>
                <div>
                    <?php
                    if (isset($_SESSION["email_verifiy"])) {
                    ?>
                        <div style="text-align:center;background-color:rgb(219, 241, 219);padding:1.5vh 1.5vh;border-radius:1.5vh;">
                            <div style="width:42vh;color:#001220;font-size:2.5vh;"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION["email_verifiy"]; ?></div>
                        </div>
                    <?php
                        unset($_SESSION["email_verifiy"]);
                    }
                    ?>
                </div>
                <button type="submit" name="trimite_email" onclick="procesare()">Trimite email</button>
            </form>
        </div>


        <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;gap:0.8vh;">
            <img src="imagini/old-but-gold-high-resolution-logo-transparent.png" alt="" style="width: 25vh;">
            <a href="index.php" class="back_to_main_page">Înapoi în pagina principală</a>
        </div>


        <div class="reset-password-container">
            <form action="" method="post">
                <p>Resetează parola</p>
                <div style="display:flex;justify-content:center;align-items:left;flex-direction:column;gap:1vh;">
                    <label for=""><i class="fa fa-key" aria-hidden="true"></i> Cod primit pe email</label>
                    <input type="text" required placeholder="Introdu codul primit pe email..." name="cod_email" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                </div>
                <div style="display:flex;justify-content:center;align-items:left;flex-direction:column;gap:1vh;">
                    <label for=""><i class="fa fa-unlock-alt" aria-hidden="true"></i> Parola nouă</label>
                    <input type="password" required name="parola_noua" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" maxlength="50" oninput="this.setCustomValidity('')" placeholder="Parola nouă (8-50 caractere)...">
                </div>
                <div style="display:flex;justify-content:center;align-items:left;flex-direction:column;gap:1vh;">
                    <label for=""><i class="fa fa-unlock-alt" aria-hidden="true"></i> Repetă parola nouă</label>
                    <input type="password" required name="parola_noua_repetare" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" maxlength="50" oninput="this.setCustomValidity('')" placeholder="Repetă parola nouă (8-50 caractere)...">
                </div>
                <?php
                if (isset($_SESSION["password_min_length"])) {
                ?>
                    <div style="text-align:center;background-color:rgb(219, 241, 219);padding:1.5vh 1.5vh;border-radius:1.5vh;">
                        <div style="width:42vh;color:#001220;font-size:2.5vh;"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION["password_min_length"]; ?></div>
                    </div>
                <?php
                    unset($_SESSION["password_min_length"]);
                }
                ?>

                <?php
                if (isset($_SESSION["code_alert"])) {
                ?>
                    <div style="text-align:center;background-color:rgb(219, 241, 219);padding:1.5vh 1.5vh;border-radius:1.5vh;">
                        <div style="width:42vh;color:#001220;font-size:2.5vh;"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION["code_alert"]; ?></div>
                    </div>
                <?php
                    unset($_SESSION["code_alert"]);
                }
                ?>

                <?php
                if (isset($_SESSION["password_match"])) {
                ?>
                    <div style="text-align:center;background-color:rgb(219, 241, 219);padding:1.5vh 1.5vh;border-radius:1.5vh;">
                        <div style="width:42vh;color:#001220;font-size:2.5vh;"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION["password_match"]; ?></div>
                    </div>
                <?php
                    unset($_SESSION["password_match"]);
                }
                ?>
                <button type="submit" name="resetare_parola">Resetează parola</button>
            </form>
        </div>

    </div>
</div>

<div style="position: fixed;z-index:99999;width:100%;height:100%;top:0;left:0;background-color:black;display:none;background-color:#001220;justify-content:center;align-items:center;" id="procesare">
    <p style="color:rgb(219, 241, 219);font-size:8vh;">Procesare <i class="fa fa-spinner fa-spin"></i></p>
</div>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#alert-message').remove();
        }, 4000);
    });
</script>

<script>
    function procesare() {
        let emailresetare = document.getElementById("emailresetare").value.trim();
        let emailresetarevalidare = document.getElementById("emailresetare")

        if (emailresetare !== '' && emailresetarevalidare.checkValidity()) {
            document.getElementById("procesare").style.display = 'flex';
        }
    }
</script>

</html>