<?php
include "sessionstart.php";

$nameNota = 'nota';

if (isset($_POST["posteaza"])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: recenzii.php");
        exit();
    }

    $mesaj_recenzie = mysqli_real_escape_string($conn, $_POST["recenzie"]);
    $nota =  mysqli_real_escape_string($conn, $_POST["nota"]);
    date_default_timezone_set('Europe/Bucharest');
    $data_recenzie = date('Y-m-d H:i:s');

    if (empty($mesaj_recenzie) or empty($nota)) {
        $_SESSION['review_limit_alert'] = "A apărut o eroare!";
        header("Location: recenzii.php");
        exit();
    }

    if (strlen($mesaj_recenzie) > 400 or strlen($mesaj_recenzie) < 1) {
        $_SESSION['review_limit_alert'] = "Recenzia trebuie sa conțină între 1 și 400 de caractere!";
        header("Location: recenzii.php");
        exit();
    }

    if ($nota != "5" and $nota != "4" and $nota != "3" and $nota != "2" and $nota != "1") {
        $_SESSION['review_limit_alert'] = "Nota trebuie să fie de la 1 la 5!";
        header("Location: recenzii.php");
        exit();
    }

    mysqli_autocommit($conn, FALSE);

    $insert_flag = true;

    $insert_recenzie = mysqli_query($conn, "INSERT INTO recenzii (idcont,data,mesaj,nota) VALUES ('$idCont','$data_recenzie','$mesaj_recenzie','$nota')");

    if (!$insert_recenzie) {
        $insert_flag = false;
    }

    if ($insert_flag) {
        if (mysqli_commit($conn)) {
            $_SESSION['success'] = "Recenzia a fost salvată cu succes!";
            header("Location: recenzii.php");
            exit();
        } else {
            $_SESSION['eroare_inserare'] = "A apărut o eroare!";
            header("Location: recenzii.php");
            exit();
        }
    } else {
        $_SESSION['eroare_inserare'] = "A apărut o eroare!";
        mysqli_rollback($conn);
        header("Location: recenzii.php");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="recenzii.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Recenzii</title>
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

    <?php
    if (isset($_SESSION['success'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success'] ?></div>
        </div>

    <?php
        unset($_SESSION['success']);
    }
    ?>

    <?php
    if (isset($_SESSION['success-delete'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message""><i class=" fa fa-trash-o" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['success-delete'] ?></div>
        </div>

    <?php
        unset($_SESSION['success-delete']);
    }
    ?>

    <?php
    if (isset($_SESSION['success-delete-by-admin'])) {
    ?>

        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-trash-o" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['success-delete-by-admin'] ?></div>
        </div>
    <?php
        unset($_SESSION['success-delete-by-admin']);
    }
    ?>

    <?php
    if (isset($_SESSION['deny-delete-by-admin'])) {
    ?>

        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['deny-delete-by-admin'] ?></div>
        </div>
    <?php
        unset($_SESSION['deny-delete-by-admin']);
    }
    ?>

    <?php
    if (isset($_SESSION['review_limit_alert'])) {
    ?>

        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['review_limit_alert'] ?></div>
        </div>
    <?php
        unset($_SESSION['review_limit_alert']);
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

    <div class="mainContent">
        <div class="position">

            <div id="barchart_values" style="width: 100vh;" class="barchart_values"></div>

            <?php
            if (isset($_SESSION["tip"])) {
                if ($_SESSION["tip"] === "client" || $_SESSION["tip"] === "admin") {
            ?>
                    <button class="add-review-button" id="add-review-button">
                        <span>Adaugă recenzie</span>
                        <img src="imagini/icons/rating.png" alt="">
                    </button>
                <?php
                }
            } else {
                ?>
                <center>
                    <div style="font-size:3.5vh">
                        Intră în cont pentru a putea lăsa o recenzie!
                    </div>
                </center>
            <?php
            }
            ?>

            <?php
            $recenzii_afisate_pe_pagina = 10;

            $result_select_toate_recenzii = mysqli_query($conn, "SELECT * FROM recenzii");
            $total_recenzii = mysqli_num_rows($result_select_toate_recenzii);

            $numar_total_pagini = ceil($total_recenzii / $recenzii_afisate_pe_pagina);

            if (isset($_GET['pagina_recenzii'])) {
                $pagina_recenzii_actuala = $_GET['pagina_recenzii'];

                if (!is_numeric($pagina_recenzii_actuala)) {
                    $pagina_recenzii_actuala = 1;
                }

                if ($pagina_recenzii_actuala < 1) {
                    $pagina_recenzii_actuala = 1;
                }
            } else {
                $pagina_recenzii_actuala = 1;
            }

            $offset = ($pagina_recenzii_actuala - 1) * $recenzii_afisate_pe_pagina;

            $result_select_recenzii = mysqli_query($conn, "SELECT * FROM recenzii INNER JOIN conturi USING (idcont) ORDER BY data DESC LIMIT $recenzii_afisate_pe_pagina OFFSET $offset");
            ?>
            <div class="all-reviews-content">
                <?php
                if (mysqli_num_rows($result_select_recenzii) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result_select_recenzii)) {
                        $descriere_recenzie = $row2["mesaj"];
                        $nota_recenzie = $row2["nota"];
                        $data_recenzie = $row2["data"];
                        $nume_client = $row2["nume"];
                        $id_client = $row2["idcont"];
                        $id_recenzie = $row2["idrecenzie"];
                        $prenume_client = substr($row2['prenume'], 0, 1) . ".";
                        $result_select_comenzi_totale = mysqli_query($conn, "SELECT * FROM comenzi WHERE idcont = '$id_client' AND status = 'Livrată'");
                        $total_comenzi_client = mysqli_num_rows($result_select_comenzi_totale);
                        $result_select_rezervari_totale = mysqli_query($conn, "SELECT * FROM rezervari WHERE idcont = '$id_client' AND status_rezervare = 'Aprobată'");
                        $total_rezervari_client = mysqli_num_rows($result_select_rezervari_totale);
                ?>
                        <div class="one-review-content">
                            <div style="display:flex;justify-content:left;align-items:center;gap:3vh;">
                                <p>Client: <?php echo $nume_client . " " . $prenume_client . " " ?></p>
                                <p>Comenzi (<?php echo $total_comenzi_client ?>)</p>
                                <p>Rezervări (<?php echo $total_rezervari_client ?>)</p>
                            </div>
                            <p>Data: <?php echo $data_recenzie ?></p>
                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "client") {
                                    if ($idCont == $id_client) {
                            ?>
                                        <div class="edit-delete-button">
                                            <button name="delete-review-by-user" style="background-color:red;" onclick="deleteConfirmation(<?php echo $id_recenzie; ?>,<?php echo $id_client; ?>)">
                                                <img src="imagini/icons/bin.png" alt="">
                                            </button>

                                        </div>
                                    <?php
                                    }
                                } else {
                                    if ($_SESSION["tip"] == "admin") {
                                    ?>
                                        <div class="edit-delete-button">

                                            <button style="background-color:red;" name="delete-review-by-admin" onclick="deleteConfirmation(<?php echo $id_recenzie; ?>,<?php echo $id_client; ?>)">
                                                <img src="imagini/icons/bin.png" alt="">
                                            </button>

                                        </div>
                                <?php
                                    }
                                }
                            } else {
                            }
                            if ($nota_recenzie == 5) {
                                ?>
                                <div class="stars-container">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                </div>
                            <?php
                            }

                            if ($nota_recenzie == 4) {
                            ?>
                                <div class="stars-container">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                </div>
                            <?php
                            }
                            if ($nota_recenzie == 3) {
                            ?>
                                <div class="stars-container">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                </div>
                            <?php
                            }
                            if ($nota_recenzie == 2) {
                            ?>
                                <div class="stars-container">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                </div>
                            <?php
                            }
                            if ($nota_recenzie == 1) {
                            ?>
                                <div class="stars-container">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                </div>
                            <?php
                            }

                            ?>
                            <p> <?php echo $descriere_recenzie; ?></p>
                        </div>
                    <?php
                    }

                    ?>

            </div>

        <?php
                } else {
        ?>
            <center>
                <div style="font-size: 3vh;">
                    Nu s-au găsit recenzii disponibile la această pagină
                </div>
            </center>
        <?php
                }


        ?>
        <div class="review-pages">
            <?php

            if ($pagina_recenzii_actuala > $numar_total_pagini) {
                echo "<script>window.location.href='recenzii.php'</script>";
            }



            if ($numar_total_pagini > 6) {
                if ($pagina_recenzii_actuala < $numar_total_pagini - 3 and $pagina_recenzii_actuala > 2) {
            ?>

                    <a href="recenzii.php">1</a>
                    <p>...</p>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala - 1; ?>"><?php echo $pagina_recenzii_actuala - 1; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala; ?>"><?php echo $pagina_recenzii_actuala; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala + 1; ?>"><?php echo $pagina_recenzii_actuala + 1; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala + 2; ?>"><?php echo $pagina_recenzii_actuala + 2; ?></a>
                    <p>...</p>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini; ?>"><?php echo $numar_total_pagini; ?></a>

                <?php
                } elseif (($pagina_recenzii_actuala < $numar_total_pagini - 3 and $pagina_recenzii_actuala = 1)) {
                ?>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala; ?>"><?php echo $pagina_recenzii_actuala; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala + 1; ?>"><?php echo $pagina_recenzii_actuala + 1; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala + 2; ?>"><?php echo $pagina_recenzii_actuala + 2; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $pagina_recenzii_actuala + 3; ?>"><?php echo $pagina_recenzii_actuala + 3; ?></a>
                    <p>...</p>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini; ?>"><?php echo $numar_total_pagini; ?></a>

                <?php
                } else {
                ?>
                    <a href="recenzii.php">1</a>
                    <p>...</p>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini - 4; ?>"><?php echo $numar_total_pagini - 4; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini - 3; ?>"><?php echo $numar_total_pagini - 3; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini - 2; ?>"><?php echo $numar_total_pagini - 2; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini - 1; ?>"><?php echo $numar_total_pagini - 1; ?></a>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $numar_total_pagini; ?>"><?php echo $numar_total_pagini; ?></a>
                <?php
                }
            } else {
                for ($index = 1; $index <= $numar_total_pagini; $index++) {
                ?>
                    <a href="recenzii.php?pagina_recenzii=<?php echo $index; ?>"><?php echo $index; ?></a>
            <?php
                }
            }

            ?>

        </div>
        <?php

        ?>

        <?php
        if (isset($_SESSION['tip'])) {
            if ($_SESSION['tip'] == 'client' or $_SESSION['tip'] == 'admin') {
        ?>
                <div class="add-review-content" id="add-review-form">
                    <div class="form-bg">
                        <div class="add-review-header">
                            <p>Scrie-ne părerea ta</p>
                            <span class="close2">&times;</span>
                        </div>
                        <form action="" method="post" class="add-review-form">

                            <textarea rows="4" cols="60" class="input1" id="recenzie" name="recenzie" type="text" placeholder="Scrie-ne părerea ta...(max 400 caractere)" required maxlength="400" oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')"></textarea>
                            <div style="display:flex;justify-content:center;align-items:center;gap:2vh;">
                                <label for="" style="color:#001220">Notă</label>
                                <select name="<?php echo htmlspecialchars($nameNota) ?>" id="" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                                    <option name="<?php echo $nameNota ?>" value="1">1</option>
                                    <option name="<?php echo $nameNota ?>" value="2">2</option>
                                    <option name="<?php echo $nameNota ?>" value="3">3</option>
                                    <option name="<?php echo $nameNota ?>" value="4">4</option>
                                    <option name="<?php echo $nameNota ?>" value="5">5</option>
                                </select>
                            </div>
                            <center>
                                <button class="post-review-button" name="posteaza" type="submit">
                                    Postează
                                </button>
                            </center>
                        </form>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>


        <?php
        }
        ?>
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
    </div>
    </div>

    <div class="copyright">
        <p><span>&#169;</span>Copyright Old But Gold. All rights reserved</p>
    </div>

    <?php
    $five_stars_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii WHERE nota = '5' "));
    $four_stars_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii WHERE nota = '4' "));
    $three_stars_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii WHERE nota = '3' "));
    $two_stars_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii WHERE nota = '2' "));
    $one_stars_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii WHERE nota = '1' "));
    $all_select = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM recenzii"));
    ?>

    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Nota", "Total recenzii", {
                    role: "style"
                }],
                ["5⭐", <?php echo $five_stars_select; ?>, "color: #001220;"],
                ["4⭐", <?php echo $four_stars_select; ?>, "color: #001220;"],
                ["3⭐", <?php echo $three_stars_select; ?>, "color: #001220;;"],
                ["2⭐", <?php echo $two_stars_select; ?>, "color: #001220;"],
                ["1⭐", <?php echo $one_stars_select; ?>, "color: #001220;"]
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2
            ]);

            var options = {
                title: "Total recenzii: <?php echo $all_select; ?>",
                titleTextStyle: {
                    fontSize: 15,
                },
                bar: {
                    groupWidth: "100vh",
                },
                legend: {
                    position: "none"
                },
                backgroundColor: 'transparent',
            };
            var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
            chart.draw(view, options);
        }
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success').remove();
            }, 2500);
        });
    </script>

    <script>
        function deleteConfirmation(id, idclient) {

            let confirmation = confirm("Ești sigur ca vrei să ștergi acestă recenzie?")

            if (confirmation) {
                window.location.href = "delete-recenzie.php?deleteid=" + id + "&idclient=" + idclient;
            }
        }
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
                $('#logout-alert').remove();
            }, 2000);
        });
    </script>


    <script>
        let addReviewForm = document.getElementById("add-review-form");
        let addReviewButton = document.getElementById("add-review-button");
        let close2 = document.getElementsByClassName("close2")[0];

        addReviewButton.onclick = function() {
            addReviewForm.style.display = "flex";
        }

        close2.onclick = function() {
            addReviewForm.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === addReviewForm) {
                addReviewForm.style.display = "none";
            }
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