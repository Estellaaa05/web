<?php
session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["warning"] = "Please login to proceed.";
    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .container {
            margin-bottom: 6%;
            padding: 0 10px;
        }

        .navbar li a {
            margin-right: 10px;
        }

        .dropdown-menu {
            background-color: rgba(var(--bs-dark-rgb));
        }

        .dropdown-item {
            color: grey;
        }

        .dropdown-item:hover {
            background-color: lightslategray;
        }

        .page-header {
            margin: 15px 0px;
            text-align: center;
            padding: 10px;
        }

        .navbar-nav {
            margin: 10px;
        }

        h2 {
            margin: 0 0 20px 0;
        }

        .info1,
        .info2 {
            flex: 60%;
        }

        .table th,
        .table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .optional {
            font-size: 18px;
            margin: 10px 0;
        }

        .btn-warning {
            margin: 0 0 0 5px;
        }

        .readOneBtn {
            text-align: center;
            margin: 20px 0 35px 0;
        }

        .readOneBtn btn,
        .readOneBtn a {
            margin: 2px;
        }

        .price {
            text-align: right;
        }

        #bigBox {
            display: flex;
            flex-wrap: wrap;
        }

        #box1,
        #box2 {
            flex: 30%;
            margin-bottom: 4%;
        }

        #box1 {
            margin-right: 60px;
        }

        .searchField {
            margin-left: 5%;
        }

        td a {
            margin: 2px;
        }

        .break {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-all;
        }

        .card-header,
        .card-body {
            text-align: center;
        }

        .row {
            margin-bottom: 4%;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Orders
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="orderSummary_read.php">Order Listing</a></li>
                            <li><a class="dropdown-item" href="order_create.php">Create Order</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="product_read.php">Product Listing</a></li>
                            <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="categories_read.php">Category Listing</a></li>
                            <li><a class="dropdown-item" href="categories_create.php">Create Category</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Customers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="customer_read.php">Customer Listing</a></li>
                            <li><a class="dropdown-item" href="customer_create.php">Create Customer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"
                        style="color: red; text-decoration: none; border: 1px solid red; padding: 5px 10px;">Log
                        Out</a>
                </li>
            </ul>
        </div>
    </nav>

</body>

</html>