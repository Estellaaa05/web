<!DOCTYPE html>
<html>

<head>
</head>

<body>

    <?php
    $sum = 0;
    $text = '';
    for ($i = 1; $i <= 100; $i++) {
        $sum += $i;
        if ($i % 2 == 0) {
            $text .= "<b>$i</b> + ";
        } else {
            $text .= "$i + ";
        }
    }
    $text = rtrim($text, ' + ');
    echo "$text = <b>$sum</b>";
    ?>

</body>

</html>