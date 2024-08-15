<?php
include 'sessionstart.php';
if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] === "admin" or ($_SESSION["tip"] === "client")) {
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesare rezervari</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
    .rezervare-container {
        background-color: #001220;
        color: rgb(219, 241, 219);
        display: flex;
        flex-direction: column;
        width: 60vh;
        gap: 1vh;
        padding: 2vh 2vh;
        text-align: left;
        border: 1vh #009473 dashed;
        border-radius: 1.5vh;
    }

    .rezervare-container p {
        margin: 0;
        font-size: 2.5vh;
    }

    .body {
        margin: 0;
    }

    * {
        font-family: "Bree Serif", serif;
        font-weight: 400;
        font-style: normal;
    }
</style>

<body>
    <?php
    $result_select_rezervari_utilizator = mysqli_query($conn, "SELECT * FROM rezervari WHERE idcont ='$idCont' ORDER BY data_rezervare DESC LIMIT 20");
    if (mysqli_num_rows($result_select_rezervari_utilizator) > 0) {
        while ($row = mysqli_fetch_assoc($result_select_rezervari_utilizator)) {
            $nume_complet = $row["nume_complet"];
            $email_rezervare = $row["email_rezervare"];
            $telefon_rezervare = $row["telefon_rezervare"];
            $data_rezervare = $row["data_rezervare"];
            $ora_rezervare = $row["ora_rezervare"];
            $status_rezervare = $row["status_rezervare"];
            $numar_persoane = $row["numar_persoane"];
            $data_ora_rezervare = $row["data_ora_rezervarii"]
    ?>
            <div class="rezervare-container">
                <p>Nume Complet: <?php echo $nume_complet; ?></p>
                <p>Email: <?php echo $email_rezervare; ?></p>
                <p>Telefon: <?php echo $telefon_rezervare ?></p>
                <p>Număr persoane: <?php echo $numar_persoane ?></p>
                <p>Data efectuării rezervării: <?php echo $data_ora_rezervare ?></p>
                <p style="font-style: italic;">Data și ora rezervării: <?php echo $data_rezervare; ?> <?php echo $ora_rezervare; ?></p>

                <?php
                if ($status_rezervare === "Validare...") {
                ?>
                    <p style="color:orange;font-style: italic;">Status rezervare: <?php echo $status_rezervare; ?></p>
                <?php
                }
                if ($status_rezervare === "Aprobată") {
                ?>
                    <p style="color:green;font-style: italic;">Status rezervare: <?php echo $status_rezervare; ?></p>
                <?php
                }
                if ($status_rezervare === "Anulată") {
                ?>
                    <p style="color:red;font-style: italic;">Status rezervare: <?php echo $status_rezervare; ?></p>
                <?php
                }
                ?>
            </div>
        <?php
        }
    } else {
        ?>
        <div style="font-size:5vh;">
            Nu ai efectutat rezervări!
        </div>
    <?php
    }
    ?>
</body>

</html>