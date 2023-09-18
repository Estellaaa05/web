<?php
session_start();
if (isset($_SESSION["warning"])) {
    echo "<div class='alert alert-danger'>" . $_SESSION["warning"] . "</div>";
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <head>
        <style>
            .container {
                margin: 90px 55px;
            }

            .loginBtn {
                text-align: center;
                margin: 25px 0 10px 0;
            }
        </style>
    </head>
</head>

<body>
    <div class="container">

        <?php

        $usernameEr = $passwordEr = "";

        if ($_POST) {
            include 'config/database.php';
            try {

                $login_usernameEmail = strip_tags($_POST['usernameEmail']);
                $login_password = strip_tags($_POST['password']);

                $flag = true;
                if (empty($login_usernameEmail)) {
                    $usernameEr = "Please enter Username/Email.";
                    $flag = false;
                }

                if (empty($login_password)) {
                    $passwordEr = "Please enter Password.";
                    $flag = false;
                }

                if ($flag) {
                    $query = "SELECT username,email,password,account_status FROM customers WHERE username = :username OR email = :email";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':username', $login_usernameEmail);
                    $stmt->bindParam(':email', $login_usernameEmail);
                    $stmt->execute();

                    $num = $stmt->rowCount();

                    if ($num > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $account_status = $row['account_status'];
                        $password_hash = $row['password'];

                        if (password_verify($login_password, $password_hash)) {
                            if ($account_status !== "active") {
                                $usernameEr = "This is an inactive account";
                            } else {
                                $_SESSION["login"] = $login_usernameEmail;
                                header("Location:dashboard.php");
                                exit;
                            }
                        } else {
                            $passwordEr = "Password is incorrect.";
                        }
                    } else {
                        $usernameEr = "Username / Email not found.";
                    }
                }

            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card text-white bg-dark mb-3">
                            <h3 class="text-center">Login</h3>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username / Email:</label>
                                <input type="text" class="form-control" id="usernameEmail" name='usernameEmail'
                                    placeholder="Enter your Username or Email"
                                    value="<?php echo isset($login_usernameEmail) ? $login_usernameEmail : ''; ?>">
                                <div class='text-danger'>
                                    <?php echo $usernameEr; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name='password'
                                    placeholder="Enter your password">
                                <div class='text-danger'>
                                    <?php echo $passwordEr; ?>
                                </div>
                            </div>

                            <div class="loginBtn">
                                <td><input type="submit" value="Login" class='btn btn-primary'></td>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>