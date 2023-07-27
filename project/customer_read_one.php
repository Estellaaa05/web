<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS →-->
</head>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customer</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $username = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record username not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, password, first_name, last_name, gender, date_of_birth, account_status, registration_date_time FROM customers WHERE username = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $username);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $registration_date_time = $row['registration_date_time'];
            $username = $row['username'];
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $date_of_birth = $row['date_of_birth'];
            $account_status = $row['account_status'];
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
                <td>Registration Datetime</td>
                <td>
                    <?php echo htmlspecialchars($registration_date_time, ENT_QUOTES); ?>
                    <!--hymlspecialchars with ENT_QUOTES convert single/double quote'" in the string to HTML entity-->
                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td>
                    <?php echo htmlspecialchars($username, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <?php echo htmlspecialchars($password, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>First Name</td>
                <td>
                    <?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>
                    <?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <?php echo htmlspecialchars($gender, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Date Of Birth</td>
                <td>
                    <?php echo htmlspecialchars($date_of_birth, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td>
                    <?php echo htmlspecialchars($account_status, ENT_QUOTES); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                </td>
            </tr>
        </table>


    </div> <!-- end .container -->

</body>

</html>