<?php
// include database connection
include 'config/database.php';

try {
    $order_ID = isset($_GET['order_ID']) ? $_GET['order_ID'] : die('ERROR: Record ID not found.');

    // delete query
    $sumQuery = "DELETE FROM order_summary WHERE order_ID = ?";
    $sumStmt = $con->prepare($sumQuery);
    $sumStmt->bindParam(1, $order_ID);

    $detailQuery = "DELETE FROM order_details WHERE order_ID = ?";
    $detailStmt = $con->prepare($detailQuery);
    $detailStmt->bindParam(1, $order_ID);

    if ($sumStmt->execute() && $detailStmt->execute()) {
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: orderSummary_read.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }

}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>