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
    <title>Procesare comenzi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Noto+Sans:wght@100..900&family=Rubik:ital@0;1&display=swap" rel="stylesheet">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style>
    .comanda-container {
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

    .comanda-container p {
        margin: 0;
        font-size: 2.5vh;
    }
    
    * {
        font-family: "Bree Serif", serif;
        font-weight: 400;
        font-style: normal;
    }

    .body {
        margin: 0;
    }
</style>

<body>
    <?php
    $result_select_comenzi_utilizator = mysqli_query($conn, "SELECT * FROM comenzi WHERE idcont ='$idCont' ORDER BY data_comanda DESC LIMIT 20");
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
    ?>
            <div class="comanda-container">
                <p>Destinatar: <?php echo $nume_destinatar; ?><span> <?php echo $prenume_destinatar; ?></span></p>
                <p>Adresă: <?php echo $adresa_destinatar; ?>, <?php echo $sector; ?></p>
                <p>Telefon: <?php echo $telefon_destinatar ?></p>
                <p>Produse: <?php echo $produse_comanda; ?></p>
                <p>Total: <?php echo $total_comanda; ?> RON</p>
                <p>Data comenzii: <?php echo $data_comanda; ?></p>
                <p>Metodă plată: <?php echo $metoda_plata ?></p>

                <?php
                if ($status === "Pregătire") {
                ?>
                    <p style="color:orange;">Cod: <?php echo $cod_comanda; ?></p>
                    <p style="color:orange;">Status: <?php echo $status ?></p>
                <?php
                }
                if ($status === "Livrată") {
                ?>
                    <p style="color:green;">Cod: <?php echo $cod_comanda; ?></p>
                    <p style="color:green;">Status: <?php echo $status ?></p>
                <?php
                }
                if ($status === "Anulată") {
                ?>
                    <p style="color:red;">Cod: <?php echo $cod_comanda; ?></p>
                    <p style="color:red;">Status: <?php echo $status ?></p>
                <?php
                }
                ?>
            </div>
        <?php
        }
    } else {
        ?>
        <div style="font-size:5vh;">
            Nu ai efectutat comenzi!
        </div>
    <?php
    }
    ?>
</body>

</html>