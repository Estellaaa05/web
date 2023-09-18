<?php
// include database connection
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $category_ID = isset($_GET['category_ID']) ? $_GET['category_ID'] : die('ERROR: Record ID not found.');

    $check = "SELECT product_ID FROM products WHERE category_ID = ?";

    $checkstmt = $con->prepare($check);
    $checkstmt->bindParam(1, $category_ID);
    $checkstmt->execute();
    $num = $checkstmt->rowCount();

    if ($num > 0) {
        header('Location: product_read.php?action=unableDelete');
    } else {
        // delete query
        $query = "DELETE FROM product_categories WHERE category_ID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $category_ID);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: categories_read.php?action=deleted');
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