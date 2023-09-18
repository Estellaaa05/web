<!DOCTYPE HTML>
<html>

<head>
    <title>Product Details</title>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Product Details</h1>
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
        <div class='table-responsive table-mobile-responsive'>
            <table class='table table-hover table-bordered'>
                <tr>
                    <th>Name</th>
                    <td>
                        <?php echo htmlspecialchars($name, ENT_QUOTES); ?>
                    </td>
                </tr>
                <tr>
                    <th>Product Image</th>
                    <td>
                        <?php $imageSource = !empty($product_image) ? $product_image :
                            'http://localhost/web/project/img/default_product_photo.jpg';
                        echo "<img src={$imageSource} class='img-thumbnail' width=100px height=100px>"; ?>
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        <?php echo htmlspecialchars($category_ID, ENT_QUOTES) . " - " . htmlspecialchars($category_name, ENT_QUOTES); ?>
                        <!--hymlspecialchars with ENT_QUOTES convert single/double quote'" in the string to HTML entity-->
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <?php echo htmlspecialchars($description, ENT_QUOTES); ?>
                    </td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>
                        <?php
                        if ($promotion_price > 0) {
                            echo "<del>RM" . htmlspecialchars($price, ENT_QUOTES) . "</del> → RM" . htmlspecialchars($promotion_price, ENT_QUOTES);
                        } else {
                            echo "RM" . htmlspecialchars($price, ENT_QUOTES);
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Manufacture Date</th>
                    <td>
                        <?php echo htmlspecialchars($manufacture_date, ENT_QUOTES); ?>
                    </td>
                </tr>
                <tr>
                    <th>Expired Date</th>
                    <td>
                        <?php echo !empty($expired_date) ? htmlspecialchars($expired_date, ENT_QUOTES) : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td>
                        <?php echo htmlspecialchars($created, ENT_QUOTES); ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="readOneBtn">
            <?php
            echo "<a href='product_read.php' class='btn btn-info m-r-1em'>Back to Product Listing</a> ";
            echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a> ";
            echo "<a href='#' onclick='delete_product({$id});' class='btn btn-danger'>Delete</a>"; ?>
        </div>

    </div> <!-- end .container -->

    <script type='text/javascript'>
        // confirm record deletion
        function delete_product(id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>