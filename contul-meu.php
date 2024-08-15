<?php
include 'sessionstart.php';

if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
    } else if ($_SESSION["tip"] == "client") {
    }
} else {
    echo "<script>alert('Intră în cont pentru detaliile contului!');window.location.href='index.php'</script>";
    exit();
}

if (isset($_POST["actualizeaza_date_personale"])) {
    $nume_actualizat = mysqli_real_escape_string($conn, $_POST["nume_actualizat"]);
    $prenume_actualizat = mysqli_real_escape_string($conn, $_POST["prenume_actualizat"]);
    $adresa_actualizat = mysqli_real_escape_string($conn, $_POST["adresa_actualizat"]);
    $judet_actualizat = mysqli_real_escape_string($conn, $_POST["judet_actualizat"]);
    $localitate_actualizat = mysqli_real_escape_string($conn, $_POST["localitate_actualizat"]);

    if (empty($nume_actualizat) or empty($prenume_actualizat) or empty($adresa_actualizat) or empty($judet_actualizat) or empty($localitate_actualizat)) {
        $_SESSION['name_characters_limit'] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    if (strlen($nume_actualizat) > 100 or strlen($prenume_actualizat) > 100 or strlen($adresa_actualizat) > 800 or strlen($judet_actualizat) > 45 or strlen($localitate_actualizat) > 45) {
        $_SESSION['name_characters_limit'] = "Ai depășit limita de caractere pentru unul din atribute. Încearcă din nou!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $result_actualizare_date = mysqli_query($conn, "UPDATE conturi SET nume='$nume_actualizat', prenume='$prenume_actualizat', adresa='$adresa_actualizat', judet='$judet_actualizat', localitate='$localitate_actualizat' WHERE idcont='$idCont'");
    if ($result_actualizare_date) {
        $_SESSION['nume'] = $nume_actualizat;
        $_SESSION['prenume'] = $prenume_actualizat;
        $_SESSION['judet'] = $judet_actualizat;
        $_SESSION['adresa'] = $adresa_actualizat;
        $_SESSION['localitate'] = $localitate_actualizat;
        $_SESSION["update_personal_data_alert"] = "Datele persoanale au fost actualizate cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

if (isset($_POST["actualizeaza_parola"])) {
    $parola_actualizata = mysqli_real_escape_string($conn, $_POST["parola_actualizata"]);
    $parola_actualizata_confirmare = mysqli_real_escape_string($conn, $_POST["parola_actualizata_confirmare"]);

    if (empty($parola_actualizata) or empty($parola_actualizata_confirmare)) {
        $_SESSION['name_characters_limit'] = "A apărut o eroare!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    if (strlen($parola_actualizata) > 7 and strlen($parola_actualizata) < 51) {
        if ($parola_actualizata == $parola_actualizata_confirmare) {
            $parola_actualizata_criptata = password_hash($parola_actualizata, PASSWORD_DEFAULT);
            $result_actualizare_parola = mysqli_query($conn, "UPDATE conturi SET parola = '$parola_actualizata_criptata' WHERE idcont ='$idCont'");
            if ($result_actualizare_parola) {
                session_destroy();
                echo "<script>alert('Parola a fost actualizată cu succes! Te rugăm să reintri în cont!');window.location.href='index.php';</script>";
                exit();
            } else {
                die(mysqli_error($conn));
            }
        } else {
            $_SESSION["passwords_match_alert"] = "Parolele nu corespund, încearcă din nou!";
            echo "<script>window.location.href='contul-meu.php'</script>";
            exit();
        }
    } else {
        $_SESSION["passwords_length_alert"] = "Parola trebuie sa conțină minim 8 caractere și maxim 50!";
        echo "<script>window.location.href='contul-meu.php'</script>";
        exit();
    }
}

if (isset($_POST["reseteaza_cod"])) {
    $verify_reset_code = mysqli_query($conn, "SELECT * FROM conturi WHERE idcont = '$idCont'");
    $row = mysqli_fetch_assoc($verify_reset_code);
    if ($row["cod_resetare"] === NULL) {
        $_SESSION["empty_code_alert"] = "Nu există cod de resetare activ!";
        echo "<script>window.location.href='contul-meu.php';</script>";
        exit();
    }
    $result_reset_password_code = mysqli_query($conn, "UPDATE conturi SET cod_resetare = NULL WHERE idcont ='$idCont'");
    if ($result_reset_password_code) {
        $_SESSION["success_update_code_alert"] = "Cererea de resetare a parolei a fost anulată!";
        echo "<script>window.location.href='contul-meu.php';</script>";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contul meu</title>
    <link rel="stylesheet" type="text/css" href="contul-meu.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#updateStatus").click(function() {

                $.ajax({
                    type: "GET",
                    url: "procesare-comenzile-mele.php",
                    dataType: "html",
                    success: function(response) {
                        $("#comenzileMelePreluareDate").html(response);
                    }

                });
            });
        });

        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "procesare-comenzile-mele.php",
                dataType: "html",
                success: function(response) {
                    $("#comenzileMelePreluareDate").html(response);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#updateStatus2").click(function() {

                $.ajax({
                    type: "GET",
                    url: "procesare-rezervarile-mele.php",
                    dataType: "html",
                    success: function(response) {
                        $("#rezervarileMelePreluareDate").html(response);
                    }

                });
            });
        });

        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "procesare-rezervarile-mele.php",
                dataType: "html",
                success: function(response) {
                    $("#rezervarileMelePreluareDate").html(response);
                }
            });
        });
    </script>

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

            window.addEventListener('resize', function() {
                if (window.innerWidth > 979) {
                    topbar.style.width = '100%';
                } else {
                    topbar.style.width = '';
                }
            });
        }
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
    if (isset($_SESSION['empty_code_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['empty_code_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['empty_code_alert']);
    }
    ?>



    <?php
    if (isset($_SESSION['success_update_code_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_update_code_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['success_update_code_alert']);
    }
    ?>


    <?php
    if (isset($_SESSION['update_personal_data_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['update_personal_data_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['update_personal_data_alert']);
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
    if (isset($_SESSION['name_characters_limit'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['name_characters_limit'] ?></div>
        </div>

    <?php
        unset($_SESSION['name_characters_limit']);
    }
    ?>

    <?php
    if (isset($_SESSION['name2_characters_limit'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['name2_characters_limit'] ?></div>
        </div>

    <?php
        unset($_SESSION['name2_characters_limit']);
    }
    ?>

    <?php
    if (isset($_SESSION['adresa_characters_limit'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['adresa_characters_limit'] ?></div>
        </div>

    <?php
        unset($_SESSION['adresa_characters_limit']);
    }
    ?>

    <?php
    if (isset($_SESSION['judet_characters_limit'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['judet_characters_limit'] ?></div>
        </div>

    <?php
        unset($_SESSION['judet_characters_limit']);
    }
    ?>

    <?php
    if (isset($_SESSION['localitate_characters_limit'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['localitate_characters_limit'] ?></div>
        </div>

    <?php
        unset($_SESSION['localitate_characters_limit']);
    }
    ?>



    <?php
    if (isset($_SESSION['tip'])) {
        if ($_SESSION["tip"] == "admin") {
        } else if ($_SESSION["tip"] == "client") {
        }
    } else {
    ?>

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
            <div class="tabs">
                <a class="my-account-links" onclick="openTab(event, 'datePersonale')" id="datePersonaleButon">
                    <img src="imagini/icons/personal-information.png" alt="">
                    <span>Date personale</span>
                </a>
                <a class="my-account-links" onclick="openTab(event, 'comenzileMele')" id="comenzileMeleButon">
                    <img src="imagini/icons/checklist.png" alt="">
                    <span>Comenzile mele</span>
                </a>
                <a class="my-account-links" onclick="openTab(event, 'rezervarileMele')" id="rezervarileMeleButon">
                    <img src="imagini/icons/booking_reservation.png" alt="">
                    <span>Rezervările mele</span>
                </a>
                <?php
                if (isset($_SESSION["tip"])) {
                    if ($_SESSION["tip"] === "admin") {
                ?>
                        <a href="admin-panel-comenzi.php" class="my-account-links">
                            <img src="imagini/icons/admin.png" alt="">
                            <span>Admin Panel</span>
                        </a>
                <?php
                    }
                }
                ?>
            </div>

            <div class="tabcontent" id="datePersonale">
                <form action="" class="personal-data-form" method="post">
                    <p style="margin:0;color:rgb(219, 241, 219);font-size:3.5vh;">Datele tale personale</p>
                    <div class="in-data-form-content">
                        <div>
                            <label for=""><i class="fa fa-user"></i> Nume</label>
                            <input type="text" value="<?php echo $_SESSION['nume'] ?>" name="nume_actualizat" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                            <label for=""><i class="fa fa-user"></i> Prenume</label>
                            <input type="text" value="<?php echo $_SESSION['prenume']; ?>" name="prenume_actualizat" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                            <label for=""><i class="fa fa-envelope"></i> Email</label>
                            <input type="email" value="<?php echo $emailCont ?>" readonly>
                            <label for=""><i class="fa fa-phone"> </i>Telefon</label>
                            <input type="text" value="<?php echo $telefonCont ?>" readonly>
                            <label for=""><i class="fa fa-address-card-o"></i> Adresă</label>
                            <input type="text" value="<?php echo $adresaCont ?>" required name="adresa_actualizat" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                            <label for=""><i class="fa fa-institution"></i> Județ/Sector</label>
                            <input type="text" value="<?php echo $judetCont ?>" required name="judet_actualizat" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                            <label for=""><i class="fa fa-institution"></i> Localitate</label>
                            <input type="text" value="<?php echo $localitateCont ?>" name="localitate_actualizat" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        </div>
                    </div>
                    <button type="submit" name="actualizeaza_date_personale" class="update-personal-data-button">Actualizează date</button>
                </form>

                <div>
                    <form action="" method="post">
                        <div class="modify-password-content">
                            <p style="margin:0;color:rgb(219, 241, 219);font-size:3.5vh;">Schimbă parola</p>
                            <div>
                                <div>
                                    <label for=""><i class="fa fa-key"></i> Parola nouă</label>
                                    <input type="password" name="parola_actualizata" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Parola nouă(8-50 caractere)...">
                                </div>
                                <div>
                                    <label for=""><i class="fa fa-key"></i> Repetă parola nouă</label>
                                    <input type="password" name="parola_actualizata_confirmare" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" placeholder="Repetă parola nouă(8-50 caractere)...">
                                </div>
                            </div>
                            <button type="submit" name="actualizeaza_parola" class="update-personal-data-button">Actualizează parola</button>
                        </div>
                    </form>

                    <form action="" method="post" class="update-reset-code">
                        <button type="submit" name="reseteaza_cod">Anulare cod</button>
                        <p><i class="fa fa-info-circle" aria-hidden="true"></i> Folosiți acest buton în cazul în care primiți un email de resetare a parolei fără ca dvs. să fi efectuat această cerere!</p>
                    </form>

                </div>
            </div>


            <div class="tabcontent" id="comenzileMele" style="flex-direction:column;align-items:center;">
                <button id="updateStatus" type="button" class="update-status-button">Actualizează comenzile <i class="fa fa-history"></i></button>
                <div id="comenzileMelePreluareDate" style="display:grid;grid-template-columns:auto auto;gap:1vh;">

                </div>
            </div>


            <div class="tabcontent" id="rezervarileMele" style="flex-direction:column;align-items:center;">
                <button id="updateStatus2" type="button" class="update-status-button">Actualizează rezervările <i class="fa fa-history"></i></button>
                <div id="rezervarileMelePreluareDate" style="display:grid;grid-template-columns:auto auto;gap:1vh;">

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
                $('#success').remove();
            }, 3000);
        });
    </script>

    <script>
        function openTab(evt, tabName) {
            let j, tabcontent2, tabLink;

            tabcontent2 = document.getElementsByClassName("tabcontent");

            for (j = 0; j < tabcontent2.length; j++) {
                tabcontent2[j].style.display = "none";
            }

            tabLink = document.getElementsByClassName("my-account-links");

            for (j = 0; j < tabLink.length; j++) {
                tabLink[j].className = tabLink[j].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "flex";
            evt.currentTarget.className += " active";

            sessionStorage.setItem("lastTabAccount", tabName);
        }

        var lastTabAccount = sessionStorage.getItem("lastTabAccount");
        if (!lastTabAccount) {
            document.getElementById("datePersonaleButon").click();
        } else {
            document.getElementById(lastTabAccount + "Buton").click();
        }
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

</body>

</html>