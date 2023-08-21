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
    <title>Order Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Order Form</h1>
        </div>

        <?php

        include 'config/database.php';

        $customer_IDEr = $customer_ID = $totalProductEr = "";
        $product_IDEr = $quantityEr = array();

        if (isset($_POST['confirm'])) {
            $totalProduct = $_POST["totalProduct"];

            if ($_POST["totalProduct"] < 1) {
                $totalProductEr = "The total product must be more than one.";
            }
        } else {
            $totalProduct = 1;
        }

        if (isset($_POST['save'])) {

            try {
                $summary_query = "INSERT INTO order_summary SET customer_ID=:customer_ID, order_date=:order_date";
                $summary_stmt = $con->prepare($summary_query);
                $customer_ID = $_POST['customer_ID'];
                $summary_stmt->bindParam(':customer_ID', $customer_ID);
                $order_date = date('Y-m-d H:i:s');
                $summary_stmt->bindParam(':order_date', $order_date);

                $flag = true;
                $resultFlag = false;

                if ($customer_ID == "") {
                    $customer_IDEr = "Please select customer.";
                    $flag = false;
                }

                $selectedFlag = false;
                $productID_array = array_unique($_POST["product_ID"]);

                for ($k = 0; $k < count($_POST["product_ID"]); $k++) {
                    $product_IDEr[$k] = "";
                    $quantityEr[$k] = "";

                    if (($_POST["product_ID"][$k] !== "")) {
                        $selectedFlag = true;
                    }

                    if (!$selectedFlag && sizeof($productID_array) < 2) {
                        $product_IDEr[$k] = "Please select at least one product.";
                        $flag = false;
                    }

                    if ($_POST["product_ID"][$k] == "" && $_POST["quantity"][$k] !== "") {
                        $product_IDEr[$k] = "Please select product.";
                        $flag = false;
                        // } else if (sizeof($productID_array) != sizeof($_POST["product_ID"])) {
                        //     $product_IDEr[$k] = "Please select different product.";
                        //     $flag = false;
                    }

                    if ($_POST["quantity"][$k] == "" && $_POST["product_ID"][$k] !== "") {
                        $quantityEr[$k] = "Please fill in the quantity.";
                        $flag = false;
                    } else if ($_POST["quantity"][$k] < 1 && $_POST["product_ID"][$k] !== "") {
                        $quantityEr[$k] = "The quantity must be more than one.";
                        $flag = false;
                    }
                }

                if ($flag) {
                    if ($summary_stmt->execute()) {
                        //retrieves ID of the last inserted row into a database with an auto-increment column
                        $order_ID = $con->lastInsertId();

                        $details_query = "INSERT INTO order_details SET order_ID=:order_ID, product_ID=:product_ID, quantity=:quantity";

                        //$productID_unique = array_unique($_POST["product_ID"]);
        
                        for ($m = 0; $m < count($_POST["product_ID"]); $m++) {
                            $details_stmt = $con->prepare($details_query);
                            $quantity = $_POST["quantity"][$m];
                            $details_stmt->bindParam(':order_ID', $order_ID);
                            $details_stmt->bindParam(':product_ID', $productID_array[$m]);
                            $details_stmt->bindParam(':quantity', $quantity);
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

        $selectProduct_query = "SELECT id,name,price,promotion_price FROM products";
        $selectProduct_stmt = $con->prepare($selectProduct_query);
        $selectProduct_stmt->execute();

        $productID_array = array();
        $productName_array = array();
        $price_array = array();
        $promotion_price_array = array();

        while ($row = $selectProduct_stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($productID_array, $id);
            array_push($productName_array, $name);
            array_push($price_array, $price);
            array_push($promotion_price_array, $promotion_price);
        }

        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <?php
                    $customer_ID = isset($_POST["customer_ID"]) ? $_POST["customer_ID"] : '';
                    ?>
                    <td>Customer</td>
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
                    <td>Total product</td>
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
                    $productID = isset($_POST["product_ID"][$i]) ? $_POST["product_ID"][$i] : '';
                    $quantity = isset($_POST["quantity"][$i]) ? $_POST["quantity"][$i] : '';
                    ?>
                    <tr>
                        <td>Product
                            <?php echo $i + 1 ?>
                        </td>
                        <td>
                            <select class="form-select" name="product_ID[]">
                                <option value="">Select product</option>
                                <?php
                                for ($j = 0; $j < count($productID_array); $j++) {
                                    $selected = (isset($_POST["product_ID"][$i]) && $productID_array[$j] == $_POST["product_ID"][$i]) ? 'selected' : '';
                                    echo "<option value='$productID_array[$j]' $selected>$productName_array[$j] - RM$price_array[$j] (RM$promotion_price_array[$j])</option>";
                                }
                                ?>
                            </select>
                            <div class='text-danger'>
                                <?php if (!empty($product_IDEr) && isset($product_IDEr[$i])) {
                                    echo $product_IDEr[$i];
                                } ?>
                            </div>
                        </td>
                        <td>Quantity</td>
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

                <tr>
                    <td></td>
                    <td colspan=3>
                        <input type='submit' name='save' value='Save' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>