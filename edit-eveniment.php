<?php
include ('sessionstart.php');
require ('restrict.php');

if (isset ($_GET["updateid"])) {
    $idEveniment = $_GET["updateid"];
    $selectEvent = "SELECT * FROM evenimente WHERE ideveniment = $idEveniment";
    $result_select = mysqli_query($conn, $selectEvent);

    if ($row = mysqli_fetch_assoc($result_select)) {
        $titluEvent = $row["titlu"];
        $descriereEvent = $row["descriere"];
        $dataEvent = $row["data"];
        $imagineEvent = $row["imagine"];
    }
}

if (isset ($_POST["updateEvent"])) {
    $titleEvent = mysqli_real_escape_string($conn, $_POST['titlu']);
    $descEvent = mysqli_real_escape_string($conn, $_POST["descriere"]);
    $dateEvent = $_POST["data"];
    $image = $_FILES['update_image']['name'];
    $image_tmp_name = $_FILES['update_image']['tmp_name'];
    $image_folder = 'imagini/events/' . $image;

    if (empty ($image)) {
        $image = $imagineEvent;
    }

    $update = "UPDATE evenimente SET titlu='$titleEvent',descriere ='$descEvent',data = '$dateEvent', imagine='$image' WHERE ideveniment = '$idEveniment'";
    $result_update = mysqli_query($conn, $update);

    if (isset ($result_update)) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $_SESSION["success_edit_event_alert"] = "Evenimentul a fost modificat cu succes!";
        echo "<script>window.location.href='evenimente.php'</script>";
        exit();
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" type="text/css" href="edit-eveniment.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap"
        rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Edit Eveniment</title>
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
            <h3 style="color:rgb(219, 241, 219);">Modifică Eveniment</h3>
            <form method="post" class="updateForm" enctype="multipart/form-data">
                <div>
                    <label class="label1">Titlu</label>
                    <input class="input1" name="titlu" type="text" value="<?php echo $titluEvent; ?>"
                        placeholder="Introdu titlul evenimentului..." required>
                </div>

                <div>
                    <label class="label1">Descriere</label>
                    <textarea rows="4" cols="50" class="input1" name="descriere" type="text"
                        placeholder="Introdu descrierea evenimentului..."
                        required maxlength="400"><?php echo $descriereEvent; ?></textarea>
                </div>

                <div>
                    <label class="label1">Data</label>
                    <input class="input1" name="data" type="date" value="<?php echo $dataEvent; ?>"
                        placeholder="Introdu data evenimentului(YYYY-MM-DD)" required>
                </div>

                <div>
                    <label class="label1">Imagine eveniment</label>
                    <input class="input1" name="update_image" style="width: 30vh;color:rgb(219, 241, 219);" type="file"
                        accept="image/png, image/jpg, image/jpeg">
                </div>
                <center>
                    <div class="backOrUpdate">
                        <a href="evenimente.php" style="text-decoration:none;">
                            <button class="backTo" type="button">Anulează</button>
                        </a>

                        <button class="updateEvent" type="submit" name="updateEvent">Actualizează</button>
                    </div>
                </center>
            </form>
        </section>
        <div class="nowMenu">
            <center>
                <h3 style="color:#001220;;margin:0;">Eveniment curent</h3>
            </center>
            <div class="eventContainer">
                <div class="zoom">
                    <img class="eventImage" src="imagini/events/<?php echo $imagineEvent ?>">
                </div>
                <div class="title">
                    <h2>
                        <?php echo $titluEvent ?>
                    </h2>
                </div>
                <div class="descriere">
                    <p>
                        <?php echo $descriereEvent ?>
                    </p>
                </div>


                <div class="dataEvent">
                    <p>Data:
                        <?php echo $dataEvent ?>
                    </p>
                </div>
            </div>
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