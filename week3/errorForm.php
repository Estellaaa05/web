<html>

<head>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>

        <form action="errorForm.php" method="get">
            First Name:<input type="text" name="firstName"><br>
            Last Name:<input type="text" name="lastName"><br>
            <input type="submit">
        </form>

        <?php
        if ($_GET) {
            if (empty($_GET["firstName"]) || empty($_GET["lastName"])) {
                echo '<span class="error">Please enter your name.</span>';
            } else {
                echo ucwords(strtolower($_GET["firstName"] . " "));
                echo ucwords(strtolower($_GET["lastName"]));
            }
        }
        ?>

    </body>

</html>