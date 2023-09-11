<?php
// include database connection
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $ID = isset($_GET['ID']) ? $_GET['ID'] : die('ERROR: Record ID not found.');

    $check = "SELECT customer_ID FROM order_summary WHERE customer_ID = ?";

    $checkstmt = $con->prepare($check);
    $checkstmt->bindParam(1, $ID);
    $checkstmt->execute();
    $num = $checkstmt->rowCount();

    if ($num > 0) {
        header('Location: customer_read.php?action=unableDelete');
    } else {
        $imageQuery = "SELECT customer_image FROM customers WHERE ID = ?";
        $imageStmt = $con->prepare($imageQuery);
        $imageStmt->bindParam(1, $ID);
        $imageStmt->execute();

        if ($imageRow = $imageStmt->fetch(PDO::FETCH_ASSOC)) {
            $customer_image = $imageRow['customer_image'];

            if (file_exists($customer_image)) {
                if (unlink($customer_image)) {
                    // customer_image deleted successfully
                } else {
                    // Unable to delete customer image
                    die('Unable to delete customer image.');
                }
            }
        }

        // delete query
        $query = "DELETE FROM customers WHERE ID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $ID);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: customer_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }

    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>