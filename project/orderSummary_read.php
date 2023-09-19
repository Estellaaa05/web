<!DOCTYPE HTML>
<html>

<head>
    <title>Order Listing</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Order Listing</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='order_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
                <input type="search" name="search" class="form-control form-control-sm searchField"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class='btn btn-warning' value="Search" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config/database.php';
        $query = "SELECT order_ID, os.customer_ID, c.username, c.email, c.first_name, c.last_name, total_price, order_date FROM order_summary os 
        LEFT JOIN customers c ON os.customer_ID = c.ID 
        ORDER BY order_ID DESC";

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($_GET && $action !== 'deleted') {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
            }

            $query = "SELECT order_ID, os.customer_ID, username, email, first_name, last_name, total_price, order_date FROM order_summary os 
            LEFT JOIN customers c ON os.customer_ID = c.ID WHERE order_ID LIKE '%$search%' OR customer_ID LIKE '%$search%' OR username LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR CONCAT(first_name, ' ', last_name) LIKE '%$search%'  OR email LIKE '%$search%' OR total_price LIKE '%$search%' OR order_date LIKE '%$search%' ORDER BY order_ID DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<div class='table-responsive table-mobile-responsive'>";
            echo "<table class='table table-hover table-bordered'>";

            //creating our table heading
            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>Customer ID</th>";
            echo "<th>Name</th>";
            echo "<th>Total Price</th>";
            echo "<th class='d-none d-sm-table-cell'>Order Date</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // a "loop" repeatedly execute block of code when specified condition is true until the condition evaluates to false.
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$order_ID}</td>"; //curly brace:substitute the values of the corresponding variables
                echo "<td>{$customer_ID}</td>";
                echo "<td>{$username} ({$first_name} {$last_name})</td>";
                echo "<td>RM{$total_price}</td>";
                echo "<td class='d-none d-sm-table-cell'>{$order_date}</td>";

                echo "<td>";
                // read one record
                echo "<a href='orderDetails_readOne.php?order_ID={$order_ID}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='order_update.php?order_ID={$order_ID}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_order({$order_ID});'  class='btn btn-danger'>Delete</a>";
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
        function delete_order(order_ID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'order_delete.php?order_ID=' + order_ID;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>