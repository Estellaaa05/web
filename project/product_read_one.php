<?php
session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["warning"] = "Please login to proceed.";
    header("Location:login_form.php");
    exit;
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, product_image, p.category_ID, c.category_name, description, price, promotion_price, manufacture_date, expired_date, created FROM products p 
            LEFT JOIN product_categories c ON p.category_ID = c.category_ID WHERE id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            // extract($row);
            $name = $row['name'];
            $product_image = $row['product_image'];
            $category_ID = $row['category_ID'];
            $category_name = $row['category_name'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
            $created = $row['created'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td>
                    <?php echo htmlspecialchars($name, ENT_QUOTES);
                    $imageSource = !empty($product_image) ? $product_image :
                        'http://localhost/web/project/img/default_product_photo.jpg';
                    echo "<br><img src={$imageSource} width=100px height=100px>"; ?>
                </td>
            </tr>
            <tr>
                <td>Category</td>
                <td>
                    <?php echo htmlspecialchars($category_ID, ENT_QUOTES) . " - " . htmlspecialchars($category_name, ENT_QUOTES); ?>
                    <!--hymlspecialchars with ENT_QUOTES convert single/double quote'" in the string to HTML entity-->
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td>
                    <?php echo htmlspecialchars($description, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Price</td>
                <td>
                    <?php
                    if ($promotion_price > 0) {
                        echo "<del>RM" . htmlspecialchars($price, ENT_QUOTES) . "</del> -> RM" . htmlspecialchars($promotion_price, ENT_QUOTES);
                    } else {
                        echo "RM" . htmlspecialchars($price, ENT_QUOTES);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td>
                    <?php echo htmlspecialchars($manufacture_date, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td>
                    <?php echo !empty($expired_date) ? htmlspecialchars($expired_date, ENT_QUOTES) : '-'; ?>
                </td>
            </tr>
            <tr>
                <td>Created</td>
                <td>
                    <?php echo htmlspecialchars($created, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    <?php echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>"; ?>
                </td>
            </tr>
        </table>


    </div> <!-- end .container -->

</body>

</html>