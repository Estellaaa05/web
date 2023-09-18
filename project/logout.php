<?php
session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["warning"] = "Please login to proceed.";
    header("Location:index.php");
    exit;
}
session_unset();
session_destroy();
header("Location:index.php");
exit;
?>