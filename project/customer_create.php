<!DOCTYPE HTML>
<html>

<head>
    <title>Create Customer</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>

        <?php

        $usernameEr = $emailEr = $passwordEr = $confirm_passwordEr = $first_nameEr = $last_nameEr = $genderEr = $date_of_birthEr = $account_statusEr = $file_upload_error_messages = "";

        if ($_POST) {
            // include database connection
            include 'config/database.php'; //connect to database
        
            try {
                $username = strip_tags($_POST['username']);
                $email = strip_tags($_POST['email']);
                $password = strip_tags($_POST['password']);
                $confirm_password = $_POST['confirm_password'];
                $first_name = strip_tags(ucwords(strtolower($_POST['first_name'])));
                $last_name = strip_tags(ucwords(strtolower($_POST['last_name'])));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = isset($_POST['account_status']) ? $_POST['account_status'] : '';
                $registration_date_time = date('Y-m-d H:i:s');

                $customer_image = !empty($_FILES["customer_image"]["name"])
                    ? sha1_file($_FILES['customer_image']['tmp_name']) . "-" . str_replace(" ", "_", basename($_FILES["customer_image"]["name"])) : "";
                $customer_image = strip_tags($customer_image);

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

                if (empty($password)) {
                    $passwordEr = "Please fill in your password.";
                    $flag = false;
                } elseif (strlen($password) < 6) {
                    $passwordEr = "Password must be at least 6 characters.";
                    $flag = false;
                }

                if (empty($confirm_password)) {
                    $confirm_passwordEr = "Please fill in confirm password.";
                    $flag = false;
                } else if ($password !== $confirm_password) {
                    $confirm_passwordEr = "Passwords do not match.";
                    $flag = false;
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
                } else if (strtotime($date_of_birth) > strtotime($registration_date_time)) {
                    $date_of_birthEr = "Please select a valid date of birth.";
                    $flag = false;
                }

                if (empty($account_status)) {
                    $account_statusEr = "Please select your account status.";
                    $flag = false;
                }

                //image validation
                if ($customer_image) {
                    // upload to file to folder
                    $target_directory = "customer_uploads/";
                    $target_file = $target_directory . $customer_image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                    if (!in_array($file_type, $allowed_file_types)) {
                        $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        $flag = false;
                    } else {
                        $check = list($width, $height, $type, $attr) = getimagesize($_FILES["customer_image"]["tmp_name"]);
                        if ($check !== false) {
                            // submitted file is an image
                            if ($width !== $height) {
                                $file_upload_error_messages .= "<div>Image is not a square size.</div>";
                                $flag = false;
                            }
                            // make sure submitted file is not too large, can't be larger than 512KB
                            if ($_FILES['customer_image']['size'] > 512 * 1024) {
                                $file_upload_error_messages .= "<div>Image must be less than 512 KB in size.</div>";
                                $flag = false;
                            }

                            if (file_exists($target_file)) {
                                $file_upload_error_messages = "<div>Image already exists. Try to change file name.</div>";
                                $flag = false;
                            }
                        } else {
                            $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                            $flag = false;
                        }
                    }

                    // make sure the 'uploads' folder exists
                    // if not, create it
                    if (!is_dir($target_directory)) {
                        mkdir($target_directory, 0777, true);
                    }
                } else {
                    $target_file = NULL;
                }

                if ($flag) {
                    $submitFlag = true;
                    // if $file_upload_error_messages is still empty
        
                    $query = "INSERT INTO customers SET username=:username, email=:email, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, customer_image=:customer_image, registration_date_time=:registration_date_time";
                    $stmt = $con->prepare($query);

                    //$password_rc = md5($password);
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password_hash);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':date_of_birth', $date_of_birth);
                    $stmt->bindParam(':account_status', $account_status);
                    $stmt->bindParam(':customer_image', $target_file);
                    $stmt->bindParam(':registration_date_time', $registration_date_time);

                    if ($customer_image) {
                        if (empty($file_upload_error_messages)) {
                            // it means there are no errors, so try to upload the file
                            if (move_uploaded_file($_FILES["customer_image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                            } else {
                                $submitFlag = false;
                                $file_upload_error_messages .= "<div>Image upload failed.</div>";
                            }
                        }
                    }

                    if ($submitFlag) {
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                            $username = $email = $first_name = $last_name = $gender = $date_of_birth = $account_status = '';
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <th>Username</th>
                        <td><input type='text' name='username' class='form-control'
                                value="<?php echo isset($username) ? $username : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $usernameEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><input type='text' name='email' class='form-control'
                                value="<?php echo isset($email) ? $email : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $emailEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input type='password' name='password' class='form-control'></textarea>
                            <div class='text-danger'>
                                <?php echo $passwordEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Confirm Password</th>
                        <td><input type='password' name='confirm_password' class='form-control'></textarea>
                            <div class='text-danger'>
                                <?php echo $confirm_passwordEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><input type='text' name='first_name' class='form-control'
                                value="<?php echo isset($first_name) ? $first_name : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $first_nameEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><input type='text' name='last_name' class='form-control'
                                value="<?php echo isset($last_name) ? $last_name : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $last_nameEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Gender</th>
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
                        <th>Date Of Birth</th>
                        <td><input type='date' name='date_of_birth' class='form-control'
                                value="<?php echo isset($date_of_birth) ? $date_of_birth : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $date_of_birthEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Account Status</th>
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
                        <th>Profile Photo (Optional)</th>
                        <td><input type="file" name="customer_image" />
                            <div class='text-danger'>
                                <?php echo $file_upload_error_messages; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="readOneBtn">
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='customer_read.php' class='btn btn-danger'>Back to Customer Listing</a>
            </div>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>