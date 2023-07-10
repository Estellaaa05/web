<?php
function checkError($firstName, $lastName, $m, $d, $y, $username, $password, $confirmPassword, $email)
{
    if (empty($firstName) || empty($lastName)) {
        echo "<span class='error'>Please fill in your name.</span>";

    } else if (!checkdate($m, $d, $y)) {
        echo "<span class='error'>Invalid date of birth.</span>";

    } else if (strlen($username) < 6 || !preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*[a-zA-Z0-9]$/', $username)) {
        echo "<span class='error'>Username must be minimum 6 characters, the first character cannot be number, and only _ or - is allowed in between.</span>";

    } else if (strlen($password) < 8 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/', $password)) {
        echo "<span class='error'>Password must be minimum 8 characters, at least 1 capital letter, 1 small letter, 1 number, and NO symbols like +$()% (@#)  allowed.</span>";

    } else if ($password !== $confirmPassword) {
        echo "<span class='error'>Passwords do not match.</span>";

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span class='error'>Invalid email format.</span>";
    } else {
        echo "Your registration is done.";
    }
}
?>

<html>
<header>
    <style>
        form input,
        form select {
            margin: 5px;
        }

        .error {
            color: red;
        }
    </style>
</header>

<body>
    <h1>Registration Form</h1>
    <form action="ex2.php" method="post">
        First Name:
        <input type="text" name="firstName">
        <br>

        Last Name:
        <input type="text" name="lastName">
        <br>

        Date of Birth:
        <select name="d">
            <?php
            for ($d = 1; $d <= 31; $d++) {
                echo "<option value='$d'>$d</option>";
            }
            ?>
        </select>

        <select name="m">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                echo "<option value='$m'>$m</option>";
            }
            ?>
        </select>

        <select name="y">
            <?php
            for ($y = date('Y'); $y >= 1900; $y--) {
                echo "<option value='$y'>$y</option>";
            }
            ?>
        </select>
        <br>

        Gender:
        <select name="gender">
            <?php
            echo "<option value='f'>Female</option>";
            echo "<option value='m'>Male</option>";
            ?>
        </select>
        <br>

        Username:
        <input type="text" name="username">
        <br>

        Password:
        <input type="password" name="password">
        <br>

        Confirm Password:
        <input type="password" name="confirmPassword">
        <br>

        Email:
        <input type="email" name="email">
        <br>

        <input type="submit">
    </form>

    <?php

    if ($_POST) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $d = $_POST['d'];
        $m = $_POST['m'];
        $y = $_POST['y'];
        $gender = $_POST['gender'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $email = $_POST['email'];

        echo checkError($firstName, $lastName, $m, $d, $y, $username, $password, $confirmPassword, $email);
    }
    ?>

</body>

</html>