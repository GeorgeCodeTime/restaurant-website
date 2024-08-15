<?php
include 'sessionstart.php';
if (isset($_POST['submitAdd'])) {
    if (!isset($_SESSION["idcont"])) {
        header("Location: evenimente.php");
        exit();
    }
    $eventTitle = mysqli_real_escape_string($conn,$_POST['titlu']);
    $eventDescription = mysqli_real_escape_string($conn,$_POST['descriere']);
    $eventDate = $_POST['data'];
    $image = $_FILES['imageEvent']['name'];
    $image_tmp_name = $_FILES['imageEvent']['tmp_name'];
    $image_folder = 'imagini/events/' . $image;

    $insertEvent = "INSERT into evenimente (titlu,descriere,data,imagine) VALUES ('$eventTitle','$eventDescription','$eventDate','$image')";
    $result_insert = mysqli_query($conn, $insertEvent);

    if ($result_insert) {
        move_uploaded_file($image_tmp_name, $image_folder);
        $_SESSION["success_add_event_alert"] = "Evenimentul a fost adăugat cu succes!";
        echo "<script>window.location.href='evenimente.php'</script>";
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
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="stylesheet" type="text/css" href="evenimente.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Evenimente</title>
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
    if (isset($_SESSION['success_add_event_alert'])) {
    ?>
        <div class="success-message" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_add_event_alert'] ?></div>

    <?php
        unset($_SESSION['success_add_event_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['success_edit_event_alert'])) {
    ?>
        <div class="success-message" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_edit_event_alert'] ?></div>

    <?php
        unset($_SESSION['success_edit_event_alert']);
    }
    ?>

    <?php
    if (isset($_SESSION['deny_delete_event_alert'])) {
    ?>
        <div class="success-message" id="success"><i class="fa fa-ban" aria-hidden="true" style="color: red;"></i> <?php echo $_SESSION['deny_delete_event_alert'] ?></div>

    <?php
        unset($_SESSION['deny_delete_event_alert']);
    }
    ?>


    <?php
    if (isset($_SESSION['success_delete_event_alert'])) {
    ?>
        <div class="success-message" id="success"><i class="fa fa-trash-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_delete_event_alert'] ?></div>

    <?php
        unset($_SESSION['success_delete_event_alert']);
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
                        <h1 style="color:rgb(219, 241, 219);font-size: 4vh;">Intră in cont</h1>
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


    <?php
    if (isset($_SESSION["tip"])) {
        if ($_SESSION["tip"] == "admin") {
    ?>
            <div class="forumBackContent2" id="addForum">
                <center id="center2">
                    <form method="post" id="addForm" class="add-form" enctype="multipart/form-data">
                        <div style="display: flex;justify-content: space-between;align-items:normal;">
                            <h1 style="color: rgb(219, 241, 219);font-size: 4vh;">Detalii Eveniment</h1>
                            <span class="close2">&times;</span>
                        </div>

                        <div>
                            <label class="label1">Titlu</label>
                            <input class="input1" name="titlu" type="text" placeholder="Introdu titlul evenimentului..." required>
                        </div>

                        <div>
                            <label class="label1">Descriere</label>
                            <textarea rows="4" cols="50" class="input1" name="descriere" placeholder="Introdu descrierea evenimentului (max 400 caractere)" maxlength="400" required></textarea>
                        </div>

                        <div>
                            <label class="label1">Data</label>
                            <input class="input1" name="data" type="date" min="<?php echo date('Y-m-d') ?>" placeholder="Introdu data evenimentului(YYYY-MM-DD)" required>
                        </div>

                        <div style="display:flex;flex-direction:row;align-items: center;gap:1vh;">
                            <label class="label1">Imagine eveniment</label>
                            <input class="input1" name="imageEvent" style="width: 30vh;margin:0;padding:0;float:left;color: rgb(219, 241, 219);" type="file" accept="image/png, image/jpg, image/jpeg" required>
                        </div>
                        <center>
                            <div>
                                <button class="button-add" type="submit" name="submitAdd">Adaugă Eveniment</button>
                            </div>
                        </center>
                    </form>
                </center>
            </div>

    <?php
        }
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

    <div class="mainContent">

        <div class="position">
            <?php
            if (isset($_SESSION["tip"])) {
                if ($_SESSION["tip"] == "admin") {
            ?>
                    <a id="addButton" class="addEvent">
                        <span id="addButton">Adaugă Eveniment</span>
                        <div class="round2"><img class="editIcons" src="imagini/icons/add.png"></div>
                    </a>
            <?php
                }
            }
            ?>
            <?php
            $selectEvents = "SELECT * FROM evenimente ORDER BY data ASC";
            $resultSelectEvents = mysqli_query($conn, $selectEvents);
            $exist = false;

            if ($resultSelectEvents) {
                while ($row = mysqli_fetch_assoc($resultSelectEvents)) {
                    $idEveniment = $row["ideveniment"];
                    $titluEvent = $row["titlu"];
                    if (strlen($row["descriere"]) > 1000) {
                        $descriereEvent = substr($row["descriere"], 0, 1000) . "...";
                    } else {
                        $descriereEvent = $row["descriere"];
                    }
                    $dataEvent = $row["data"];
                    $imagineEvent = $row["imagine"];

                    if ($dataEvent >= date("Y-m-d")) {
                        $exist = true;
            ?>
                        <div class="eventContainer">
                            <div class="zoom">
                                <img class="eventImage" src="imagini/events/<?php echo $imagineEvent ?>">
                            </div>

                            <div class="descriere">
                                <p>
                                    <?php echo $descriereEvent ?>
                                </p>
                            </div>

                            <div class="title">
                                <h2>
                                    <?php echo $titluEvent ?>
                                </h2>
                            </div>

                            <div class="dataEvent">
                                <p>Data:
                                    <?php echo $dataEvent ?>
                                </p>
                            </div>

                            <?php
                            if (isset($_SESSION["tip"])) {
                                if ($_SESSION["tip"] == "admin") {
                            ?>
                                    <div class="modifyButtons">
                                        <a style="text-decoration:none;" href="edit-eveniment.php?updateid=<?php echo $idEveniment ?>">
                                            <button class="adminEditButton"><img class="editIcons" src="imagini/icons/edit.png">Edit</button></a>
                                        <a style="text-decoration:none;" id="deleteButton" onclick="deleteConfirmation(<?php echo $idEveniment ?>)">
                                            <button class="adminEditButton" style="background-color:red;"><img class="editIcons" src="imagini/icons/bin.png">Delete</button></a>
                                        </a>
                                    </div>

                                    <div class="confirmationModalContent" id="modalContent">
                                        <center id="center3">
                                            <div class="modalContainer">
                                                <div class="modalHeader">
                                                    <span class="close3" onclick="deleteConfirmation(<?php echo $idEveniment ?>)">&times;</span>
                                                </div>
                                                <p>Ești sigur că vrei să ștergi evenimentul?</p>
                                                <div class="yesNoButtons">
                                                    <a href="" id="cancel" class="cancel" onclick="deleteConfirmation(<?php echo $idEveniment ?>)">Cancel</a>
                                                    <a href="" class="confirmDetele" id="confirmDelete">Delete</a>
                                                </div>
                                            </div>
                                        </center>
                                    </div>
                            <?php

                                }
                            }
                            ?>
                        </div>
            <?php
                    }
                }
            }
            if ($exist == false) {
                echo '<div style="font-size:5vh;margin-top:10vh;">Momentan, nu exsită evenimente viitoare!</div>';
            } ?>

        </div>

        <?php
        if (isset($_SESSION['success_add_event_alert'])) {
        ?>
            <div class="success-message" id="success"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION['success_add_event_alert'] ?></div>

        <?php
            unset($_SESSION['success_add_event_alert']);
        }
        ?>

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


    <div class="copyright">
        <p><span>&#169;</span>Copyright Old But Gold. All rights reserved</p>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success').remove();
            }, 4000);
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
        $(document).ready(function() {
            setTimeout(function() {
                $('#success_login_alert').remove();
            }, 2000);
        });
    </script>

    <script>
        function deleteConfirmation(id) {

            let confirmation = confirm("Ești sigur ca vrei să ștergi acest eveniment?")


            if (confirmation) {
                window.location.href = "delete-eveniment.php?deleteid=" + id;
            }


        }
    </script>

    <script>
        let addForum = document.getElementById("addForum");
        let addButton = document.getElementById("addButton");
        let close2 = document.getElementsByClassName("close2")[0];
        let center2 = document.getElementById("center2");


        addButton.onclick = function() {
            addForum.style.display = "block";
        }

        close2.onclick = function() {
            addForum.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === addForum || event.target === center2) {
                addForum.style.display = "none";
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


        addButton.onclick = function() {
            addForum.style.display = "block";
        }

        close2.onclick = function() {
            addForum.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === addForum || event.target === center2) {
                addForum.style.display = "none";
            }
        }
    </script>
</body>

</html>