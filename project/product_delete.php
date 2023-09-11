<?php
// include database connection
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    $check = "SELECT product_ID FROM order_details WHERE product_ID = ?";

    $checkstmt = $con->prepare($check);
    $checkstmt->bindParam(1, $id);
    $checkstmt->execute();
    $num = $checkstmt->rowCount();

    if ($num > 0) {
        header('Location: product_read.php?action=unableDelete');
    } else {
        $imageQuery = "SELECT product_image FROM products WHERE id = ?";
        $imageStmt = $con->prepare($imageQuery);
        $imageStmt->bindParam(1, $id);
        $imageStmt->execute();

        if ($imageRow = $imageStmt->fetch(PDO::FETCH_ASSOC)) {
            $product_image = $imageRow['product_image'];

            if (file_exists($product_image)) {
                if (unlink($product_image)) {
                    // Product image deleted successfully
                } else {
                    // Unable to delete product image
                    die('Unable to delete product image.');
                }
            }
        }

        // delete query
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: product_read.php?action=deleted');
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