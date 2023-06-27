<!DOCTYPE html>
<html>

<head>
    <style>
        #date {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <?php
    echo "<div id=date>";
    echo date("M d, Y (D)");
    echo "</div>";
    echo date("H:i:s");
    ?>

</body>

</html>