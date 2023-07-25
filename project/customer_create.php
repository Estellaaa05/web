<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php'; //connect to database
            try {
                // insert query
                $query = "INSERT INTO customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, registration_date_time=:registration_date_time, account_status=:account_status";
                // prepare query for execution
                $stmt = $con->prepare($query); //con connect database
                // posted values
                $username = strip_tags($_POST['username']);
                $password = strip_tags($_POST['password']);
                $confirm_password = $_POST['confirm_password'];
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : '';
                // bind the parameters
        
                $password = md5($password);
                $stmt->bindParam(':username', $username); //bindParam = put $name into :name
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $date_of_birth);
                $stmt->bindParam(':account_status', $account_status);
                // specify when this record was inserted to the database
                $registration_date_time = date('Y-m-d H:i:s');
                $stmt->bindParam(':registration_date_time', $registration_date_time);

                // Execute the query
                $flag = true;
                if (empty($username)) {
                    echo "<div class='alert alert-danger'>Please fill in your username.</div>";
                    $flag = false;
                }

                if (empty($password)) {
                    echo "<div class='alert alert-danger'>Please fill in your password.</div>";
                    $flag = false;
                }

                if (empty($confirm_password)) {
                    echo "<div class='alert alert-danger'>Please fill in confirm password.</div>";
                    $flag = false;
                } else if ($_POST['password'] !== $confirm_password) {
                    echo "<div class='alert alert-danger'>Passwords do not match.</div>";
                    $flag = false;
                }

                if (empty($first_name)) {
                    echo "<div class='alert alert-danger'>Please fill in your first name.</div>";
                    $flag = false;
                }

                if (empty($last_name)) {
                    echo "<div class='alert alert-danger'>Please fill in your last name.</div>";
                    $flag = false;
                }

                if (empty($gender)) {
                    echo "<div class='alert alert-danger'>Please select your gender.</div>";
                    $flag = false;
                }

                if (empty($date_of_birth)) {
                    echo "<div class='alert alert-danger'>Please select your date of birth.</div>";
                    $flag = false;
                }

                if (empty($account_status)) {
                    echo "<div class='alert alert-danger'>Please select your account status.</div>";
                    $flag = false;
                }


                if ($flag == true) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    }
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-control">
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label><br>
                            <input type="radio" id="male" name="gender" value="male">
                            <label for="male">Male</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <div class="form-control">
                            <input type="radio" id="active" name="account_status" value="active">
                            <label for="active">Active</label><br>
                            <input type="radio" id="pending" name="account_status" value="pending">
                            <label for="pending">Pending</label><br>
                            <input type="radio" id="inactive" name="account_status" value="inactive">
                            <label for="inactive">Inactive</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>