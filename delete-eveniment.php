<?php
include 'sessionstart.php';
require 'restrict.php';

if (isset($_SESSION["tip"])) {
    if ($_SESSION["tip"] == "admin") {
        if (isset($_GET['deleteid'])) {
            $idEvent = $_GET['deleteid'];
            $verify_event_id = mysqli_query($conn, "SELECT ideveniment FROM evenimente WHERE ideveniment = '$idEvent' ");
            if (mysqli_num_rows($verify_event_id) === 0) {
                $_SESSION["deny_delete_event_alert"] = "Nu există eveniment cu acest ID!";
                echo "<script>window.location.href='evenimente.php'</script>";
                exit();
            }

            $deleteEvent = "DELETE FROM evenimente WHERE ideveniment=$idEvent";
            $resultDeleteEvent = mysqli_query($conn, $deleteEvent);
            if ($resultDeleteEvent) {
                $_SESSION["success_delete_event_alert"] = "Evenimentul a fost șters cu succes!";
                echo "<script>window.location.href='evenimente.php'</script>";
                exit();
            } else {
                die(mysqli_error($conn));
            }
        }
    } else if ($_SESSION["tip"] == "client") {
        echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
    }
} else {
    echo "<script>alert('PERMISIUNE RESPINSĂ');window.location.href='index.php'</script>";
}
