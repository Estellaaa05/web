<!DOCTYPE HTML>
<html>

<head>
    <style>
        <style>.navbar {
            padding: 10px 35px;
        }

        .page-header {
            margin: 15px 0px;
        }

        .custom-container {
            padding: 0px 80px;
        }

        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            background-color: lightgray;
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

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="orderSummary_read.php" id="navbarDropdownOrders" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Orders
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownOrders">
                            <a class="dropdown-item" href="orderSummary_read.php">Order Listing</a>
                            <a class="dropdown-item" href="order_form.php">Create Order</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="product_read.php" id="navbarDropdownOrders" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Products
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownOrders">
                            <a class="dropdown-item" href="product_read.php">Product Listing</a>
                            <a class="dropdown-item" href="product_create.php">Create Product</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="product_categories.php">Categories</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="customer_read.php" id="navbarDropdownOrders" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Customers
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownOrders">
                            <a class="dropdown-item" href="customer_read.php">Customer Listing</a>
                            <a class="dropdown-item" href="customer_create.php">Create Customer</a>
                        </div>
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