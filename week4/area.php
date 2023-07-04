<?php
function area($r)
{
    return pi() * $r * $r;
}
?>

<html>

<head>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>

    <form action="area.php" method="get">
        Radius:<input type="text" name="r">
        <input type="submit">
    </form>

    <?php
    if ($_GET) {
        $r = $_GET["r"];
        if (!is_numeric($r)) {
            echo '<span class="error">Please fill in a number.</span>';
        } else {
            echo "The area of circle is " . area($r) . ".";
        }
    }
    ?>

</body>

</html>