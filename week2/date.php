<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .dropdownGroup {
            display: flex;
        }

        .dropdownGroup>.dropdown {
            flex: 1;
            text-align: center;
        }

        .dropdown>.dropdown-toggle {
            color: black;
        }

        #day .dropdown-toggle {
            background-color: lightblue;
        }

        #month .dropdown-toggle {
            background-color: yellow;
        }

        #year .dropdown-toggle {
            background-color: red;
        }

        .btn-secondary,
        .btn:hover,
        .btn-check:checked+.btn,
        .btn.active,
        .btn.show,
        .btn:first-child:active,
        :not(.btn-check)+.btn:active {
            color: black;
        }
    </style>

</head>

<body>
    <div class="container mt-3">

        <div class="dropdownGroup">
            <div class="dropdown">
                <div id="day">
                    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Day
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        for ($day = 1; $day <= 31; $day++) {
                            echo "<a class='dropdown-item' href='#'>" . $day . "</a>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="dropdown">
                <div id="month">
                    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Month
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        for ($month = 1; $month <= 12; $month++) {
                            echo "<a class='dropdown-item' href='#'>" . $month . "</a>";
                        }
                        ?>
                    </ul>
                </div>
            </div>


            <div class="dropdown">
                <div id="year">
                    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Year
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        $currentYear = date('Y');
                        for ($year = 1900; $year <= $currentYear; $year++) {
                            echo "<a class='dropdown-item' href='#'>" . $year . "</a>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</body>

</html>