<!DOCTYPE html>
<html>

<head>
    <style>

    </style>
</head>

<body>

    <?php

    for ($row = 1; $row <= 10; $row++) {
        for ($star = 10; $star >= $row; $star--) {
            echo "*";
        }
        echo "<br>";
    }

    ?>

</body>

</html>