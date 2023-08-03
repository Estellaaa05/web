<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>

        <?php
        if ($_POST) {
            include 'config/database.php';
            try {

                $login_username = strip_tags($_POST['username']);
                $login_password = strip_tags($_POST['password']);

                $flag = true;
                if (empty($login_username)) {
                    echo "<div class='alert alert-danger'>Please enter username.</div>";
                    $flag = false;
                }

                if (empty($login_password)) {
                    echo "<div class='alert alert-danger'>Please enter password.</div>";
                    $flag = false;
                }

                if ($flag == true) {
                    $query = "SELECT username,password,account_status FROM customers WHERE username = :username";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':username', $login_username);
                    $stmt->execute();

                    $num = $stmt->rowCount();

                    if ($num > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $account_status = $row['account_status'];
                        $password_hash = $row['password'];

                        if (password_verify($login_password, $password_hash)) {
                            if ($account_status !== "active") {
                                echo "<div class='alert alert-danger'>Inactive account.</div>";
                            } else {
                                header("Location:dashboard.php");
                                exit;
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Incorrect password.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Username not found.</div>";
                    }
                }

            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Login" class='btn btn-primary'></td>
                </tr>
            </table>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>