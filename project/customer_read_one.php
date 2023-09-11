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
    <title>Customer Details</title>
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
            <h1>Customer Details</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $ID = isset($_GET['ID']) ? $_GET['ID'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT ID,username, email, first_name, last_name, gender, date_of_birth, account_status, customer_image, registration_date_time FROM customers WHERE ID = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $ID);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $ID = $row['ID'];
            $username = $row['username'];
            $email = $row['email'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $account_status = $row['account_status'];
            $customer_image = $row['customer_image'];
            $registration_date_time = $row['registration_date_time'];
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
                <th>ID</th>
                <td>
                    <?php echo htmlspecialchars($ID, ENT_QUOTES); ?>
                    <!--hymlspecialchars with ENT_QUOTES convert single/double quote'" in the string to HTML entity-->
                </td>
            </tr>
            <tr>
                <th>Username</th>
                <td>
                    <?php echo htmlspecialchars($username, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <th>Profile Image</th>
                <td>
                    <?php $imageSource = !empty($customer_image) ? $customer_image :
                        'http://localhost/web/project/img/default_profile_photo.jpg';
                    echo "<img src={$imageSource} width=100px height=100px>"; ?>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <?php echo htmlspecialchars($email, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <td>
                    <?php echo htmlspecialchars($first_name, ENT_QUOTES) . " " . htmlspecialchars($last_name, ENT_QUOTES);
                    ; ?>
                </td>
            </tr>
            <tr>
                <th>Gender</th>
                <td>
                    <?php echo htmlspecialchars($gender, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <th>Date Of Birth</th>
                <td>
                    <?php echo htmlspecialchars($date_of_birth, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <th>Account Status</th>
                <td>
                    <?php echo htmlspecialchars($account_status, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <th>Registration Datetime</th>
                <td>
                    <?php echo htmlspecialchars($registration_date_time, ENT_QUOTES); ?>
                    <!--hymlspecialchars with ENT_QUOTES convert single/double quote'" in the string to HTML entity-->
                </td>
            </tr>
        </table>
        <div class="readOneBtn">
            <?php echo "<a href='customer_read.php' class='btn btn-info m-r-1em'>Back to Customer Listing</a> ";
            echo "<a href='customer_update.php?ID={$ID}' class='btn btn-primary m-r-1em'>Edit</a> ";
            echo "<a href='#' onclick='delete_customer({$ID});'  class='btn btn-danger'>Delete</a>"; ?>
        </div>
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

</body>

</html>