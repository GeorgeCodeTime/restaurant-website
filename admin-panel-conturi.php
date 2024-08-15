<?php
include 'sessionstart.php';
include 'restrict.php';

if (isset($_POST["search-by-everything"])) {
    $search_content = mysqli_real_escape_string($conn, $_POST["search-by-everything-bar"]);
    echo '<script>window.location.href="admin-panel-conturi.php?cautare=' . $search_content . '"</script>';
}

if (isset($_GET["cautare"])) {
    $text_search = mysqli_real_escape_string($conn, $_GET["cautare"]);
} else {
    $text_search = "";
}


$result_select_all_accounts = mysqli_query($conn, "SELECT * FROM conturi");
$total_accounts = mysqli_num_rows($result_select_all_accounts);

$result_select_accounts = mysqli_query($conn, "SELECT * FROM conturi WHERE CONCAT(idcont,nume,prenume,email,telefon,adresa,judet,localitate,tip) LIKE '%$text_search%' ORDER BY data_creare DESC");
$total_results = mysqli_num_rows($result_select_accounts);

$total_comenzi_site = mysqli_num_rows(mysqli_query($conn, "SELECT idcont,status FROM comenzi WHERE status='Livrată'"));
$total_rezervari_site = mysqli_num_rows(mysqli_query($conn, "SELECT idcont,status_rezervare FROM rezervari WHERE status_rezervare = 'Aprobată'"));

$total_incasari = 0;
$result_select_total_comanda = mysqli_query($conn, "SELECT total_comanda,status FROM comenzi WHERE status='Livrată'");
if ($result_select_total_comanda) {
    while ($data = mysqli_fetch_assoc($result_select_total_comanda)) {
        $total_incasari = $total_incasari + $data["total_comanda"];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Conturi</title>
    <link rel="stylesheet" type="text/css" href="admin-panel.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

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


            <a class="middleanchor" href="admin-panel-conturi.php" href="admin-panel-conturi.php">
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

            <div class="search_by_everything_bar">
                <form action="" method="post">
                    <label for="" style="color: #001220;margin-bottom:0;font-size:3.5vh;">Căutare: </label>
                    <input type="text" name="search-by-everything-bar" style="margin-bottom: 0;width:30vh;" placeholder="Caută..." required value="<?php echo $text_search; ?>">
                    <button type="submit" class="search-by-status" name="search-by-everything"><i class="fa fa-search"></i></button>
                </form>
                <a href="admin-panel-conturi.php" class="search-by-status" style="text-decoration: none;margin-bottom:0;"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            </div>

            <div style="display: flex;justify-content:center;align-items:left;gap:1vh;flex-direction:column;position:relative;">
                <div style="display: flex;justify-content:center;align-items:center;gap:1vh;">
                    <p style="margin:0;font-size:2.8vh;background-color:#001220;color:rgb(219, 241, 219);padding:1.5vh 1.5vh;">Rezultate căutare: <?php echo $total_results; ?></p>
                    <p style="margin:0;font-size:2.8vh;background-color:#001220;color:rgb(219, 241, 219);padding:1.5vh 1.5vh;">Total conturi create: <?php echo $total_accounts; ?></p>
                    <p style="margin:0;font-size:2.8vh;background-color:#001220;color:rgb(219, 241, 219);padding:1.5vh 1.5vh;">Total comenzi: <?php echo $total_comenzi_site; ?></p>
                    <p style="margin:0;font-size:2.8vh;background-color:#001220;color:rgb(219, 241, 219);padding:1.5vh 1.5vh;">Total rezervări: <?php echo $total_rezervari_site; ?></p>
                    <p style="margin:0;font-size:2.8vh;background-color:#001220;color:rgb(219, 241, 219);padding:1.5vh 1.5vh;">Încasări comenzi: <?php echo $total_incasari; ?> RON</p>
                </div>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nume</th>
                        <th>Prenume</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Adresă</th>
                        <th>Tip</th>
                        <th>Dată</th>
                        <th>Resetare</th>
                        <th>Comenzi</th>
                        <th>Rezervări</th>
                    </tr>


                    <?php
                    if (mysqli_num_rows($result_select_accounts)) {
                        while ($row = mysqli_fetch_assoc($result_select_accounts)) {
                            $id = $row["idcont"];
                            $nume = $row["nume"];
                            $prenume = $row["prenume"];
                            $email = $row["email"];
                            $telefon = $row["telefon"];
                            $adresa = $row["adresa"] . ", " . $row["judet"] . ", " . $row["localitate"];
                            $tip = $row["tip"];
                            $data_creare = $row["data_creare"];
                            $cod_resetare = $row["cod_resetare"];

                            $total_comenzi_cont = mysqli_num_rows(mysqli_query($conn, "SELECT idcont,status FROM comenzi WHERE idcont = '$id' AND status = 'Livrată' "));
                            $total_rezervari_cont = mysqli_num_rows(mysqli_query($conn, "SELECT idcont,status_rezervare FROM rezervari WHERE idcont = '$id' AND status_rezervare = 'Aprobată' "));

                            if ($cod_resetare === NULL) {
                                $cod_resetare = "NULL";
                            }

                            if ($data_creare === NULL) {
                                $data_creare = "NULL";
                            }
                    ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $nume; ?></td>
                                <td><?php echo $prenume; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $telefon; ?></td>
                                <td><?php echo $adresa; ?></td>
                                <td><?php echo $tip; ?></td>
                                <?php
                                if ($data_creare === "NULL") {
                                ?>
                                    <td style="font-style: italic;"><?php echo $data_creare; ?></td>
                                <?php
                                } else {
                                ?>
                                    <td><?php echo $data_creare; ?></td>
                                <?php
                                }
                                ?>

                                <?php
                                if ($cod_resetare === "NULL") {
                                ?>
                                    <td style="font-style: italic;"><?php echo $cod_resetare; ?></td>
                                <?php
                                } else {
                                ?>
                                    <td><?php echo $cod_resetare; ?></td>
                                <?php
                                }
                                ?>
                                <td><?php echo $total_comenzi_cont; ?></td>
                                <td><?php echo $total_rezervari_cont; ?></td>

                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td style="color: #001220;margin:0;font-size:4vh;" colspan="11">Nu există rezultate pentru căutarea efectuată!</td>
                        </tr>
                    <?php
                    }
                    ?>



                </table>
            </div>

        </div>
    </div>

</body>

</html>