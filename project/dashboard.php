<?php
session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["warning"] = "Please login to proceed.";
    header("Location:login_form.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    include 'config/database.php';

    // $customerQuery = "SELECT COUNT(ID) AS totalCustomer FROM customers";
    // $customerQueryStmt = $con->prepare($customerQuery);
    // $customerQueryStmt->execute();
    // $calCustomer = $customerQueryStmt->fetch(PDO::FETCH_ASSOC);
    // $totalCustomer = $calCustomer['totalCustomer'];
    
    // $productQuery = "SELECT COUNT(id) AS totalProduct FROM products";
    // $productQueryStmt = $con->prepare($productQuery);
    // $productQueryStmt->execute();
    // $calProduct = $productQueryStmt->fetch(PDO::FETCH_ASSOC);
    // $totalProduct = $calProduct['totalProduct'];
    
    // $orderQuery = "SELECT COUNT(order_ID) AS totalOrder FROM order_summary";
    // $orderQueryStmt = $con->prepare($orderQuery);
    // $orderQueryStmt->execute();
    // $calOrder = $orderQueryStmt->fetch(PDO::FETCH_ASSOC);
    // $totalOrder = $calOrder['totalOrder'];
    // ?>

    <div class="container">
        <div class="page-header">
            <h1>Welcome!</h1>
        </div>

        <!-- <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <th>Total Number of Customers</th>
                <td>
                    <?php echo $totalCustomer ?>
                </td>
            </tr>

            <tr>
                <th>Total Number of Products</th>
                <td>
                    <?php echo $totalProduct ?>
                </td>
            </tr>

            <tr>
                <th>Total Number of Orders</th>
                <td>
                    <?php echo $totalOrder ?>
                </td>
            </tr>
        </table>

        <tr>
            <h1>Latest Order</h1>
            <table class='table table-hover table-responsive table-bordered'>
                <?php
                $latestOrderQuery = "SELECT order_ID,customer_ID,order_date,total_price FROM order_summary o LEFT JOIN customers c ON o.customer_ID = c.ID ORDER BY order_date DESC LIMIT 1";
                $latestOrderQueryStmt = $con->prepare($latestOrderQuery);
                $latestOrderQueryStmt->execute();
                $orderNum = $latestOrderQueryStmt->rowCount();

                if ($orderNum > 0) {
                    while ($row = $latestOrderQueryStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr>";
                        echo "<th>Order ID</th>";
                        echo "<th>Customer ID</th>";
                        echo "<th>Order Date</th>";
                        echo "<th>Total Price</th>";
                        echo "</tr>";
                        echo "<td>{$order_ID}</td>";
                        echo "<td>{$customer_ID}</td>";
                        echo "<td>{$order_date}</td>";
                        echo "<td>RM{$total_price}</td>";
                    }
                }
                ?>
            </table>


        </tr>
        </table> -->
    </div><!--the end of container-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>