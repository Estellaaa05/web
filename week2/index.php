<!DOCTYPE html>
<html>

<head>
    <style>
        span {
            color: red;
        }
    </style>
</head>

<body>

    <?php
    echo "My first <span><b>PHP</b></span> script!";

    // date is a function cannot write other code
    echo "<br>";
    echo date("F d, Y (l)");

    echo "<br>";
    date_default_timezone_set("Asia/Kuala_Lumpur");
    echo date("g:ia");
    ?>

</body>

</html>