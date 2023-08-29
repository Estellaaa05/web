<!DOCTYPE HTML>
<html>

<head>
    <style>
        .navbar {
            padding: 10px 35px;
        }

        .page-header {
            margin: 15px 0px;
        }

        .custom-container {
            padding: 0px 80px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: black" ;>
        <div class=" container-fluid">
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav d-flex">

                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="orderSummary_read.php">Orders</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="product_read.php">Products</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="product_categories.php">Categories</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="customer_read.php">Customers</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"
                            style="color: red; text-decoration: none; border: 1px solid red; padding: 5px 10px;">Log
                            Out</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</body>

</html>