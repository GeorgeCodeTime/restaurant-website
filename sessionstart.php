<?php

require 'connect.php';

error_reporting(0);

session_start();

if (isset($_POST['submitLogIn'])) {
    $emaillogin = mysqli_real_escape_string($conn, $_POST['emaillogin']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($emaillogin) or empty($password)) {
        echo "<script>alert('A apărut o eroare!');window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }

    $selectEmailFromDb = "SELECT * FROM conturi WHERE email = '$emaillogin'";
    $result = mysqli_query($conn, $selectEmailFromDb);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["parola"])) {
            $_SESSION["idcont"] = $row["idcont"];
            $_SESSION["nume"] = $row["nume"];
            $_SESSION["prenume"] = $row["prenume"];
            $_SESSION["tip"] = $row["tip"];
            $_SESSION["telefon"] = $row["telefon"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["adresa"] = $row["adresa"];
            $_SESSION["judet"] = $row["judet"];
            $_SESSION["localitate"] = $row["localitate"];
            $_SESSION["success-login"] = "Ai intrat în cont cu succes!";
            echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "'</script>";
            exit();
        } else {
            echo "<script>alert('Emailul sau parola au fost introduse greșit!');window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Emailul sau parola au fost introduse greșit!');window.location.href='" . $_SERVER['HTTP_REFERER'] . "';</script>";
        exit();
    }
}


if (isset($_SESSION["prenume"])) {
    $prenumeClientComplet = $_SESSION["prenume"];
    if (strlen($_SESSION["prenume"]) < 14) {
        $prenumeClient = $_SESSION["prenume"];
    } else {
        $prenumeClient = substr($_SESSION['prenume'], 0, 3) . ".";
    }
}
if (isset($_SESSION["nume"])) {
    $numeClient = $_SESSION["nume"];
    if (strlen($_SESSION["nume"]) < 14) {
        $numeClient = $_SESSION["nume"];
    } else {
        $numeClient = substr($_SESSION['nume'], 0, 3) . ".";
    }
}

if (isset($_SESSION["idcont"])) {
    $idCont = $_SESSION["idcont"];
}

if (isset($_SESSION["tip"])) {
    $tipCont = $_SESSION["tip"];
}
if (isset($_SESSION["telefon"])) {
    $telefonCont = $_SESSION["telefon"];
}
if (isset($_SESSION["email"])) {
    $emailCont = $_SESSION["email"];
}
if (isset($_SESSION["adresa"])) {
    $adresaCont = $_SESSION["adresa"];
}
if (isset($_SESSION["judet"])) {
    $judetCont = $_SESSION["judet"];
}
if (isset($_SESSION["localitate"])) {
    $localitateCont = $_SESSION["localitate"];
}

if (isset($_SESSION["success-login"])) {
?>
    <div class="success-message-container-login" id="success_login_alert">
        <div class="success-message-login" style="background-color:#001220;color:rgb(219, 241, 219);"><i class="fa fa-check-circle-o" aria-hidden="true" style="color: green;"></i> <?php echo $_SESSION["success-login"]; ?></div>
    </div>
<?php
    unset($_SESSION["success-login"]);
}
?>


<html>
<style>
    .success-message-login {
        color: #001220;
        font-size: 5vh;
        text-align: center;
        background-color: rgb(144, 236, 52);
        z-index: 9;
        padding: 2vh 2vh;
        border-radius: 2vh;
        border: solid 0.3vh #001220;
        width: 80vh;
    }

    .success-message-container-login {
        display: flex;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 9;
    }
</style>

</html>