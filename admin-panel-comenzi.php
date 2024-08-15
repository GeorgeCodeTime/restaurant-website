<?php

include 'sessionstart.php';
include 'restrict.php';

if (isset($_POST["success-button"])) {
    $id_preluare_comanda = $_POST["id_comanda"];
    $result_update_status1 = mysqli_query($conn, "UPDATE comenzi SET status ='Livrată' WHERE idcomanda = '$id_preluare_comanda'");

    if ($result_update_status1) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["cancel-button"])) {
    $id_preluare_comanda2 = $_POST["id_comanda2"];
    $result_update_status2 = mysqli_query($conn, "UPDATE comenzi SET status ='Anulată' WHERE idcomanda = '$id_preluare_comanda2'");

    if ($result_update_status2) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["progress-button"])) {
    $id_preluare_comanda3 = $_POST["id_comanda3"];
    $result_update_status3 = mysqli_query($conn, "UPDATE comenzi SET status ='Pregătire' WHERE idcomanda = '$id_preluare_comanda3'");

    if ($result_update_status3) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["cancel-button2"])) {
    $id_preluare_comanda4 = $_POST["id_comanda4"];
    $result_update_status4 = mysqli_query($conn, "UPDATE comenzi SET status ='Anulată' WHERE idcomanda = '$id_preluare_comanda4'");

    if ($result_update_status4) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["progress-button2"])) {
    $id_preluare_comanda5 = $_POST["id_comanda5"];
    $result_update_status5 = mysqli_query($conn, "UPDATE comenzi SET status ='Pregătire' WHERE idcomanda = '$id_preluare_comanda5'");

    if ($result_update_status5) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_POST["success-button2"])) {
    $id_preluare_comanda6 = $_POST["id_comanda6"];
    $result_update_status6 = mysqli_query($conn, "UPDATE comenzi SET status ='Livrată' WHERE idcomanda = '$id_preluare_comanda6'");

    if ($result_update_status6) {
        $_SESSION["orders_status_alert"] = "Statusul comenzii a fost modificat cu succes!";
        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    } else {
        echo '<script>alert("Eroare");</script>';
    }
}

if (isset($_GET["status"])) {
    $status_value = $_GET["status"];
} else {
    $status_value = 'Pregătire';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Comenzi</title>
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
    if (isset($_SESSION['orders_status_alert'])) {
    ?>
        <div class="success-message2" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['orders_status_alert'] ?></div>
    <?php
        unset($_SESSION['orders_status_alert']);
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
                        <label for="">Selectează comenzi după status:</label>
                        <select name="set_status" id="">
                            <option name="set_status" value="<?php echo $status_value; ?>">Alege status</option>
                            <option name="set_status" value="Pregătire">Pregătire</option>
                            <option name="set_status" value="Livrată">Livrată</option>
                            <option name="set_status" value="Anulată">Anulată</option>
                            <option name="set_status" value="Toate">Toate</option>
                        </select>
                        <button type="submit" class="search-by-status" name="search-by-status" id="refresh"><i class="fa fa-search"></i></button>
                    </form>
                </div>


                <div class="select-by-code-container">
                    <form action="" method="post">
                        <label for="">Caută după cod: </label>
                        <input type="text" name="code" placeholder="Introdu codul..." required>
                        <button type="submit" class="search-by-status" name="search-by-code"><i class="fa fa-search"></i></button>
                    </form>
                </div>

            </div>

            <?php
            $select_by_code = "";
            if (isset($_POST["search-by-code"])) {
                $select_code = $_POST["code"];
                $statuslivrat = "Pregătire"; 
                if(!isset($_GET["status"])){
                    echo '<script>window.location.href="admin-panel-comenzi.php?status=' . $statuslivrat . '&cod=' . $select_code . '";</script>';
                    exit();
                }
                echo '<script>window.location.href="' . $_SERVER['HTTP_REFERER'] . '&cod=' . $select_code . '";</script>';
            }

            if (isset($_GET["cod"])) {
                $select_code_2 = mysqli_real_escape_string($conn, $_GET["cod"]);
                $select_by_code = "AND cod_comanda = '$select_code_2'";
            } else {
            }

            if (isset($_POST["search-by-status"])) {
                $select_status = $_POST["set_status"];
                echo '<script>window.location.href="admin-panel-comenzi.php?status=' . $select_status . '"</script>';
            } else {
                $select_status = "Pregătire";
            }

            if (isset($_GET["status"])) {

                if ($_GET["status"] === "Toate") {
                    $select_by_status = implode("','", array('Pregătire', 'Livrată', 'Anulată'));
                } else {
                    $select_by_status = $_GET["status"];
                }
            } else {
                $select_by_status = "Pregătire";
            }

            //$result_select_comenzi_utilizator = mysqli_query($conn, "SELECT * FROM comenzi WHERE status = '$select_by_status' AND (DATE(data_comanda) = CURDATE() OR DATE(data_comanda) = CURDATE() - INTERVAL 1 DAY OR DATE(data_comanda) = CURDATE() - INTERVAL 2 DAY) $select_by_code ORDER BY data_comanda");
            $result_select_comenzi_utilizator = mysqli_query($conn, "SELECT * FROM comenzi WHERE status IN ('$select_by_status') $select_by_code  ORDER BY data_comanda DESC");
            if (mysqli_num_rows($result_select_comenzi_utilizator) > 0) {
                while ($row = mysqli_fetch_assoc($result_select_comenzi_utilizator)) {
                    $produse_comanda = $row["produse"];
                    $total_comanda = $row["total_comanda"];
                    $nume_destinatar = $row["nume_destinatar"];
                    $prenume_destinatar = $row["prenume_destinatar"];
                    $email_destinatar = $row["email_destinatar"];
                    $telefon_destinatar = $row["telefon_destinatar"];
                    $adresa_destinatar = $row["adresa_destinatar"];
                    $sector = $row["sector"];
                    $metoda_plata = $row["metoda_plata"];
                    $data_comanda = $row["data_comanda"];
                    $status = $row["status"];
                    $cod_comanda = $row["cod_comanda"];
                    $id_comanda = $row["idcomanda"];
            ?>
                    <div class="comanda-container">
                        <p>Destinatar: <?php echo $nume_destinatar; ?><span> <?php echo $prenume_destinatar; ?></span></p>
                        <p>Adresă: <?php echo $adresa_destinatar; ?>, <?php echo $sector; ?></p>
                        <p>Telefon: <?php echo $telefon_destinatar ?></p>
                        <p>Produse: <?php echo $produse_comanda; ?></p>
                        <p>Total: <?php echo $total_comanda; ?> RON</p>
                        <p>Data comenzii: <?php echo $data_comanda; ?></p>


                        <?php
                        if ($status === "Pregătire") {
                        ?>
                            <p style="color:orange;">Cod: <?php echo $cod_comanda; ?></p>
                            <p style="color:orange;">Status: <?php echo $status ?></p>
                            <div class="success-cancel-buttons">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <button name="success-button" class="success-button" type="submit" style="background-color: green;">Livrată</button>
                                    <input type="hidden" name="id_comanda" value="<?php echo $id_comanda ?>">
                                </form>
                                <form action="" method="post">
                                    <button name="cancel-button" class="cancel-button" type="submit">Anulată</button>
                                    <input type="hidden" name="id_comanda2" value="<?php echo $id_comanda ?>">
                                </form>
                            </div>
                        <?php
                        }
                        if ($status === "Livrată") {
                        ?>
                            <p style="color:green;">Cod: <?php echo $cod_comanda; ?></p>
                            <p style="color:green;">Status: <?php echo $status ?></p>
                            <div class="success-cancel-buttons">
                                <form action="" method="post">
                                    <button name="progress-button" class="success-button" style="background-color:orange" type="submit">Pregătire</button>
                                    <input type="hidden" name="id_comanda3" value="<?php echo $id_comanda ?>">
                                </form>
                                <form action="" method="post">
                                    <button name="cancel-button2" class="cancel-button" type="submit">Anulată</button>
                                    <input type="hidden" name="id_comanda4" value="<?php echo $id_comanda ?>">
                                </form>
                            </div>
                        <?php
                        }
                        if ($status === "Anulată") {
                        ?>
                            <p style="color:red;">Cod: <?php echo $cod_comanda; ?></p>
                            <p style="color:red;">Status: <?php echo $status ?></p>
                            <div class="success-cancel-buttons">
                                <form action="" method="post">
                                    <button name="progress-button2" class="success-button" style="background-color:orange" type="submit">Pregătire</button>
                                    <input type="hidden" name="id_comanda5" value="<?php echo $id_comanda ?>">
                                </form>
                                <form action="" method="post">
                                    <button name="success-button2" class="success-button" type="submit">Livrată</button>
                                    <input type="hidden" name="id_comanda6" value="<?php echo $id_comanda ?>">
                                </form>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                <?php
                }
            } else {
                ?>
                <div style="font-size:5vh;">
                    Nu există comenzi plasate!
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