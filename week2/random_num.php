<!DOCTYPE html>
<html>

<head>
    <style>
        #first {
            color: green;
        }

        #second {
            color: blue;
        }

        #sum {
            color: red;
        }

        #mul {
            color: black;
        }
    </style>
</head>

<body>

    <?php
    $num1 = rand(100, 200);
    $num2 = rand(100, 200);
    $sum = $num1 + $num2;
    $mul = $num1 * $num2;

    echo "<div id=first><i>$num1</i></div>";
    echo "<div id=second><i>$num2</i></div>";
    echo "<div id=sum><b>$sum</b></div>";
    echo "<div id=mul><b><i>$mul</b></i></div>";
    ?>

</body>

</html>