<html>

<head>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>

    <form action="sumForm.php" method="get">
        First Name:<input type="text" name="firstName"><br>
        Last Name:<input type="text" name="lastName"><br>
        <input type="submit">
    </form>

    <?php
    if ($_GET) {
        if (empty($_GET["firstName"]) || empty($_GET["lastName"]) || is_numeric($_GET["firstName"]) == false || is_numeric($_GET["lastName"]) == false) {
            echo '<span class="error">Please fill in a number.</span>';
        } else {
            $sum = $_GET["firstName"] + $_GET["lastName"];
            echo ($sum);
        }
    }
    ?>

</body>

</html>