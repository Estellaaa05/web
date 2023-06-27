<!DOCTYPE html>
<html>

<head>
    <style>
        .xy {
            display: flex;
            background-color: blue;
        }

        .xy>div {
            background-color: grey;
            margin: 10px;
            padding: 20px;
            /*divide equally*/
            flex: 1;
            text-align: center;
        }

        .big {
            font-size: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php

    $x = rand();
    $y = rand();

    echo "<div class='xy'>";
    if ($x > $y) {
        echo "<div class='big'>";
        echo $x;
        echo "</div>";
        echo "<div>";
        echo $y;
        echo "</div>";
        echo "</div>";
    } else {
        echo "<div>";
        echo $x;
        echo "</div>";
        echo "<div class='big'>";
        echo $y;
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";

    ?>

</body>

</html>