<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>

        <?php

        include 'config/database.php';

        $customer_IDEr = $customer_ID = $totalProductEr = "";
        $product_IDEr = $quantityEr = $processedProducts = array();
        $productPrice = $total_price = 0;

        $selectProduct_query = "SELECT id,name,price,promotion_price FROM products";
        $selectProduct_stmt = $con->prepare($selectProduct_query);
        $selectProduct_stmt->execute();

        $productID_array = $productName_array = $price_array = $promotion_price_array = $subtotals = array();

        while ($row = $selectProduct_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($productID_array, $id);
            array_push($productName_array, $name);
            array_push($price_array, $price);
            array_push($promotion_price_array, $promotion_price);
        }

        if (isset($_POST['confirm'])) {
            $totalProduct = $_POST["totalProduct"];

            if ($_POST["totalProduct"] < 1) {
                $totalProductEr = "The total product must be at least one.";
            }
        } else {
            $totalProduct = 1;
        }

        if (isset($_POST['save'])) {

            try {

                $totalProduct = $_POST["totalProduct"];
                $customer_ID = $_POST['customer_ID'];
                $order_date = date('Y-m-d H:i:s');

                $flag = true;
                $resultFlag = false;

                if ($customer_ID == "") {
                    $customer_IDEr = "Please select customer.";
                    $flag = false;
                }

                $selectedFlag = false;
                $productID_unique_array = array_unique($_POST["product_ID"]);

                for ($k = 0; $k < count($_POST["product_ID"]); $k++) {
                    $product_IDEr[$k] = "";
                    $quantityEr[$k] = "";

                    if (($_POST["product_ID"][$k] !== "")) {
                        $selectedFlag = true;
                    }

                    if (!$selectedFlag && sizeof($productID_unique_array) < 2) {
                        $product_IDEr[$k] = "Please select at least one product.";
                        $flag = false;
                    }

                    if ($_POST["product_ID"][$k] == "" && $_POST["quantity"][$k] !== "") {
                        $product_IDEr[$k] = "Please select product.";
                        $flag = false;
                    }

                    if ($_POST["quantity"][$k] == "" && $_POST["product_ID"][$k] !== "") {
                        $quantityEr[$k] = "Please fill in the quantity.";
                        $flag = false;
                    } else if ($_POST["quantity"][$k] < 1 && $_POST["product_ID"][$k] !== "") {
                        $quantityEr[$k] = "The quantity must be at least one.";
                        $flag = false;
                    }
                }

                if ($flag) {
                    for ($e = 0; $e < $totalProduct; $e++) {
                        $selectedProductID = $_POST["product_ID"][$e];
                        $selectedQuantity = $_POST["quantity"][$e];

                        //find $seleted in $processed
                        if (!in_array($selectedProductID, $processedProducts)) {
                            array_push($processedProducts, $selectedProductID);
                            $productIndex = array_search($selectedProductID, $productID_array);
                            if ($productIndex !== false) {
                                $productPrice = $promotion_price_array[$productIndex] > 0 ? $promotion_price_array[$productIndex] : $price_array[$productIndex];
                                $subtotal_price = $selectedQuantity * $productPrice;
                                $subtotals[$e] = $subtotal_price;
                                $total_price += $subtotal_price;
                            }
                        }
                    }

                    $summary_query = "INSERT INTO order_summary SET customer_ID=:customer_ID, total_price=:total_price, order_date=:order_date";
                    $summary_stmt = $con->prepare($summary_query);
                    $summary_stmt->bindParam(':customer_ID', $customer_ID);
                    $summary_stmt->bindParam(':total_price', $total_price);
                    $summary_stmt->bindParam(':order_date', $order_date);

                    if ($summary_stmt->execute()) {
                        $order_ID = $con->lastInsertId();

                        $details_query = "INSERT INTO order_details SET order_ID=:order_ID, product_ID=:product_ID, quantity=:quantity, subtotal_price=:subtotal_price";

                        for ($m = 0; $m < count($_POST["product_ID"]); $m++) {
                            $details_stmt = $con->prepare($details_query);
                            $quantity = $_POST["quantity"][$m];
                            $details_stmt->bindParam(':order_ID', $order_ID);
                            $details_stmt->bindParam(':product_ID', $productID_unique_array[$m]);
                            $details_stmt->bindParam(':quantity', $quantity);
                            $details_stmt->bindParam(':subtotal_price', $subtotals[$m]);
                            $details_stmt->execute();
                            $resultFlag = true;
                        }

                        if ($resultFlag) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                            $_POST["customer_ID"] = '';
                            $_POST["totalProduct"] = 1;
                            for ($m = 0; $m < count($_POST["product_ID"]); $m++) {
                                $_POST["product_ID"][$m] = $_POST["quantity"][$m] = '';
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        $selectCustomer_query = "SELECT ID,username,first_name,last_name FROM customers";
        $selectCustomer_stmt = $con->prepare($selectCustomer_query);
        $selectCustomer_stmt->execute();
        $selectProduct_stmt->execute();

        $productID_array = $productName_array = $price_array = $promotion_price_array = array();

        while ($row = $selectProduct_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($productID_array, $id);
            array_push($productName_array, $name);
            array_push($price_array, $price);
            array_push($promotion_price_array, $promotion_price);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-responsive table-bordered'>

                    <tr>
                        <?php
                        $customer_ID = isset($_POST["customer_ID"]) ? $_POST["customer_ID"] : '';
                        ?>
                        <th>Customer</th>
                        <td colspan=3>
                            <select class="form-select" name="customer_ID" id="customer_ID">
                                <option value="">Select username</option>
                                <?php

                                while ($row = $selectCustomer_stmt->fetch(PDO::FETCH_ASSOC)) {

                                    extract($row);

                                    $selected = ($ID == $customer_ID) ? 'selected' : '';
                                    echo "<option value='$ID' $selected>$username ($first_name $last_name)</option>";
                                }
                                ?>
                            </select>
                            <div class='text-danger'>
                                <?php echo $customer_IDEr; ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Total product</th>
                        <td colspan=2>
                            <input type='number' name='totalProduct' class='form-control'
                                value="<?php echo isset($_POST["totalProduct"]) ? $_POST["totalProduct"] : 1; ?>" />

                            <div class='text-danger'>
                                <?php echo $totalProductEr; ?>
                            </div>
                        </td>
                        <td>
                            <input type='submit' name='confirm' value='Confirm' class='btn btn-info m-r-1em' />
                        </td>
                    </tr>

                    <?php
                    $totalProduct = isset($_POST["totalProduct"]) ? $_POST["totalProduct"] : $totalProduct;

                    for ($i = 0; $i < $totalProduct; $i++) {
                        $product_ID = isset($_POST["product_ID"][$i]) ? $_POST["product_ID"][$i] : '';
                        $quantity = isset($_POST["quantity"][$i]) ? $_POST["quantity"][$i] : '';
                        ?>
                        <tr>
                            <th>Product
                                <?php echo $i + 1 ?>
                            </th>
                            <td>
                                <select class="form-select" name="product_ID[]">
                                    <option value="">Select product</option>
                                    <?php
                                    for ($j = 0; $j < count($productID_array); $j++) {
                                        $selected = (isset($_POST["product_ID"][$i]) && $productID_array[$j] == $_POST["product_ID"][$i]) ? 'selected' : '';

                                        if ($promotion_price_array[$j] > 0) {
                                            echo "<option value='$productID_array[$j]' $selected>$productName_array[$j]  (RM$price_array[$j] → RM$promotion_price_array[$j])</option>";
                                        } else {
                                            echo "<option value='$productID_array[$j]' $selected>$productName_array[$j] (RM$price_array[$j])</option>";
                                        }
                                    }
                                    ?>
                                </select>

                                <div class='text-danger'>
                                    <?php if (!empty($product_IDEr) && isset($product_IDEr[$i])) {
                                        echo $product_IDEr[$i];
                                    } ?>
                                </div>
                            </td>

                            <th>Quantity</th>
                            <td><input type='number' name='quantity[]' class='form-control'
                                    value="<?php echo isset($_POST["quantity"][$i]) ? $_POST["quantity"][$i] : ''; ?>" />
                                <div class='text-danger'>
                                    <?php if (!empty($quantityEr) && isset($quantityEr[$i])) {
                                        echo $quantityEr[$i];
                                    } ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>

            <div class="readOneBtn">
                <input type='submit' name='save' value='Save' class='btn btn-primary' />
                <a href='orderSummary_read.php' class='btn btn-danger'>Back to Order Listing</a>
            </div>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>