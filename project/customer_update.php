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

    <title>Update Customer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        td.bg-color {
            background-color: lightgray;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <?php

        $usernameEr = $emailEr = $old_passwordEr = $new_passwordEr = $confirm_passwordEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = "";

        $ID = isset($_GET['ID']) ? $_GET['ID'] : die('ERROR: Record ID not found.');

        include 'config/database.php';

        try {

            $query = "SELECT ID, username, email, password, first_name, last_name, gender, date_of_birth, account_status FROM customers WHERE ID = ? LIMIT 0,1";

            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $ID);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $username = $row['username'];
            $email = $row['email'];
            $password_hash = $row['password'];
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
        <?php
        if ($_POST) {
            try {
                $username = strip_tags($_POST['username']);
                $email = strip_tags($_POST['email']);
                $old_password = isset($_POST['old_password']) ? strip_tags($_POST['old_password']) : '';
                $new_password = isset($_POST['new_password']) ? strip_tags($_POST['new_password']) : '';
                ;
                $confirm_password = isset($_POST['confirm_password']) ? strip_tags($_POST['confirm_password']) : '';
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : '';

                $flag = true;
                if (empty($username)) {
                    $usernameEr = "Please fill in your username.";
                    $flag = false;
                }

                if (empty($email)) {
                    $emailEr = "Please fill in your email.";
                    $flag = false;
                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailEr = "Please fill in a valid email.";
                    $flag = false;
                }

                if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {
                    if (empty($old_password)) {
                        $old_passwordEr = "Please fill in your old password.";
                        $flag = false;
                    } else if (!password_verify($old_password, $password_hash)) {
                        $old_passwordEr = "Old password is incorrect.";
                        $flag = false;
                    }

                    if (empty($new_password)) {
                        $new_passwordEr = "Please fill in your new password.";
                        $flag = false;
                    } else if (strlen($new_password) < 6) {
                        $new_passwordEr = "Password must be at least 6 characters.";
                        $flag = false;
                    } else if ($old_password == $new_password) {
                        $new_passwordEr = "New password cannot be the same as your old password.";
                        $flag = false;
                    }

                    if (empty($confirm_password)) {
                        $confirm_passwordEr = "Please fill in confirm password.";
                        $flag = false;
                    } else if ($new_password !== $confirm_password) {
                        $confirm_passwordEr = "Password does not match.";
                        $flag = false;
                    }
                }

                if (empty($first_name)) {
                    $first_nameEr = "Please fill in your first name.";
                    $flag = false;
                }

                if (empty($last_name)) {
                    $last_nameEr = "Please fill in your last name.";
                    $flag = false;
                }

                if (empty($gender)) {
                    $genderEr = "Please select your gender.";
                    $flag = false;
                }

                if (empty($date_of_birth)) {
                    $date_of_birthEr = "Please select your date of birth.";
                    $flag = false;
                }

                if (empty($account_status)) {
                    $account_statusEr = "Please select your account status.";
                    $flag = false;
                }

                if ($flag) {
                    if (!empty($new_password)) {
                        $query = "UPDATE customers SET username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status WHERE ID = :ID";
                        $stmt = $con->prepare($query);
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt->bindParam(':password', $password_hash);
                    } else {
                        $query = "UPDATE customers SET username=:username, email=:email, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status WHERE ID = :ID";
                        $stmt = $con->prepare($query);
                    }

                    $stmt->bindParam(':ID', $ID);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':date_of_birth', $date_of_birth);
                    $stmt->bindParam(':account_status', $account_status);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                        $old_password = $new_password = '';
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?ID={$ID}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control'
                            value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>" />
                        <div class='text-danger'>
                            <?php echo $usernameEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control'
                            value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" />
                        <div class='text-danger'>
                            <?php echo $emailEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control'
                            value="<?php echo htmlspecialchars($first_name, ENT_QUOTES); ?>" />
                        <div class='text-danger'>
                            <?php echo $first_nameEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' class='form-control'
                            value="<?php echo htmlspecialchars($last_name, ENT_QUOTES); ?>" />
                        <div class='text-danger'>
                            <?php echo $last_nameEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-control">
                            <input type="radio" id="female" name="gender" value="female" <?php echo (isset($gender) && $gender == "female") ? "checked" : ''; ?>>
                            <label for="female">Female</label><br>

                            <input type="radio" id="male" name="gender" value="male" <?php echo (isset($gender) && $gender == "male") ? "checked" : ''; ?>>
                            <label for="male">Male</label>
                        </div>
                        <div class='text-danger'>
                            <?php echo $genderEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control'
                            value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES); ?>" />
                        <div class='text-danger'>
                            <?php echo $date_of_birthEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td>
                        <div class="form-control">
                            <input type="radio" id="active" name="account_status" value="active" <?php echo (isset($account_status) && $account_status == "active") ? "checked" : ''; ?>>
                            <label for="active">Active</label><br>

                            <input type="radio" id="pending" name="account_status" value="pending" <?php echo (isset($account_status) && $account_status == "pending") ? "checked" : ''; ?>>
                            <label for="pending">Pending</label><br>

                            <input type="radio" id="inactive" name="account_status" value="inactive" <?php echo (isset($account_status) && $account_status == "inactive") ? "checked" : ''; ?>>
                            <label for="inactive">Inactive</label>
                        </div>
                        <div class='text-danger'>
                            <?php echo $account_statusEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="bg-color" colspan=2>
                        Change Password (Optional)
                    </td>
                </tr>
                <tr>
                    <td>Old Password</td>
                    <td><input type='password' name='old_password' class='form-control'
                            value="<?php echo isset($old_password) ? $old_password : ''; ?>"></textarea>
                        <div class='text-danger'>
                            <?php echo $old_passwordEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='password' name='new_password' class='form-control'
                            value="<?php echo isset($new_password) ? $new_password : ''; ?>"></textarea>
                        <div class='text-danger'>
                            <?php echo $new_passwordEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td><input type='password' name='confirm_password' class='form-control'></textarea>
                        <div class='text-danger'>
                            <?php echo $confirm_passwordEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->

</body>

</html>