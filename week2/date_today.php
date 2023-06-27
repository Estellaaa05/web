<!DOCTYPE html>
<html>

<head>
</head>

<body>

    <select name="day">
        <?php
        $currentDay = date('d');

        for ($day = 1; $day <= 31; $day++) {
            $selected = ($day == $currentDay) ? 'selected' : '';
            echo "<option value='$day' $selected>$day</option>";
        }
        ?>
    </select>

    <?php
    $monthNames = array(
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Aug',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dec'
    );

    $currentMonth = date('n');
    ?>

    <select name="month">
        <?php
        foreach ($monthNames as $value => $monthName) {
            $selected = ($value == $currentMonth) ? 'selected' : '';
            echo "<option value='$monthName' $selected>$monthName</option>";
        }
        ?>
    </select>

    <select name="year">
        <?php
        $currentYear = date('Y');
        for ($year = 1900; $year <= $currentYear; $year++) {
            $selected = ($year == $currentYear) ? 'selected' : '';
            echo "<option value='$year' $selected>$year</option>";
        }
        ?>
    </select>

</body>

</html>