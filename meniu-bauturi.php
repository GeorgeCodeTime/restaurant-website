<?php
include("sessionstart.php");

$select1 = "SELECT * from imaginimeniu where image_id=10";
$result1 = mysqli_query($conn, $select1);
if ($result1) {
    $row1 = mysqli_fetch_assoc($result1);
    $img1 = $row1['image'];
}

$select2 = "SELECT * from imaginimeniu where image_id=11";
$result2 = mysqli_query($conn, $select2);
if ($result2) {
    $row2 = mysqli_fetch_assoc($result2);
    $img2 = $row2['image'];
}

$select3 = "SELECT * from imaginimeniu where image_id=12";
$result3 = mysqli_query($conn, $select3);
if ($result3) {
    $row3 = mysqli_fetch_assoc($result3);
    $img3 = $row3['image'];
}

$select4 = "SELECT * from imaginimeniu where image_id=13";
$result4 = mysqli_query($conn, $select4);
if ($result4) {
    $row4 = mysqli_fetch_assoc($result4);
    $img4 = $row4['image'];
}

$select5 = "SELECT * from imaginimeniu where image_id=14";
$result5 = mysqli_query($conn, $select5);
if ($result5) {
    $row5 = mysqli_fetch_assoc($result5);
    $img5 = $row5['image'];
}

$select6 = "SELECT * from imaginimeniu where image_id=15";
$result6 = mysqli_query($conn, $select6);
if ($result6) {
    $row6 = mysqli_fetch_assoc($result6);
    $img6 = $row6['image'];
}

$select7 = "SELECT * from imaginimeniu where image_id=16";
$result7 = mysqli_query($conn, $select7);
if ($result7) {
    $row7 = mysqli_fetch_assoc($result7);
    $img7 = $row7['image'];
}

$select8 = "SELECT * from imaginimeniu where image_id=17";
$result8 = mysqli_query($conn, $select8);
if ($result8) {
    $row8 = mysqli_fetch_assoc($result8);
    $img8 = $row8['image'];
}

$select9 = "SELECT * from imaginimeniu where image_id=18";
$result9 = mysqli_query($conn, $select9);
if ($result9) {
    $row9 = mysqli_fetch_assoc($result9);
    $img9 = $row9['image'];
}

$select10 = "SELECT * from imaginimeniu where image_id=19";
$result10 = mysqli_query($conn, $select10);
if ($result10) {
    $row10 = mysqli_fetch_assoc($result10);
    $img10 = $row10['image'];
}

$select11 = "SELECT * from imaginimeniu where image_id=20";
$result11 = mysqli_query($conn, $select11);
if ($result11) {
    $row11 = mysqli_fetch_assoc($result11);
    $img11 = $row11['image'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="meniu-bauturi.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Meniu Băuturi</title>
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

    <?php
    if (isset($_SESSION['drinks_success_alert'])) {
    ?>
        <div class="success-message-container" id="success">
            <div class="success-message"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['drinks_success_alert'] ?></div>
        </div>

    <?php
        unset($_SESSION['drinks_success_alert']);
    }
    ?>

    <script>
        function hrefSite(url) {
            window.open(url, '_blank');
        }
    </script>

    <div class="left-side-bar">

        <div class="twoByTwo">
            <a class="icn-text" href="meniu.php">
                <img class="icons" src="imagini/previous.png">
                <span>Înapoi</span>
            </a>
            <a class="icn-text" href="#section1">
                <img class="icons" src="imagini/beer.png">
                <span>Bere</span>
            </a>
        </div>


        <div class="twoByTwo">
            <a class="icn-text" href="#section2">
                <img class="icons" src="imagini/whiskey.png">
                <span>Whiskey&Gin</span>
            </a>
            <a class="icn-text" href="#section3">
                <img class="icons" src="imagini/cocktail.png">
                <span>Cocktailuri</span>
            </a>
        </div>


        <div class="twoByTwo">
            <a class="icn-text" href="#section4">
                <img class="icons" src="imagini/longdrinks.png">
                <span>Long Drinks</span>
            </a>
            <a class="icn-text" href="#section5">
                <img class="icons" src="imagini/white-wine.png">
                <span>Vinuri Albe</span>
            </a>
        </div>


        <div class="twoByTwo">
            <a class="icn-text" href="#section6">
                <img class="icons" src="imagini/rose-wine.png">
                <span>Vinuri Rose</span>
            </a>
            <a class="icn-text" href="#section7">
                <img class="icons" src="imagini/red-wine.png">
                <span>Vinuri Roșii</span>
            </a>
        </div>


        <div class="twoByTwo">
            <a class="icn-text" href="#section8">
                <img class="icons" src="imagini/vodka.png">
                <span>Spirits</span>
            </a>
            <a class="icn-text" href="#section9">
                <img class="icons" src="imagini/shot.png">
                <span>Shots</span>
            </a>
        </div>


        <div class="twoByTwo">
            <a class="icn-text" href="#section10">
                <img class="icons" src="imagini/can.png">
                <span>Răcoritoare</span>
            </a>
            <a class="icn-text" href="#section11">
                <img class="icons" src="imagini/coffee-cup.png">
                <span>Hot Drinks</span>
            </a>
        </div>

    </div>

    <div class="social-media">
        <div class="sm">
            <img src="imagini/tiktok.png" onclick="hrefSite('https://www.tiktok.com/en?lang=en')">
        </div>
        <div class="sm">
            <img src="imagini/facebook.png" onclick="hrefSite('https://www.facebook.com/')">
        </div>
        <div class="sm">
            <img src="imagini/instagram.png" onclick="hrefSite('https://www.instagram.com/')">
        </div>
    </div>
    </div>

    <div class="container">
        <div id="section1">
            <img class="image" src="imagini/<?php echo $img1 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=10" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section2">
            <img class="image" src="imagini/<?php echo $img2 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=11" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section3">
            <img class="image" src="imagini/<?php echo $img3 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=12" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section4">
            <img class="image" src="imagini/<?php echo $img4 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=13" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section5">
            <img class="image" src="imagini/<?php echo $img5 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=14" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section6">
            <img class="image" src="imagini/<?php echo $img6 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=15" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section7">
            <img class="image" src="imagini/<?php echo $img7 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=16" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section8">
            <img class="image" src="imagini/<?php echo $img8 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=17" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section9">
            <img class="image" src="imagini/<?php echo $img9 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=18" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section10">
            <img class="image" src="imagini/<?php echo $img10 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=19" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <div id="section11">
            <img class="image" src="imagini/<?php echo $img11 ?>">
            <?php
            if (isset($_SESSION['tip'])) {
                if ($_SESSION['tip'] == 'admin') {
            ?>
                    <a href="edit-meniu.php?updateid=20" class="editButton">Editează</a>
            <?php
                }
            }
            ?>
        </div>

        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('#success').remove();
                }, 3000);
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


    </div>
</body>

</html>