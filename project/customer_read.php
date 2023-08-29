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
    <title>Read Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="custom-container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='customer_create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
                <input type="search" name="search"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class='btn btn-warning' value="Search" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        $query = "SELECT ID, username, email, password, first_name, last_name, account_status, registration_date_time FROM customers ORDER BY ID ASC";

        if ($_GET) {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
            }

            $query = "SELECT ID, username, email, password, first_name, last_name, account_status, registration_date_time FROM customers WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR username LIKE '%$search%' ORDER BY ID ASC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
        
            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>Email</th>";
            //echo "<th>Password</th>";
            echo "<th>Name</th>";
            echo "<th>Account Status</th>";
            echo "<th>Registration Datetime</th>";
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
                echo "<td>{$username}</td>"; //curly brace:substitute the values of the corresponding variables
                echo "<td>{$email}</td>";
                //echo "<td>{$password}</td>";
                echo "<td>{$first_name} {$last_name}</td>";
                echo "<td>{$account_status}</td>";
                echo "<td>{$registration_date_time}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?ID={$ID}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?ID={$ID}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$ID});'  class='btn btn-danger'>Delete</a>";
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