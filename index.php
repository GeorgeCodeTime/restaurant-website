<?php
include("sessionstart.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="bars.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <div class="main-containter">

        <div class="slide-one">
            <div class="slide-show-container">
                <div class="wrapper">
                    <img class="img11" src="imagini/restaurant1.webp">
                    <img class="img1" src="imagini/restaurant2.webp">
                    <img class="img1" src="imagini/restaurant3.webp">
                    <img class="img1" src="imagini/restaurant4.webp">
                    <img class="img11" src="imagini/restaurant1.webp">
                </div>
            </div>
        </div>

        <div class="slide-two">
            <div class="text-bg">
                <p>Noul nostru restaurant "Old but Gold" îmbină o tematică "Vintage" cu moda contemporană. De asemenea,
                    calitatea noastră principală o reprezintă
                    diversitatea culinară. La noi veți regăsi gustul de altădată și veți pleca spunând "Am mâncat ca la
                    mama
                    acasă".
                </p>
                <a class="view-menu-anchor" href="meniu.php">Vezi meniuri</a>
            </div>


            <div style="display: flex;justify-content:center;align-items:center;" class="twoImagesSlideTwo">
                <img src="imagini/bauturi.jpg" class="food-drinks-img" alt="">
                <img src="imagini/mancare.jpg" class="food-drinks-img" alt="">
            </div>

        </div>

        <div class="slide-three">
            <?php
            $result_select_random_products = mysqli_query($conn, "SELECT numeprodus,imagine FROM produse WHERE (categorie = 'pizza' OR categorie = 'pasta' OR categorie = 'burger' OR categorie = 'deserturi' OR categorie = 'gratar')ORDER BY RAND() LIMIT 3");
            if (mysqli_num_rows($result_select_random_products) > 0) {
                while ($product_data = mysqli_fetch_assoc($result_select_random_products)) {
                    $product_name = $product_data["numeprodus"];
                    $product_img = $product_data["imagine"];
            ?>
                    <div class="mini-delivery">
                        <img src="imagini/delivery/<?php echo $product_img; ?>" alt="">
                        <p><?php echo $product_name; ?></p>
                    </div>
            <?php
                }
            }
            ?>

            <div class="mini-delivery2">
                <p style="width:30vh;">Intră să vezi întreaga ofertă, pofta vine mâncând...</p>
                <a href="delivery.php" class="order-now-anchor">Comandă acum</a>
            </div>
        </div>

        <div class="slide-four">
            <div class="res-txt">
                <p style="width: 150vh;">
                    Nu lăsa nimic în voia întâplării, asigură-te că vei avea o masă rezervată. Nu te costă nimic sa faci o rezervare, iar ca
                    mic pont, nu durează mai mult de 2 minute întregul proces.
                </p>
            </div>
            <div class="slide-four-second-sections">
                <div class="reservation-containers">
                    <p>Câte persoane?</p>
                    <img class="res-imgs" src="imagini/icons/people.png" alt="">
                </div>
                <div class="reservation-containers">
                    <p>În ce zi?</p>
                    <img class="res-imgs" src="imagini/icons/calendar.png" alt="">
                </div>
                <div class="reservation-containers">
                    <p>La ce oră?</p>
                    <img class="res-imgs" src="imagini/icons/clock.png" alt="">
                </div>
            </div>
            <a href="rezervari.php" class="res-anchor">Rezervă-ți masa</a>
        </div>

        <div class="slide-five">
            <a href="evenimente.php" class="all-events-anchor">Vezi toate evenimentele</a>
            <div class="slide-five-second-section">
                <?php
                $result_select_three_events = mysqli_query($conn, "SELECT * FROM evenimente ORDER BY data DESC LIMIT 3");
                if (mysqli_num_rows($result_select_three_events) > 0) {
                    while ($row = mysqli_fetch_assoc($result_select_three_events)) {
                        $titlu_eveniment_index = $row["titlu"];
                        $data_eveniment_index = $row["data"];
                        $imagine_eveniment_index = $row["imagine"];
                        if ($data_eveniment_index > date('Y-m-d')) {
                ?>


                            <div class="mini-events-container">
                                <div style="overflow:hidden;height:30vh;" class="imgZoomContainer">
                                    <img src="imagini/events/<?php echo $imagine_eveniment_index ?>" alt="">
                                </div>
                                <p><?php echo $titlu_eveniment_index ?></p>
                                <p>Data: <?php echo $data_eveniment_index ?></p>
                            </div>

                        <?php
                        } else {
                        ?>
                            <div class="mini-events-container">
                                <div style="overflow:hidden;height:30vh;" class="imgZoomContainer">
                                    <img src="imagini/events/<?php echo $imagine_eveniment_index ?>" alt="">
                                </div>
                                <p><?php echo $titlu_eveniment_index ?></p>
                                <p>Data: <?php echo $data_eveniment_index ?></p>
                            </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="events-txt">
                <p style="width:150vh">La noi, în fiecare săptămână vei fi surprins cu un eveniment nou, așă că nu îți face griji dacă l-ai ratat pe unul. Pentru
                    a fi la curent cu cele mai noi evenimente ne poți urmări pe rețelele noastre de socializare sau poți accesa pagina de evenimente!</p>
            </div>

        </div>

        <div class="slide-six">
            <p style="color:rgb(219, 241, 219);font-size:4.5vh">Ce spun clienții noștri?</p>
            <div class="all-reviews-content">
                <?php
                $result_select_random_reviews = mysqli_query($conn, "SELECT * FROM recenzii INNER JOIN conturi USING (idcont) WHERE nota >= 4 AND length(mesaj) BETWEEN 200 AND 300 ORDER BY rand() LIMIT 3");

                if (mysqli_num_rows($result_select_random_reviews) > 0) {
                    while ($row_review = mysqli_fetch_assoc($result_select_random_reviews)) {
                        $nume_client_review_index = $row_review["nume"];
                        $prenume_client_review_index = substr($row_review["prenume"], 0, 1) . ".";
                        $nota_recenzie_index = $row_review["nota"];
                        $mesaj_recenzie_index = $row_review["mesaj"];

                ?>
                        <div class="one-review-content">
                            <p><?php echo $nume_client_review_index; ?> <?php echo $prenume_client_review_index; ?></p>
                            <?php
                            if ($nota_recenzie_index == 5) {
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

                            if ($nota_recenzie_index == 4) {
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
                            ?>
                            <p class="msg-review"><?php echo $mesaj_recenzie_index ?></p>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <a href="recenzii.php">
                Alte recenzii
            </a>
        </div>

        <div class="slide-seven">
            <div style="display: flex;justify-content: center;align-items:center;flex-direction:column;text-align:center;">
                <p style="font-size: 4vh;color:#001220;;width:61vh;background-color:rgb(219, 241, 219);margin:0;padding: 3vh 6vh;border-bottom: 2vh #001220 solid;border-top-left-radius:2vh;border-top-right-radius:2vh;" class="locatie_program_titlu">Cum și unde dai de noi?</p>
                <div class="first-section">
                    <p><i class="fa fa-thumb-tack" aria-hidden="true"></i> Timpuri Noi Square</p>
                    <p><i class="fa fa-envelope" aria-hidden="true"></i> oldbutgold@contact.ro</p>
                    <p><i class="fa fa-mobile" aria-hidden="true"></i> 0711223344</p>
                </div>
            </div>

            <div style="display: flex;justify-content: center;align-items:center;flex-direction:column;text-align:center;">
                <p style="font-size: 4vh;color:#001220;;width:61vh;background-color:rgb(219, 241, 219);margin:0;padding: 3vh 6vh;border-bottom: 2vh #001220 solid;border-top-left-radius:2vh;border-top-right-radius:2vh;" class="locatie_program_titlu">Te așteptăm la noi în intervalul...</p>
                <div class="second-section">
                    <p><i class="fa fa-calendar" aria-hidden="true"></i> Luni-Joi - 10:30 - 00:00</p>
                    <p><i class="fa fa-calendar" aria-hidden="true"></i> Vineri-Sâmbătă - 10:30 - 03:00</p>
                    <p><i class="fa fa-calendar" aria-hidden="true"></i> Duminică - 10:30 - 23:00</p>
                </div>
            </div>
        </div>

        <div class="slide-eigth">
            <p style="font-size:6vh;width:60vh;">Fii primul care află...</p>
            <div style="display:flex;justify-content:center;align-items:center;gap:5vh;" class="slide_eight_sm">
                <a href="https://www.instagram.com/" target="_blank" class="social-media-anchor">
                    <img src="imagini/instagram.png" style="width:30vh;" alt="">
                </a>
                <a href="https://www.facebook.com/" target="_blank" class="social-media-anchor">
                    <img src="imagini/facebook.png" style="width:30vh;" alt="">
                </a>
                <a href="https://www.tiktok.com/" target="_blank" class="social-media-anchor">
                    <img src="imagini/tiktok2.png" style="width:30vh;" alt="">
                </a>
            </div>
        </div>


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

    <div class="copyright">
        <p><span>&#169;</span>Copyright Old But Gold. All rights reserved</p>
    </div>

    <?php

    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === 'http://localhost/restaurant/logout.php') {
    ?>
        <div class="success-message-container-logout" id="success_logout_alert">
            <div class="success-message-logout"><i class="fa fa-frown-o" aria-hidden="true" style="color: green;"></i> Ai ieșit din cont cu succes!</div>
        </div>
    <?php
    }
    ?>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#success_logout_alert').remove();
            }, 2000);
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