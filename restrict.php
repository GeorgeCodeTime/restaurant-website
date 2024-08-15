<?php
if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {

    } else if ($_SESSION["tip"] == "client") {
        echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
        exit();
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
    exit();
}

?>