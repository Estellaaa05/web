<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Listing</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Customer Listing</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='customer_create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
                <input type="search" name="search" class="form-control form-control-sm searchField"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class='btn btn-warning' value="Search" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        $query = "SELECT ID, username, email, password, first_name, last_name, account_status, customer_image, registration_date_time FROM customers ORDER BY ID DESC";

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($action == 'unableDelete') {
            echo "<div class='alert alert-danger'>This customer is in one of the order(s), unable to delete record.</div>";
        }

        if ($_GET && $action !== 'unableDelete' && $action !== 'deleted') {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
            }

            $query = "SELECT ID, username, email, password, first_name, last_name, account_status, customer_image, registration_date_time FROM customers WHERE ID LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%' OR account_status LIKE '%$search%' ORDER BY ID DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<div class='table-responsive table-mobile-responsive'>";
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
        
            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            echo "<th class='d-none d-sm-table-cell'>Name</th>";
            echo "<th>Status</th>";
            echo "<th class='d-none d-sm-table-cell'>Registration Datetime</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
        
            // a "loop" repeatedly execute block of code when specified condition is true until the condition evaluates to false.
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$ID}</td>";
                $imageSource = !empty($customer_image) ? $customer_image : 'http://localhost/web/project/img/default_profile_photo.jpg';
                echo "<td>{$username}<br><img src={$imageSource} class='img-thumbnail' width=100px height=100px></td>"; //curly brace:substitute the values of the corresponding variables
                echo "<td>{$email}</td>";
                echo "<td class='d-none d-sm-table-cell'>{$first_name} {$last_name}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td class='d-none d-sm-table-cell'>{$registration_date_time}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?ID={$ID}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?ID={$ID}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_customer({$ID});'  class='btn btn-danger'>Delete</a>";
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
        function delete_customer(ID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'customer_delete.php?ID=' + ID;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>