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
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Products</h1>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <a href='product_create.php' class='btn btn-primary m-b-1em'>Create New Product</a>
            <input type="search" name="search" />
            <input type="submit" class='btn btn-info m-r-1em' value="Search" />
        </form>

        <?php
        // include database connection
        include 'config/database.php';
        if ($_GET) {
            $search = $_GET['search'];
            $query = "SELECT id, name, category_ID, description, price, promotion_price, manufacture_date, expired_date, created FROM products WHERE name LIKE '%$search%' ORDER BY id DESC";
        } else {
            $query = "SELECT id, name, category_ID, description, price, promotion_price, manufacture_date, expired_date, created FROM products ORDER BY id DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
        
            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Category ID</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Promotion Price</th>";
            echo "<th>Manufacture Date</th>";
            echo "<th>Expired Date</th>";
            echo "<th>Created</th>";
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
                echo "<td>{$name}</td>";
                echo "<td>{$category_ID}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>{$promotion_price}</td>";
                echo "<td>{$manufacture_date}</td>";
                echo "<td>{$expired_date}</td>";
                echo "<td>{$created}</td>";
                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->

</body>

</html>