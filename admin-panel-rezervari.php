<?php

include 'sessionstart.php';
include 'restrict.php';

if (isset($_POST["update_rezervare"])) {
    $numar_nou_persoane = $_POST["numar_nou_persoane"];
    $data_noua = $_POST["data_noua"];
    $ora_rezervare_noua = $_POST["ora_rezervare"];
    $id_rezervare_update = $_POST["id_rezervare"];

    $result_update_rezervare = mysqli_query($conn, "UPDATE rezervari SET numar_persoane = '$numar_nou_persoane', data_rezervare = '$data_noua', ora_rezervare = '$ora_rezervare_noua', status_rezervare = 'Aprobată' WHERE idrezervare = '$id_rezervare_update'");
    if ($result_update_rezervare) {
        $_SESSION["reservation_update_alert"] = "Datele rezervării au fost modificate cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["search-by-status"])) {
    $select_status = $_POST["set_status"];
    echo '<script>window.location.href="admin-panel-rezervari.php?status=' . $select_status . '"</script>';
} else {
    $select_status = "Validare...";
}

if (isset($_GET["status"])) {
    $status_value = $_GET["status"];
} else {
    $status_value = 'Validare...';
}

if (isset($_POST["search-by-data"])) {
    $data_cautare_rezervare = $_POST["data_cautare_rezervare"];
    echo '<script>window.location.href="admin-panel-rezervari.php?status=' . $status_value . '&data=' . $data_cautare_rezervare . '"</script>';
} else {
    $select_status = "Validare...";
}

$select_by_data = "";
if (isset($_GET["data"])) {
    $select_data_2 = $_GET["data"];
    $select_by_data = "AND data_rezervare = '$select_data_2'";
} else {
}

if (isset($_GET["status"])) {

    if ($_GET["status"] === "Toate") {
        $select_by_status = implode("','", array('Validare...', 'Aprobată', 'Anulată'));
    } else {
        $select_by_status = $_GET["status"];
    }
} else {
    $select_by_status = "Validare...";
}


if (isset($_GET["status"])) {
    if ($_GET["status"] === "Validare...") {
        $sort = "ASC";
    }

    if ($_GET["status"] === "Aprobată") {
        $sort = "DESC";
    }

    if ($_GET["status"] === "Anulată") {
        $sort = "DESC";
    }
    if ($_GET["status"] === "Toate") {
        $sort = "DESC";
    }
} else {
    $sort = "ASC";
}

if (isset($_POST["aproba_rezervare"])) {
    $id_rezervare_aprobare = $_POST["id_rezervare_aprobare"];
    $result_aprobare_rezervare = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Aprobată' WHERE idrezervare = '$id_rezervare_aprobare' ");
    if ($result_aprobare_rezervare) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["anuleaza_rezervare"])) {
    $id_rezervare_anulare = $_POST["id_rezervare_anulare"];
    $result_anulare_rezervare = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Anulată' WHERE idrezervare = '$id_rezervare_anulare' ");
    if ($result_anulare_rezervare) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["validare_rezervare"])) {
    $id_rezervare_validare = $_POST["id_rezervare_validare"];
    $result_validare_rezervare = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Validare...' WHERE idrezervare = '$id_rezervare_validare' ");
    if ($result_validare_rezervare) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["anuleaza_rezervare2"])) {
    $id_rezervare_anulare2 = $_POST["id_rezervare_anulare2"];
    $result_anulare_rezervare2 = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Anulată' WHERE idrezervare = '$id_rezervare_anulare2' ");
    if ($result_anulare_rezervare2) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["validare_rezervare2"])) {
    $id_rezervare_validare2 = $_POST["id_rezervare_validare2"];
    $result_validare_rezervare2 = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Validare...' WHERE idrezervare = '$id_rezervare_validare2' ");
    if ($result_validare_rezervare2) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["aproba_rezervare2"])) {
    $id_rezervare_aprobare2 = $_POST["id_rezervare_aprobare2"];
    $result_aprobare_rezervare2 = mysqli_query($conn, "UPDATE rezervari SET status_rezervare = 'Aprobată' WHERE idrezervare = '$id_rezervare_aprobare2' ");
    if ($result_aprobare_rezervare2) {
        $_SESSION["reservation_status_alert"] = "Statusul rezervării a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["search-by-email"])) {
    $email_search = $_POST["email"];
    echo '<script>window.location.href="admin-panel-rezervari.php?status=' . $status_value . '&email=' . $email_search . '"</script>';
}

$select_by_email = "";
if (isset($_GET["email"])) {
    $select_email = mysqli_real_escape_string($conn, $_GET["email"]);
    $select_by_email = "AND email_rezervare = '$select_email'";
} else {
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Rezervări</title>
    <link rel="stylesheet" type="text/css" href="admin-panel.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    if (isset($_SESSION['reservation_update_alert'])) {
    ?>
        <div class="success-message2" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['reservation_update_alert'] ?></div>
    <?php
        unset($_SESSION['reservation_update_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['reservation_status_alert'])) {
    ?>
        <div class="success-message2" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['reservation_status_alert'] ?></div>
    <?php
        unset($_SESSION['reservation_status_alert']);
    }
    ?>

    <div class="top-bar">
        <div class="logo-container" style="flex:1;">
            <img class="logo" src="imagini/old-but-gold-high-resolution-logo-transparent.png">
        </div>
        <div class="middle-navigation" style="align-items:center;gap:3vh;justify-content:center;flex:2;">
            <a href="index.php" class="middleanchor">
                <div class="round"><img class="middleIcons" src="imagini/icons/home.png"></div>
                <span>Acasă</span>
            </a>

            <a class="middleanchor" href="admin-panel-comenzi.php">
                <div class="round"><img class="middleIcons" src="imagini/icons/shopping-bag.png"></div>
                <span>Comenzi</span>
            </a>

            <a class="middleanchor" href="admin-panel-rezervari.php">
                <div class="round"> <img class="middleIcons" src="imagini/icons/booking.png"></div>
                <span>Rezervări</span>
            </a>


            <a class="middleanchor" href="admin-panel-conturi.php">
                <div class="round"><img class="middleIcons" src="imagini/icons/user.png"></div>
                <span>Conturi</span>
            </a>

        </div>
        <?php
        if (isset($_SESSION['tip'])) {
            if ($_SESSION['tip'] == 'client') {
        ?>
                <div class="already-login-buttons" style="text-decoration: none;flex:1;">
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
                <div class="already-login-buttons" style="flex:1;">
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
        <div class="position">

            <div class="filters-container">
                <div class="select-by-status-container">
                    <form action="" method="post">
                        <label for="">Selectează rezervări după status:</label>
                        <select name="set_status" id="">
                            <option name="set_status" value="<?php echo $status_value; ?>">Alege status</option>
                            <option name="set_status" value="Validare...">Validare...</option>
                            <option name="set_status" value="Aprobată">Aprobată</option>
                            <option name="set_status" value="Anulată">Anulată</option>
                            <option name="set_status" value="Toate">Toate</option>
                        </select>
                        <button type="submit" class="search-by-status" name="search-by-status" id="refresh"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <div class="select-by-data">
                    <form action="" method="post">
                        <label for="">Selectează rezervări după dată:</label>
                        <input type="date" name="data_cautare_rezervare" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" style="margin-bottom:0;" required>
                        <button type="submit" class="search-by-status" name="search-by-data"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <?php
                if (isset($_GET["status"])) {
                    if ($_GET["status"] === "Toate") {
                ?>
                        <div class="select-by-code-container">
                            <form action="" method="post">
                                <label for="">Caută după email:</label>
                                <input type="email" name="email" style="width: 40vh;" placeholder="Introdu emailul..." required>
                                <button type="submit" class="search-by-status" name="search-by-email"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <?php
            date_default_timezone_set('Europe/Bucharest');
            $data_curenta = date("Y-m-d");
            $data_maxima_rezervari = date("Y-m-d", strtotime("+14days"));
            // $result_select_rezervari_utilizator = mysqli_query($conn, "SELECT * FROM rezervari WHERE status_rezervare IN ('$select_by_status') AND (data_rezervare BETWEEN '$data_curenta' AND '$data_maxima_rezervari') ORDER BY data_rezervare $sort");
            $result_select_rezervari_utilizator = mysqli_query($conn, "SELECT * FROM rezervari WHERE status_rezervare IN ('$select_by_status') $select_by_data $select_by_email ORDER BY data_ora_rezervarii $sort");
            if (mysqli_num_rows($result_select_rezervari_utilizator) > 0) {
                while ($row = mysqli_fetch_assoc($result_select_rezervari_utilizator)) {
                    $nume_complet = $row["nume_complet"];
                    $email_rezervare = $row["email_rezervare"];
                    $telefon_rezervare = $row["telefon_rezervare"];
                    $data_rezervare = $row["data_rezervare"];
                    $ora_rezervare = $row["ora_rezervare"];
                    $status_rezervare = $row["status_rezervare"];
                    $numar_persoane = $row["numar_persoane"];
                    $id_rezervare = $row["idrezervare"];
                    $data_efectuare_rezervare = $row["data_ora_rezervarii"];
            ?>
                    <div class="rezervare-container">
                        <div class="rezervare-container-content">
                            <p>Nume Complet: <?php echo $nume_complet; ?></p>
                            <p>Email: <?php echo $email_rezervare; ?></p>
                            <p>Telefon: <?php echo $telefon_rezervare ?></p>
                            <p>Număr persoane: <?php echo $numar_persoane ?></p>
                            <p>Data și ora rezervării: <?php echo $data_rezervare; ?> <?php echo $ora_rezervare; ?></p>
                            <p style="color:antiquewhite;font-style: italic;">Data efectuării: <?php echo $data_efectuare_rezervare; ?></p>

                            <?php
                            if ($status_rezervare === "Validare...") {
                            ?>
                                <p style="color:orange">Status rezervare: <?php echo $status_rezervare; ?></p>
                            <?php
                            }
                            if ($status_rezervare === "Aprobată") {
                            ?>
                                <p style="color:green;">Status rezervare: <?php echo $status_rezervare; ?></p>
                            <?php
                            }
                            if ($status_rezervare === "Anulată") {
                            ?>
                                <p style="color:red;">Status rezervare: <?php echo $status_rezervare; ?></p>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="modificari-rezervare">
                            <form action="" method="post" class="rezervari-form">
                                <div class="form-first-content">
                                    <div class="label-input-flex">
                                        <label for="">Număr pers.</label>
                                        <input type="number" name="numar_nou_persoane" value="<?php echo $numar_persoane ?>" required style="width:7.35vh;margin-bottom:0;">
                                    </div>
                                    <div class="label-input-flex">
                                        <label for="">Data</label>
                                        <input type="date" name="data_noua" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" style="margin-bottom:0;" required>
                                    </div>
                                    <div class="label-input-flex">
                                        <label for="">Ora</label>
                                        <select name="ora_rezervare" id="" class="reservation-input" required id="reservation_select" required style="background-color:white;color:black;font-size:2vh;">
                                            <option value="10:30" name="ora_rezervare" style="font-size:2vh;">10:30</option>
                                            <option value="11:00" name="ora_rezervare" style="font-size:2vh;">11:00</option>
                                            <option value="11:30" name="ora_rezervare" style="font-size:2vh;">11:30</option>
                                            <option value="12:00" name="ora_rezervare" style="font-size:2vh;">12:00</option>
                                            <option value="12:30" name="ora_rezervare" style="font-size:2vh;">12:30</option>
                                            <option value="13:00" name="ora_rezervare" style="font-size:2vh;">13:00</option>
                                            <option value="13:30" name="ora_rezervare" style="font-size:2vh;">13:30</option>
                                            <option value="14:00" name="ora_rezervare" style="font-size:2vh;">14:00</option>
                                            <option value="14:30" name="ora_rezervare" style="font-size:2vh;">14:30</option>
                                            <option value="15:00" name="ora_rezervare" style="font-size:2vh;">15:00</option>
                                            <option value="15:30" name="ora_rezervare" style="font-size:2vh;">15:30</option>
                                            <option value="16:00" name="ora_rezervare" style="font-size:2vh;">16:00</option>
                                            <option value="16:30" name="ora_rezervare" style="font-size:2vh;">16:30</option>
                                            <option value="17:00" name="ora_rezervare" style="font-size:2vh;">17:00</option>
                                            <option value="17:30" name="ora_rezervare" style="font-size:2vh;">17:30</option>
                                            <option value="18:00" name="ora_rezervare" style="font-size:2vh;">18:00</option>
                                            <option value="18:30" name="ora_rezervare" style="font-size:2vh;">18:30</option>
                                            <option value="19:00" name="ora_rezervare" style="font-size:2vh;">19:00</option>
                                            <option value="19:30" name="ora_rezervare" style="font-size:2vh;">19:30</option>
                                            <option value="20:00" name="ora_rezervare" style="font-size:2vh;">20:00</option>
                                            <option value="20:30" name="ora_rezervare" style="font-size:2vh;">20:30</option>
                                            <option value="21:00" name="ora_rezervare" style="font-size:2vh;">21:00</option>
                                            <option value="21:30" name="ora_rezervare" style="font-size:2vh;">21:30</option>
                                            <option value="22:00" name="ora_rezervare" style="font-size:2vh;">22:00</option>
                                            <option value="22:30" name="ora_rezervare" style="font-size:2vh;">22:30</option>
                                            <option value="23:00" name="ora_rezervare" style="font-size:2vh;">23:00</option>
                                            <option value="23:30" name="ora_rezervare" style="font-size:2vh;">23:30</option>
                                            <option value="00:00" name="ora_rezervare" style="font-size:2vh;">00:00</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="id_rezervare" value="<?php echo $id_rezervare; ?>">
                                </div>
                                <button type="submit" name="update_rezervare" class="update-reservation-button">Actualizează</button>
                            </form>
                            <?php
                            if ($status_rezervare === "Validare...") {
                            ?>
                                <div style="display:flex;justify-content:center;align-items:center;gap: 2vh;">
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="aproba_rezervare" style="background-color:green;color:white;">Aprobă</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_aprobare">
                                    </form>
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="anuleaza_rezervare" style="background-color:red;color:white;">Anulează</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_anulare">
                                    </form>
                                </div>

                            <?php
                            }
                            ?>

                            <?php
                            if ($status_rezervare === "Aprobată") {
                            ?>
                                <div style="display:flex;justify-content:center;align-items:center;gap: 2vh;">
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="validare_rezervare" style="background-color:orange;color:white;">Validare</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_validare">
                                    </form>
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="anuleaza_rezervare2" style="background-color:red;color:white;">Anulează</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_anulare2">
                                    </form>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            if ($status_rezervare === "Anulată") {
                            ?>
                                <div style="display:flex;justify-content:center;align-items:center;gap: 2vh;">
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="aproba_rezervare2" style="background-color:green;color:white;">Aprobă</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_aprobare2">
                                    </form>
                                    <form action="" method="post" class="update-status-form">
                                        <button type="submit" name="validare_rezervare2" style="background-color:orange;color:white;">Validare</button>
                                        <input type="hidden" value="<?php echo $id_rezervare; ?>" name="id_rezervare_validare2">
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                    </div>
                <?php
                }
            } else {
                ?>
                <div style="font-size:5vh;">
                    Nu există rezervări!
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success').remove();
            }, 2000);
        });
    </script>

    <script>
        function refresh() {
            document.getElementById("refresh").click();
        }

        setInterval(refresh, 180000);
    </script>

</body>

</html>