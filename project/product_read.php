<!DOCTYPE HTML>
<html>

<head>
    <title>Product Listing</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Product Listing</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='product_create.php' class='btn btn-primary m-b-1em'>Create New Product</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
                <input type=" search" name="search" class="form-control form-control-sm searchField"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class='btn btn-warning' value="Search" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config/database.php';
        $query = "SELECT id, name, product_image, p.category_ID, c.category_name, price, promotion_price, product_image, created FROM products p
        LEFT JOIN product_categories c ON p.category_ID = c.category_ID ORDER BY id DESC";

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($action == 'unableDelete') {
            echo "<div class='alert alert-danger'>This product is in one of the order(s), unable to delete record.</div>";
        }

        if ($_GET && $action !== 'unableDelete' && $action !== 'deleted') {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
            }

            $query = "SELECT id, name, product_image, p.category_ID, c.category_name, price, promotion_price, product_image, created FROM products p
            LEFT JOIN product_categories c ON p.category_ID = c.category_ID 
            WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR p.category_ID LIKE '%$search%' OR c.category_name LIKE '%$search%' OR p.price LIKE '%$search%' OR p.promotion_price LIKE '%$search%' OR p.created LIKE '%$search%' ORDER BY id DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<div class='table-responsive table-mobile-responsive'>";
            echo "<table class='table table-responsive table-bordered'>"; //start table
        
            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category</th>";
            echo "<th>Single Price</th>";
            echo "<th class='d-none d-sm-table-cell'>Created</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // a "loop" repeatedly execute block of code when specified condition is true until the condition evaluates to false.
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$id}</td>"; //curly brace:substitute the values of the corresponding variables
                $imageSource = !empty($product_image) ? $product_image : 'http://localhost/web/project/img/default_product_photo.jpg';
                echo "<td>{$name}<br><img src={$imageSource} class='img-thumbnail' width=100px height=100px></td>";
                echo "<td>{$category_ID} - {$category_name}</td>";

                if ($promotion_price > 0) {
                    echo "<td><del>RM{$price}</del> → RM{$promotion_price}</td>";
                } else {
                    echo "<td>RM{$price}</td>";
                }

                echo "<td class='d-none d-sm-table-cell'>{$created}</td>";

                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_product({$id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            // end table
            echo "</table>";
            echo "</div>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

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