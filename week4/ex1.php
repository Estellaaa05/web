<?php
function starSign($month, $day)
{
    if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
        return "Aries";
    } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
        return "Taurus";
    } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
        return "Gemini";
    } else if (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
        return "Cancer";
    } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
        return "Leo";
    } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
        return "Virgo";
    } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
        return "Libra";
    } else if (($month == 10 && $day >= 24) || ($month == 11 && $day <= 21)) {
        return "Scorpius";
    } else if (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) {
        return "Sagittarius";
    } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
        return "Capricornus";
    } else if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
        return "Aquarius";
    } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
        return "Pisces";
    }
}

function chineseZodiac($year)
{
    $remainder = $year % 12;
    if ($remainder == 0) {
        return "Monkey";
    } else if ($remainder == 1) {
        return "Rooster";
    } else if ($remainder == 2) {
        return "Dog";
    } else if ($remainder == 3) {
        return "Pig";
    } else if ($remainder == 4) {
        return "Rat";
    } else if ($remainder == 5) {
        return "Ox";
    } else if ($remainder == 6) {
        return "Tiger";
    } else if ($remainder == 7) {
        return "Rabbit";
    } else if ($remainder == 8) {
        return "Dragon";
    } else if ($remainder == 9) {
        return "Snake";
    } else if ($remainder == 10) {
        return "Horse";
    } else if ($remainder == 11) {
        return "Sheep";
    }
}

?>

<html>

<head>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>

    <form action="ex1.php" method="get">
        First Name:<input type="text" name="firstName"><br>
        Last Name:<input type="text" name="lastName"><br>

        <select name="d">
            <?php
            for ($d = 1; $d <= 31; $d++) {
                echo "<option value='$d'>$d</option>";
            }
            ?>
        </select>

        <select name="m">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                echo "<option value='$m'>$m</option>";
            }
            ?>
        </select>

        <select name="y">
            <?php
            for ($y = date('Y'); $y >= 1900; $y--) {
                echo "<option value='$y'>$y</option>";
            }
            ?>
        </select>

        <input type="submit">
    </form>

    <?php

    if ($_GET) {
        $firstName = $_GET["firstName"];
        $lastName = $_GET["lastName"];
        $day = intval($_GET["d"]);
        $month = intval($_GET["m"]);
        $year = intval($_GET["y"]);

        if (empty($firstName) || empty($lastName)) {
            echo '<span class="error">Please fill in your name.</span>';
        } else if (!checkdate($month, $day, $year)) {
            echo '<span class="error">Invalid date.</span>';
        } else if (date('Y') - $year < 18) {
            echo '<span class="error">You are not over 18 years old.</span>';
        } else {
            echo ucwords(strtolower($_GET["firstName"] . " "));
            echo ucwords(strtolower($_GET["lastName"] . "<br>"));
            echo "Day: " . $day . "<br>Month: " . $month . "<br>Year: " . $year . "<br>";
            echo "Star Sign: " . starSign($month, $day) . "<br>";
            echo "Chinese Zodiac: " . chineseZodiac($year);
        }
    }
    ?>

</body>

</html>