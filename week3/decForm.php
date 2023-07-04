<html>

<head>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>

        <form action="decForm.php" method="get">
            Number:<input type="text" name="number"><br>
            <input type="submit">
        </form>

        <?php
        if ($_GET) {
            if (empty($_GET["number"]) || is_numeric($_GET["number"]) == false) {
                echo '<span class="error">Please fill in a number.</span>';
            } else {
                $sum = 0;
                $equation = '';
                for ($x = $_GET["number"]; $x >= 1; $x--) {
                    $sum += $x;
                    $equation .= "$x + ";
                }
                $equation = rtrim($equation, ' + ');
                echo "$equation = $sum";
            }
        }
        ?>

    </body>

</html>