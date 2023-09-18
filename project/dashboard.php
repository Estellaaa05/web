<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <?php
    include 'navbar.php';
    include 'config/database.php';

    $customerQuery = "SELECT COUNT(ID) AS totalCustomer FROM customers";
    $customerQueryStmt = $con->prepare($customerQuery);
    $customerQueryStmt->execute();
    $calCustomer = $customerQueryStmt->fetch(PDO::FETCH_ASSOC);
    $totalCustomer = $calCustomer['totalCustomer'];

    $productQuery = "SELECT COUNT(id) AS totalProduct FROM products";
    $productQueryStmt = $con->prepare($productQuery);
    $productQueryStmt->execute();
    $calProduct = $productQueryStmt->fetch(PDO::FETCH_ASSOC);
    $totalProduct = $calProduct['totalProduct'];

    $orderQuery = "SELECT COUNT(order_ID) AS totalOrder, SUM(total_price) AS totalPrice FROM order_summary";
    $orderQueryStmt = $con->prepare($orderQuery);
    $orderQueryStmt->execute();
    $calOrder = $orderQueryStmt->fetch(PDO::FETCH_ASSOC);
    $totalOrder = $calOrder['totalOrder'];
    $totalPrice = $calOrder['totalPrice'];
    ?>

    <div class="container">

        <div class="page-header">
            <h1>Welcome!</h1>
        </div>

        <h2>Summary</h2>
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <div class="col">
                <div class="card text-dark bg-light mb-3" style="max-width: 15rem;">
                    <div class="card-header">Total Customers</div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $totalCustomer ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-dark bg-light mb-3" style="max-width: 15rem;">
                    <div class="card-header">Total Products</div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $totalProduct ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-dark bg-light mb-3" style="max-width: 15rem;">
                    <div class="card-header">Total Orders</div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $totalOrder ?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-dark bg-light mb-3" style="max-width: 15rem;">
                    <div class="card-header">Total Sales</div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo "RM " . $totalPrice ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div><!--the end of bigBox-->

        <div id="bigBox">
            <div id="box1">
                <h2>Most Popular Products</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $popularProductQuery = "SELECT od.product_ID, SUM(od.quantity) AS total, p.name, p.price, p.promotion_price FROM order_details od LEFT JOIN products p ON od.product_ID = p.id GROUP BY od.product_ID ORDER BY total DESC LIMIT 5";
                    $popularProductQueryStmt = $con->prepare($popularProductQuery);
                    $popularProductQueryStmt->execute();
                    $mostProductNum = $popularProductQueryStmt->rowCount();

                    if ($mostProductNum > 0) {
                        echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product</th>";
                        echo "<th>Purchased Quantity</th>";
                        echo "<th>Single Price</th>";
                        echo "</tr>";
                        while ($row = $popularProductQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$product_ID}</td>";
                            echo "<td>{$name}</td>";
                            echo "<td>{$total}</td>";
                            if ($promotion_price > 0) {
                                echo "<td><del>RM{$price}</del> → RM{$promotion_price}</td>";
                            } else {
                                echo "<td>RM{$price}</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box1-->

            <div id="box2">
                <h2>Least Popular Products</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $leastProductQuery = "SELECT od.product_ID, p.id, SUM(od.quantity) AS total, p.name, p.price, p.promotion_price FROM order_details od RIGHT JOIN products p ON od.product_ID = p.id GROUP BY p.id ORDER BY total ASC LIMIT 5";
                    $leastProductQueryStmt = $con->prepare($leastProductQuery);
                    $leastProductQueryStmt->execute();
                    $leastProductNum = $leastProductQueryStmt->rowCount();

                    if ($leastProductNum > 0) {
                        echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product</th>";
                        echo "<th>Purchased Quantity</th>";
                        echo "<th>Single Price</th>";
                        echo "</tr>";
                        while ($row = $leastProductQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$id}</td>";
                            echo "<td>{$name}</td>";

                            if ($total > 0) {
                                echo "<td>{$total}</td>";
                            } else {
                                echo "<td>0</td>";
                            }

                            if ($promotion_price > 0) {
                                echo "<td><del>RM{$price}</del> → RM{$promotion_price}</td>";
                            } else {
                                echo "<td>RM{$price}</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box2-->
        </div><!--the end of bigBox-->

        <div id="bigBox">
            <div id="box1">
                <h2>Most Valuable Products</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $valuableProductQuery = "SELECT p.id, p.name, p.category_ID, c.category_name, p.price, p.promotion_price FROM products p
                    LEFT JOIN product_categories c ON p.category_ID = c.category_ID ORDER BY CASE WHEN promotion_price > 0 THEN promotion_price ELSE price END DESC LIMIT 5";
                    $valuableProductQueryStmt = $con->prepare($valuableProductQuery);
                    $valuableProductQueryStmt->execute();
                    $valuableProductNum = $valuableProductQueryStmt->rowCount();

                    if ($valuableProductNum > 0) {
                        echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product</th>";
                        echo "<th>Category</th>";
                        echo "<th>Single Price</th>";
                        echo "</tr>";
                        while ($row = $valuableProductQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$id}</td>";
                            echo "<td>{$name}</td>";
                            echo "<td>{$category_ID} - {$category_name}</td>";
                            if ($promotion_price > 0) {
                                echo "<td><del>RM{$price}</del> → RM{$promotion_price}</td>";
                            } else {
                                echo "<td>RM{$price}</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box1-->

            <div id="box2">
                <h2>Most Priceless Products</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $valuableProductQuery = "SELECT p.id, p.name, p.category_ID, c.category_name, p.price, p.promotion_price FROM products p
                    LEFT JOIN product_categories c ON p.category_ID = c.category_ID ORDER BY CASE WHEN promotion_price > 0 THEN promotion_price ELSE price END ASC LIMIT 5";
                    $valuableProductQueryStmt = $con->prepare($valuableProductQuery);
                    $valuableProductQueryStmt->execute();
                    $valuableProductNum = $valuableProductQueryStmt->rowCount();

                    if ($valuableProductNum > 0) {
                        echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product</th>";
                        echo "<th>Category</th>";
                        echo "<th>Single Price</th>";
                        echo "</tr>";
                        while ($row = $valuableProductQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$id}</td>";
                            echo "<td>{$name}</td>";
                            echo "<td>{$category_ID} - {$category_name}</td>";
                            if ($promotion_price > 0) {
                                echo "<td><del>RM{$price}</del> → RM{$promotion_price}</td>";
                            } else {
                                echo "<td>RM{$price}</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box2-->
        </div><!--the end of bigBox-->

        <div id="bigBox">
            <div id="box1">
                <h2>Most Supportive Customers</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $customerQuery = "SELECT COUNT(customer_ID) AS customer, id,username, first_name, last_name FROM order_summary os LEFT JOIN customers c ON os.customer_ID = c.ID GROUP BY customer_ID ORDER BY customer DESC LIMIT 3";
                    $customerQueryStmt = $con->prepare($customerQuery);
                    $customerQueryStmt->execute();
                    $customerNum = $customerQueryStmt->rowCount();

                    if ($customerNum > 0) {
                        echo "<tr>";
                        echo "<th>Customer ID</th>";
                        echo "<th>Username</th>";
                        echo "<th>Name</th>";
                        echo "</tr>";
                        while ($row = $customerQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$id}</td>";
                            echo "<td>{$username}</td>";
                            echo "<td>{$first_name} {$last_name}</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box1-->
            <div id="box2">
                <h2>The Lastest Order</h2>
                <table class='table table-responsive table-bordered infoTable'>
                    <?php
                    $latestOrderQuery = "SELECT order_ID,customer_ID, username, first_name, last_name, order_date,total_price FROM order_summary o LEFT JOIN customers c ON o.customer_ID = c.ID ORDER BY order_date DESC LIMIT 1";
                    $latestOrderQueryStmt = $con->prepare($latestOrderQuery);
                    $latestOrderQueryStmt->execute();
                    $orderNum = $latestOrderQueryStmt->rowCount();

                    if ($orderNum > 0) {
                        echo "<tr>";
                        echo "<th>Order ID</th>";
                        echo "<th>Customer</th>";
                        echo "<th>Order Date</th>";
                        echo "<th>Total Price</th>";
                        echo "</tr>";
                        while ($row = $latestOrderQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr>";
                            echo "<td>{$order_ID}</td>";
                            echo "<td>{$customer_ID} - {$first_name} {$last_name} ({$username})</td>";
                            echo "<td>{$order_date}</td>";
                            echo "<td>RM {$total_price}</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>
            </div><!--the end of box2-->
        </div><!--the end of bigBox-->

    </div><!--the end of container-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>