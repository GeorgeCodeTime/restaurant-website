<?php
include ('sessionstart.php');
require ('restrict.php');
if (isset ($_GET['updateid'])) {
    $id = $_GET['updateid'];
    $selectMeniu = "SELECT * FROM imaginimeniu WHERE image_id = $id";
    $result_select = mysqli_query($conn, $selectMeniu);

    if ($row = mysqli_fetch_assoc($result_select)) {
        $numeImg = $row['image_name'];
        $img = $row['image'];
    }
}

if (isset ($_POST['updateMenu'])) {
    $image_name = $_POST['image_name'];
    $image = $_FILES['update_image']['name'];
    $image_tmp_name = $_FILES['update_image']['tmp_name'];
    $image_folder = 'imagini/' . $image;

    $update = "UPDATE imaginimeniu SET image_name='$image_name', image='$image' WHERE image_id='$id'";
    $result_update = mysqli_query($conn, $update);
    if ($result_update) {
        move_uploaded_file($image_tmp_name, $image_folder);
        if (isset ($_GET['updateid'])) {
            $id2 = $_GET['updateid'];
            if ($id2 < 10) {
                $_SESSION["food_success_alert"] = "Meniul a fost modificat cu succes!";
                echo "<script>window.location.href='meniu-mancare.php'</script>";
                exit();
            } else {
                $_SESSION["drinks_success_alert"] = "Meniul a fost modificat cu succes!";
                echo "<script>window.location.href='meniu-bauturi.php'</script>";
                exit();
            }
        }
    } else {
        die (mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Meniu</title>
    <link rel="stylesheet" type="text/css" href="edit-meniu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap"
        rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <h3>Modifică Meniu</h3>
            <form method="post" class="updateForm" enctype="multipart/form-data">
                <div>
                    <label class="label1">Nume meniu</label>
                    <input class="input1" name="image_name" type="text" value="<?php echo $numeImg; ?>"
                        placeholder="Introdu numele meniului..." required>
                </div>

                <div>
                    <label class="label1">Imagine meniu</label>
                    <input class="input1" name="update_image" style="width: 30vh;color:rgb(219, 241, 219);" type="file"
                        accept="image/png, image/jpg, image/jpeg" required>
                </div>
                <center>
                    <?php
                    if (isset ($_GET['updateid'])) {
                        $id2 = $_GET['updateid'];
                        if ($id2 < 10) {
                            ?>
                            <a href="meniu-mancare.php" style="text-decoration:none;">
                                <button class="backTo" type="button">Anulează</button>
                            </a>
                            <?php
                        } else {
                            ?>
                            <a href="meniu-bauturi.php" style="text-decoration:none;">
                                <button class="backTo" type="button">Anulează</button>
                            </a>
                            <?php
                        }
                    }
                    ?>
                    <button class="updateMenu" type="submit" name="updateMenu">Actualizează</button>
                </center>
            </form>
        </section>
        <div class="nowMenu">
            <center>
                <h3 style="color:black;margin:0;">Imaginea actuală</h3>
            </center>
            <img class="imgMenu" src="imagini/<?php echo $img; ?>">
        </div>
    </div>

    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('#preloader').delay(100).queue(function () {
                    $(this).remove();
                });
            }, 400);
        });
    </script>
</body>

</html>