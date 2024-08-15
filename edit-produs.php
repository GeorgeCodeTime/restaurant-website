<?php

include 'sessionstart.php';
require 'restrict.php';

if (isset($_GET["update-produs"])) {
    $idprodus_edit = $_GET["update-produs"];
    $select_edit_produs = "SELECT * FROM produse WHERE idprodus = '$idprodus_edit'";
    $result_select_edit_produs = mysqli_query($conn, $select_edit_produs);

    if ($row = mysqli_fetch_assoc($result_select_edit_produs)) {
        $numeprodus_edit = $row["numeprodus"];
        $descriereprodus_edit = $row["descriereprodus"];
        $pretprodus_edit = $row["pret"];
        $categorie_edit = $row["categorie"];
        $imagineprodus_edit = $row["imagine"];
    }
}

if (isset($_POST['submitAdd'])) {
    $productName_final = mysqli_real_escape_string($conn,$_POST['nume-produs']);
    $productDesc_final = mysqli_real_escape_string($conn,$_POST['descriere-produs']);
    $productPrice_final = mysqli_real_escape_string($conn,$_POST['pret-produs']);
    $productCategory_final = mysqli_real_escape_string($conn,$_POST['categorie-produs']);
    $image = $_FILES['imagine-produs']['name'];
    $image_tmp_name = $_FILES['imagine-produs']['tmp_name'];
    $image_folder = 'imagini/delivery/' . $image;

    if (empty($image)) {
        $image = $imagineprodus_edit;
    }

    $insertProduct_final = "UPDATE produse SET numeprodus = '$productName_final',descriereprodus = '$productDesc_final',pret = '$productPrice_final',imagine = '$image',categorie = '$productCategory_final' WHERE idprodus = '$idprodus_edit'";
    $result_insert_final = mysqli_query($conn, $insertProduct_final);

    if ($result_insert_final) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $_SESSION["add_to_cart_alert"] = "Produsul a fost modificat cu succes!";
        echo "<script>window.location.href='delivery.php';</script>";
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="edit-produs.css">
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Produs</title>

</head>

<body>
    <div class="container-preloader" id="preloader">
        <div class="animation-preloader">
            <div class="txt-loading">
                <img src="imagini/old-but-gold-high-resolution-logo-transparent.png">
            </div>
            <i class="fa fa-spinner fa-spin" style="font-size:30vh;color:#009473;"></i>
        </div>
        <div class="loader-section"></div>
    </div>
    <div class="editContainer">

        <section>
            <form method="post" id="addForm" class="add-form" enctype="multipart/form-data">
                <div style="display: flex;justify-content: space-between;align-items:normal;">
                    <h1 style="color: rgb(219, 241, 219);font-size: 4vh;">Detalii Produs</h1>
                </div>

                <div>
                    <label class="label1">Nume Produs</label>
                    <input class="input1" name="nume-produs" type="text" placeholder="Introdu titlul produsului..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $numeprodus_edit; ?>">
                </div>

                <div>
                    <label class="label1">Descriere</label>
                    <textarea rows="2" cols="50" class="input1" name="descriere-produs" maxlength="80" placeholder="Introdu descrierea produsului(maxim 80 de caractere)..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')"><?php echo $descriereprodus_edit; ?></textarea>
                </div>

                <div>
                    <label class="label1">Preț</label>
                    <input class="input1" name="pret-produs" type="number" placeholder="Introdu prețul produsului..." required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')" value="<?php echo $pretprodus_edit; ?>">
                </div>

                <div style="display:flex;justify-content:left;align-items:center;gap:2vh;">
                    <label class="label1">Categorie</label>
                    <select name="categorie-produs" id="" required oninvalid="this.setCustomValidity('Acest câmp este obligatoriu!')" oninput="this.setCustomValidity('')">
                        <option name="categorie-produs" value="<?php echo $categorie_edit; ?>">
                            <?php echo $categorie_edit; ?>
                        </option>
                        <option name="categorie-produs" value="micdejun">Mic Dejun</option>
                        <option name="categorie-produs" value="pizza">Pizza</option>
                        <option name="categorie-produs" value="paste">Paste</option>
                        <option name="categorie-produs" value="burger">Burger</option>
                        <option name="categorie-produs" value="gratar">Grătar</option>
                        <option name="categorie-produs" value="salate">Salate</option>
                        <option name="categorie-produs" value="ciorbe">Ciorbe</option>
                        <option name="categorie-produs" value="garnituri">Garnituri</option>
                        <option name="categorie-produs" value="deserturi">Deserturi</option>
                        <option name="categorie-produs" value="bauturi">Băuturi</option>
                        <option name="categorie-produs" value="oferta">Ofertă</option>
                    </select>
                </div>

                <div style="display:flex;flex-direction:row;align-items: center;gap:1vh;">
                    <label class="label1">Imagine produs</label>
                    <input class="input1" name="imagine-produs" style="width: 30vh;margin:0;padding:0;float:left;color: rgb(219, 241, 219);display:flex;border:none;" type="file" accept="image/png, image/jpg, image/jpeg">
                </div>
                <center>
                    <div>
                        <a class="button-add" href="delivery.php" style="background-color:red;text-decoration:none;">Anulează</a>
                        <button class="button-add" type="submit" name="submitAdd">Actualizează</button>
                    </div>
                </center>
            </form>


        </section>

        <section>
            <center>
                <h1 style="font-weight:900;font-size:3vh;">Produs actual</h1>
            </center>
            <div class="product-container">
                <div class="food-img-zoom">
                    <img class="food-img" src="imagini/delivery/<?php echo $imagineprodus_edit ?>">
                </div>

                <div class="product-name">
                    <p>
                        <?php echo $numeprodus_edit ?>
                    </p>
                </div>

                <div class="product-description">
                    <p>
                        <?php echo $descriereprodus_edit ?>
                    </p>
                </div>

                <div class="product-price">
                    <p>
                        <?php echo $pretprodus_edit ?><span> RON</span>
                    </p>
                </div>
            </div>
        </section>

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

</body>

</html>