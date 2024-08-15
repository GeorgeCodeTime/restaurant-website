<?php
require 'connect.php';
include 'sessionstart.php';

if (isset($_SESSION['tip'])) {
    if ($_SESSION['tip'] == 'client') {
        header('Location: index.php');
    }
    if ($_SESSION['tip'] == 'admin') {
        header('Location: index.php');
    }
}

if (isset($_POST['submitSignUp'])) {
    $nume = mysqli_real_escape_string($conn, $_POST["nume"]);
    $prenume = mysqli_real_escape_string($conn, $_POST["prenume"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $parola = mysqli_real_escape_string($conn, $_POST["parola"]);
    $confirmaparola = mysqli_real_escape_string($conn, $_POST["confirmaparola"]);
    $telefon = mysqli_real_escape_string($conn, $_POST["telefon"]);
    $judet = mysqli_real_escape_string($conn, $_POST["judet"]);
    $adresa = mysqli_real_escape_string($conn, $_POST["adresa"]);
    $localitate = mysqli_real_escape_string($conn, $_POST["localitate"]);
    $tip = "client";
    date_default_timezone_set('Europe/Bucharest');
    $data_creare_cont = date('Y-m-d H:i:s');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["deny_modify_alert"] = "A apărut o eroare!";
        echo "<script>window.location.href='creeaza-cont.php'</script>";
        exit();
    }

    if (
        strlen($nume) > 99 or strlen($prenume) > 99 or strlen($email) > 99 or strlen($parola)  > 199 or
        strlen($telefon) != 10 or strlen($judet) > 44 or strlen($adresa) > 799 or strlen($localitate) > 44
    ) {
        $_SESSION["deny_modify_alert"] = "Ai depășit limita de caractere pentru unul sau mai multe atribute!";
        echo "<script>window.location.href='creeaza-cont.php'</script>";
        exit();
    }

    if (empty($nume) or empty($prenume) or empty($email) or empty($telefon) or empty($judet) or empty($adresa) or empty($localitate)) {
        $_SESSION["deny_modify_alert"] = "Toate câmpurile sunt obligatorii!";
        echo "<script>window.location.href='creeaza-cont.php'</script>";
        exit();
    }

    $selectEmail = "SELECT * FROM conturi WHERE email = '$email'";
    $verificareEmail = mysqli_query($conn, $selectEmail);

    $selectTelefon = "SELECT * FROM conturi WHERE telefon = '$telefon'";
    $verificareTelefon = mysqli_query($conn, $selectTelefon);

    if (mysqli_num_rows($verificareEmail) == 0 and mysqli_num_rows($verificareTelefon) == 0) {
        if (strlen($parola) > 7 && strlen($parola) < 51) {
            if ($parola == $confirmaparola) {
                $parolaCriptata = password_hash($parola, PASSWORD_DEFAULT);
                mysqli_autocommit($conn, FALSE);
                $flag_insert_data = true;
                $inserare = "INSERT INTO conturi (nume, prenume, email, parola, telefon, judet, adresa, localitate, tip, 
                data_creare) VALUES ('$nume','$prenume','$email','$parolaCriptata','$telefon','$judet','$adresa',
                '$localitate','$tip','$data_creare_cont')";
                $new_account_insert = mysqli_query($conn, $inserare);
                if (!$new_account_insert) {
                    $flag_insert_data = false;
                }
                if ($flag_insert_data) {
                    if (mysqli_commit($conn)) {
                        $_SESSION["success_register_alert"] = "Contul a fost creat cu succes!";
                        echo "<script>window.location.href='creeaza-cont.php'</script>";
                        exit();
                    } else {
                        $_SESSION['eroare_inserare'] = "A apărut o eroare!";
                        echo "<script>window.location.href='creeaza-cont.php'</script>";
                        exit();
                    }
                } else {
                    $_SESSION['eroare_inserare'] = "A apărut o eroare!";
                    mysqli_rollback($conn);
                    header("Location: recenzii.php");
                    exit();
                }
            } else {
                $_SESSION["passwords_match_alert"] = "Parolele nu corespund, încearcă din nou!";
                echo "<script>window.location.href='creeaza-cont.php'</script>";
                exit();
            }
        } else {
            $_SESSION["passwords_length_alert"] = "Parola trebuie sa conțină minim între 8 și 50 de caractere!";
            echo "<script>window.location.href='creeaza-cont.php'</script>";
            exit();
        }
    } else {
        $_SESSION["email_phone_alert"] = "Emailul sau numărul de telefon este deja folosit!";
        echo "<script>window.location.href='creeaza-cont.php'</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="creeaza-cont.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Creează cont</title>
</head>

<body>

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
    if (isset($_SESSION['success_register_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_register_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['success_register_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['deny_modify_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['deny_modify_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['deny_modify_alert']);
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
    if (isset($_SESSION['passwords_match_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['passwords_match_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['passwords_match_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['passwords_length_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['passwords_length_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['passwords_length_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['email_phone_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['email_phone_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['email_phone_alert']);
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
                    <a>
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
                    <a>
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

    <div class="signUpFormContainer">
        <form class="signupForum" method="post" id="submitSignUp">
            <h1 style="font-size:4vh;color:rgb(219, 241, 219);">Creează cont</h1>
            <div class="formContent">
                <div class="leftSide">
                    <div class="datePersonale">
                        <div>
                            <label class="label2" for="nume"><i class="fa fa-user" aria-hidden="true"></i> Nume</label>
                            <input class="input2" type="text" id="nume" name="nume" maxlength="99" placeholder="Nume..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>

                        <div>
                            <label class="label2" for="prenume"><i class="fa fa-user" aria-hidden="true"></i> Prenume</label>
                            <input class="input2" type="text" id="prenume" maxlength="99" placeholder="Prenume..." name="prenume" required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>
                    </div>

                    <div>
                        <label class="label2" for="email"><i class="fa fa-envelope" aria-hidden="true"></i> Email</label>
                        <input class="input2" type="email" id="email" name="email" maxlength="99" placeholder="Email..." required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>

                    <div>
                        <label class="label2" for="parola"><i class="fa fa-key" aria-hidden="true"></i> Parolă</label>
                        <input class="input2" type="password" id="parola" name="parola" maxlength="50" placeholder="Introdu parola...(8-50 caractere)" required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>

                    <div>
                        <label class="label2" for="confirmaparola"><i class="fa fa-key" aria-hidden="true"></i> Repetă parola</label>
                        <input class="input2" type="password" id="confirmaparola" name="confirmaparola" maxlength="50" placeholder="Repetă parola...(8-50 caractere)" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>
                </div>

                <div class="rightSide">
                    <div>
                        <label class="label2" for="telefon"><i class="fa fa-phone" aria-hidden="true"></i> Telefon</label>
                        <input class="input2" type="text" id="telefon" name="telefon" maxlength="10" placeholder="Număr telefon..." required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>

                    <div>
                        <label class="label2" for="adresa"><i class="fa fa-map-marker" aria-hidden="true"></i> Adresă</label>
                        <input class="input2" type="text" id="adresa" name="adresa" maxlength="799" placeholder="Adresă..." required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>

                    <div>
                        <label class="label2" for="judet"><i class="fa fa-map-marker" aria-hidden="true"></i> Județ/Sector</label>
                        <input class="input2" type="text" id="judet" name="judet" maxlength="44" placeholder="Județ..." required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>

                    <div>
                        <label class="label2" for="localitate"><i class="fa fa-map-marker" aria-hidden="true"></i> Localitate</label>
                        <input class="input2" type="text" id="localitate" name="localitate" maxlength="44" placeholder="Localitate..." required required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
            </div>

            <center>
                <button class="button1" type="submit" name="submitSignUp">Creează cont</button>
            </center>
            <p style="color:rgb(219, 241, 219)">Ești deja înregistrat? <a id="loginBtn2">Intră in cont</a></p>
        </form>
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
        $(document).ready(function() {
            setTimeout(function() {
                $('#success').remove();
            }, 3000);
        });
    </script>

    <script>
        function hrefSite(url) {
            window.open(url, '_blank');
        }

        let loginForum = document.getElementById("loginForum");
        let loginBtn = document.getElementById("loginBtn");
        let loginBtn2 = document.getElementById("loginBtn2");
        let close = document.getElementsByClassName("close")[0];
        let center = document.getElementById("center");

        loginBtn.onclick = function() {
            loginForum.style.display = "block";
        }

        loginBtn2.onclick = function() {
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