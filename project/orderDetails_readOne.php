<?php
session_start();
if (!isset($_SESSION["login"])) {
    $_SESSION["warning"] = "Please login to proceed.";
    header("Location:login_form.php");
    exit;
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .price {
            text-align: right;
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Read Order</h1>
        </div>

        <?php
        $order_ID = isset($_GET['order_ID']) ? $_GET['order_ID'] : die('ERROR: Record ID not found.');

        include 'config/database.php';

        try {
            $IDquery = "SELECT order_ID, order_date, customer_ID, c.username, c.first_name, c.last_name FROM order_summary os
            LEFT JOIN customers c ON c.ID = os.customer_ID 
            WHERE order_ID = ?";

            $query = "SELECT orderDetail_ID, product_ID, name AS product_name, price, promotion_price, quantity, subtotal_price, total_price FROM order_details od 
            LEFT JOIN order_summary os ON od.order_ID = os.order_ID 
            LEFT JOIN products p ON od.product_ID = p.id 
            WHERE od.order_ID = ? ORDER BY orderDetail_ID ASC";

            $IDstmt = $con->prepare($IDquery);
            $IDstmt->bindParam(1, $order_ID);
            $IDstmt->execute();
            $IDnum = $IDstmt->rowCount();

            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $order_ID);
            $stmt->execute();
            $num = $stmt->rowCount();

            if ($IDnum > 0) {

                $IDrow = $IDstmt->fetch(PDO::FETCH_ASSOC);
                extract($IDrow);

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Order ID</th>";
                echo "<td colspan=5>{$order_ID}</td>";
                echo "</tr><tr>";
                echo "<th>Order Date</th>";
                echo "<td colspan=5>{$order_date}</td>";
                echo "</tr><tr>";
                echo "<th>Customer ID</th>";
                echo "<td colspan=5>{$customer_ID}</td>";
                echo "</tr><tr>";
                echo "<th>Customer Name</th>";
                echo "<td colspan=5>{$first_name} {$last_name} ({$username})</td>";
                echo "</tr>";
                echo "</table>";
            }

            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'><tr>";
                echo "<th>Product ID</th>";
                echo "<th>Product Name</th>";
                echo "<th>Single Price (RM)</th>";
                echo "<th>Quantity</th>";
                echo "<th>Total Price (RM)</th></tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    echo "<tr><td>{$product_ID}</td>";
                    echo "<td>{$product_name}</td>";
                    echo "<td>" . (($promotion_price > 0) ? "<del>{$price}</del> -> {$promotion_price}" : "{$price}") . "</td>";
                    echo "<td> x {$quantity}</td>";
                    echo "<td><div class=price>{$subtotal_price}</td></div></tr>";
                }
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <tr>
            <td colspan=4></td>
            <td>
                <?php echo "<div class=price>" . htmlspecialchars($total_price, ENT_QUOTES) . "</div>"; ?>
            </td>
        </tr>
        <tr>
            <td colspan=6>
                <a href='orderSummary_read.php' class='btn btn-danger'>Back to Read Order</a>
            </td>
        </tr>
        </table>
    </div> <!-- end .container -->

</body>

</html>