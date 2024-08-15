<?php
include "sessionstart.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST["rezerva_masa"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: rezervari.php");
        exit();
    }
    $nume_complet = mysqli_real_escape_string($conn, $_POST["nume_complet"]);
    $telefon_rezervare = mysqli_real_escape_string($conn, $_POST["telefon_rezervare"]);
    $email_rezervare = mysqli_real_escape_string($conn, $_POST["email_rezervare"]);
    $numar_persoane = mysqli_real_escape_string($conn, $_POST["numar_persoane"]);
    $data_rezervare = mysqli_real_escape_string($conn, $_POST["data_rezervare"]);
    $ora_rezervare = mysqli_real_escape_string($conn, $_POST["ora_rezervare"]);
    $status_rezervare = "Validare...";
    date_default_timezone_set('Europe/Bucharest');
    $data_ora_rezervarii = date('Y-m-d H:i:s');

    if (empty($nume_complet) or empty($telefon_rezervare) or empty($email_rezervare) or empty($numar_persoane) or empty($data_rezervare) or empty($ora_rezervare)) {
        $_SESSION["alert-message-persoane"] = "Toate câmpurile sunt obligatorii!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    $ore_disponibile_rezervare = [
        "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00",
        "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00",
        "22:30", "23:00", "23:30", "00:00"
    ];

    if (!in_array($ora_rezervare, $ore_disponibile_rezervare)) {
        $_SESSION["alert-message-persoane"] = "A apărut o eroare!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    if (!filter_var($email_rezervare, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["alert-message-persoane"] = "A apărut o eroare!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    if (!is_numeric($numar_persoane)) {
        $_SESSION["alert-message-persoane"] = "A apărut o eroare!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    if ($numar_persoane > 20 or $numar_persoane < 1) {
        $_SESSION["alert-message-persoane"] = "Rezervarea trebuie să fie efectuată pentru minim o persoana și maxim 20!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    if (strlen($telefon_rezervare) != 10) {
        $_SESSION["alert-message-persoane"] = "Numărul de telefon trebuie să conțină 10 cifre!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    }

    $result_verificare_rezervare = mysqli_query($conn, "SELECT * FROM rezervari WHERE idcont='$idCont' AND data_rezervare = '$data_rezervare'");
    if (mysqli_num_rows($result_verificare_rezervare) > 0) {
        $_SESSION["alert-message"] = "Nu puteți rezerva mai mult de o masă pe zi!";
        echo "<script>window.location.href='rezervari.php';</script>";
        exit();
    } else {
        mysqli_autocommit($conn, FALSE);
        $flag_insert_reservation = true;
        $result_insert_rezervare = mysqli_query($conn, "INSERT INTO rezervari (idcont, numar_persoane, data_rezervare, ora_rezervare, nume_complet, telefon_rezervare, email_rezervare, status_rezervare, data_ora_rezervarii) VALUES (
            '$idCont', '$numar_persoane', '$data_rezervare', '$ora_rezervare', '$nume_complet', '$telefon_rezervare', '$email_rezervare', '$status_rezervare', '$data_ora_rezervarii' 
        )");
        if (!$result_insert_rezervare) {
            $flag_insert_reservation = false;
        }

        if ($flag_insert_reservation) {
            if (mysqli_commit($conn)) {
                $_SESSION["success-reservation"] = "Rezervarea dvs. s-a realizat cu succes! Verificați statusul rezervării în contul de client!";
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '';
                $mail->Password = '';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('george30012001@gmail.com', 'Old But Gold Restaurant');
                $mail->addAddress($email_rezervare, " " . $nume_complet . " ");

                $mail->isHTML(true);
                $mail->Subject = 'Rezervare masa - Old But Gold -' . " ";

                $mail->Body = '<center><div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Rezervarea dumneavoastra a fost plasata cu succes!</p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding:5vh 3vh;width:70vh;font-size:3.5vh;color:#001220;"><center><p style="margin:0;padding:0;">In functie de locurile disponibile, rezervarea dvs. poate fi <b>aprobata</b> sau <b>anulata</b>. Statusul rezervarii va fi modificat in cel mai scurt timp posibil, verificati-l in contul dvs. de client! Va multumim de intelegere!</p></center></div>
                <div style="background-color:rgb(219, 241, 219);padding-left:3vh;padding-right:3vh;padding-bottom:5vh;width:70vh;font-size:3.5vh;color:#001220;"><center><img src="cid:logo" style="width:15.5vh;height:15.5vh;"></center></div>
                <div style="background-color:#001220;width:70vh;font-size:3vh;color:white;padding:2vh 3vh;"><center><p style="margin:0;padding:0;">Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455.</p></center></div>
                </center>';
                $mail->AltBody = 'Rezervarea dumneavoastra a fost plasata cu succes! Urmariti pe site statusul recenziei. Daca intampinati probleme nu ezitati sa ne contactati la numarul de telefon 0722334455. ';
                $mail->addEmbeddedImage('imagini/old-but-gold-high-resolution-logo-transparent.png', 'logo', 'old-but-gold-high-resolution-logo-transparent.png');
                $mail->send();
                echo "<script>window.location.href='rezervari.php';</script>";
                exit();
            }
        } else {
            $_SESSION['eroare_inserare'] = "A apărut o eroare!";
            mysqli_rollback($conn);
            echo "<script>window.location.href='rezervari.php';</script>";
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
    <title>Rezervări</title>
    <link rel="stylesheet" type="text/css" href="rezervari.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
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

    <?php
    if (isset($_SESSION['success-reservation'])) {
    ?>
        <div class="success-message-container" id="success-reservation">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success-reservation'] ?></div>
        </div>
    <?php
        unset($_SESSION['success-reservation']);
    }
    ?>

    <?php
    if (isset($_SESSION['alert-message'])) {
    ?>
        <div class="success-message-container" id="alert-message">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['alert-message'] ?></div>
        </div>
    <?php
        unset($_SESSION['alert-message']);
    }
    ?>

    <?php
    if (isset($_SESSION['alert-message-persoane'])) {
    ?>
        <div class="success-message-container" id="alert-message">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['alert-message-persoane'] ?></div>
        </div>
    <?php
        unset($_SESSION['alert-message-persoane']);
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

    <div class="mainContent">
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
        <div class="position">

            <div>
                <form action="" method="post" enctype="multipart/form-data" class="reservation-content">
                    <p class="title-reservation">Rezervă o masă</p>
                    <div class="form-section">
                        <div>
                            <label for=""><i class="fa fa-user"></i> Numele complet</label>
                            <input class="reservation-input" type="text" name="nume_complet" id="nume" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Nume complet" value="<?php if (isset($_SESSION["tip"])) {
                                                                                                                                                                                                                                                                if ($_SESSION["tip"] === "client" or $_SESSION["tip"] === "admin") {
                                                                                                                                                                                                                                                                    echo $numeClient . " " . $prenumeClientComplet;
                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                echo "";
                                                                                                                                                                                                                                                            } ?>">
                        </div>
                        <div>
                            <label for=""><i class="fa fa-phone"></i> Telefon</label>
                            <input class="reservation-input" type="text" maxlength="10" name="telefon_rezervare" id="telefon" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Număr de telefon" value="<?php if (isset($_SESSION["tip"])) {
                                                                                                                                                                                                                                                                                            if ($_SESSION["tip"] === "client" or $_SESSION["tip"] === "admin") {
                                                                                                                                                                                                                                                                                                echo $telefonCont;
                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                            echo "";
                                                                                                                                                                                                                                                                                        } ?>">
                        </div>
                        <div>
                            <label for=""><i class="fa fa-envelope"></i> Email</label>
                            <input class="reservation-input" type="email" name="email_rezervare" id="email" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Email" value="<?php if (isset($_SESSION["tip"])) {
                                                                                                                                                                                                                                                            if ($_SESSION["tip"] === "client" or $_SESSION["tip"] === "admin") {
                                                                                                                                                                                                                                                                echo $emailCont;
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                            echo "";
                                                                                                                                                                                                                                                        } ?>">
                        </div>
                    </div>

                    <div class="form-section">
                        <div>
                            <label for=""><i class="fa fa-sort-numeric-desc" aria-hidden="true"></i> Număr persoane</label>
                            <input class="reservation-input" type="number" name="numar_persoane" id="numar_persoane" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" min="1" max="20" value="1" placeholder="Alege numărul de persoane">
                        </div>
                        <div>
                            <label for=""><i class="fa fa-calendar" aria-hidden="true"></i> Alege data</label>
                            <input class="reservation-input" type="date" name="data_rezervare" id="data_rezervare" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Format mm/dd/yyyy">
                        </div>
                        <div>
                            <label for=""><i class="fa fa-clock-o" aria-hidden="true"></i> Alege ora</label>
                            <select name="ora_rezervare" id="" class="reservation-input" required id="reservation_select">
                                <option value="10:30" name="ora_rezervare">10:30</option>
                                <option value="11:00" name="ora_rezervare">11:00</option>
                                <option value="11:30" name="ora_rezervare">11:30</option>
                                <option value="12:00" name="ora_rezervare">12:00</option>
                                <option value="12:30" name="ora_rezervare">12:30</option>
                                <option value="13:00" name="ora_rezervare">13:00</option>
                                <option value="13:30" name="ora_rezervare">13:30</option>
                                <option value="14:00" name="ora_rezervare">14:00</option>
                                <option value="14:30" name="ora_rezervare">14:30</option>
                                <option value="15:00" name="ora_rezervare">15:00</option>
                                <option value="15:30" name="ora_rezervare">15:30</option>
                                <option value="16:00" name="ora_rezervare">16:00</option>
                                <option value="16:30" name="ora_rezervare">16:30</option>
                                <option value="17:00" name="ora_rezervare">17:00</option>
                                <option value="17:30" name="ora_rezervare">17:30</option>
                                <option value="18:00" name="ora_rezervare">18:00</option>
                                <option value="18:30" name="ora_rezervare">18:30</option>
                                <option value="19:00" name="ora_rezervare">19:00</option>
                                <option value="19:30" name="ora_rezervare">19:30</option>
                                <option value="20:00" name="ora_rezervare">20:00</option>
                                <option value="20:30" name="ora_rezervare">20:30</option>
                                <option value="21:00" name="ora_rezervare">21:00</option>
                                <option value="21:30" name="ora_rezervare">21:30</option>
                                <option value="22:00" name="ora_rezervare">22:00</option>
                                <option value="22:30" name="ora_rezervare">22:30</option>
                                <option value="23:00" name="ora_rezervare">23:00</option>
                                <option value="23:30" name="ora_rezervare">23:30</option>
                                <option value="00:00" name="ora_rezervare">00:00</option>
                            </select>
                        </div>
                    </div>

                    <?php
                    if (isset($_SESSION["tip"])) {
                        if ($_SESSION["tip"] === "client" or $_SESSION["tip"] === "admin") {
                    ?>
                            <button type="submit" name="rezerva_masa" class="finish-reservation-button" id="rezervare" onclick="procesare()">Rezervă acum</button>

                        <?php
                        }
                    } else {
                        ?>
                        <p style="font-size:4vh;margin:0;color:rgb(219, 241, 219);text-align:center;">Intră în cont pentru a rezerva o masă</p>
                    <?php
                    }
                    ?>

                </form>
            </div>

            <div style="position: fixed;z-index:99999;width:100%;height:100%;top:0;left:0;background-color:black;display:none;background-color:#001220;justify-content:center;align-items:center;" id="procesare">
                <p style="color:rgb(219, 241, 219);font-size:8vh;">Procesare <i class="fa fa-spinner fa-spin"></i></p>
            </div>

            <div class="all-program-content">
                <p style="margin:0;font-size:4.4vh;color:rgb(219, 241, 219);">Program</p>

                <div class="program-content">
                    <div class="program-per-days-content">
                        <p style="font-size: 4.2vh;">Luni-Joi</p>
                        <p>10:30 - 00:00</p>
                    </div>

                    <div class="program-per-days-content">
                        <p style="font-size: 4.2vh;">Vineri-Sâmbătă</p>
                        <p>10:30 - 03:00</p>
                    </div>

                    <div class="program-per-days-content">
                        <p style="font-size: 4.2vh;">Duminică</p>
                        <p>10:30 - 23:00</p>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="copyright">
        <p><span>&#169;</span>Copyright Old But Gold. All rights reserved</p>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#alert-message').remove();
            }, 4000);
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
        $(document).ready(function() {
            setTimeout(function() {
                $('#success-reservation').remove();
            }, 5000);
        });
    </script>

    <script>
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

    <script>
        function procesare() {
            let nume = document.getElementById("nume").value;
            let telefon = document.getElementById("telefon").value;
            let email = document.getElementById("email").value.trim();
            let emailvalidare = document.getElementById("email");
            let numarPersoane = document.getElementById("numar_persoane").value;
            let dataRezervare = document.getElementById("data_rezervare").value;

            if (nume.trim() !== '' && telefon.trim() !== '' && email !== '' && emailvalidare.checkValidity() && numarPersoane.trim() !== '' && dataRezervare.trim() !== '') {
                document.getElementById("procesare").style.display = 'flex';
            }
        }
    </script>

</body>

</html>