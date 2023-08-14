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

        $customer_ID = $product_ID1 = $quantity1 = $product_ID2 = $quantity2 = $product_ID3 = $quantity3 = "";
        $customer_IDEr = $product_ID1Er = $quantity1Er = $product_ID2Er = $quantity2Er = $product_ID3Er = $quantity3Er = "";

        if ($_POST) {

            try {
                $summary_query = "INSERT INTO order_summary SET customer_ID=:customer_ID, order_date=:order_date";
                $summary_stmt = $con->prepare($summary_query);
                $customer_ID = $_POST['customer_ID'];
                $summary_stmt->bindParam(':customer_ID', $customer_ID);
                $order_date = date('Y-m-d H:i:s');
                $summary_stmt->bindParam(':order_date', $order_date);

                $product_ID1 = $_POST["product_ID1"];
                $quantity1 = $_POST["quantity1"];
                $product_ID2 = $_POST["product_ID2"];
                $quantity2 = $_POST["quantity2"];
                $product_ID3 = $_POST["product_ID3"];
                $quantity3 = $_POST["quantity3"];

                $flag = true;
                $resultFlag = false;

                if ($customer_ID == "") {
                    $customer_IDEr = "Please select customer.";
                    $flag = false;
                }

                if ($product_ID1 == "") {
                    $product_ID1Er = "Please select product1.";
                    $flag = false;
                }

                if (empty($quantity1)) {
                    $quantity1Er = "Please fill in the quantity.";
                    $flag = false;
                } else if (!is_numeric($quantity1)) {
                    $quantity1Er = "Please fill in the quantity using numbers.";
                }

                if ($product_ID2 == "") {
                    $product_ID2Er = "Please select product2.";
                    $flag = false;
                }

                if (empty($quantity2)) {
                    $quantity2Er = "Please fill in the quantity.";
                    $flag = false;
                } else if (!is_numeric($quantity2)) {
                    $quantity2Er = "Please fill in the quantity using numbers.";
                }

                if ($product_ID3 == "") {
                    $product_ID3Er = "Please select product3.";
                    $flag = false;
                }

                if (empty($quantity3)) {
                    $quantity3Er = "Please fill in the quantity.";
                    $flag = false;
                } else if (!is_numeric($quantity3)) {
                    $quantity3Er = "Please fill in the quantity using numbers.";
                }

                if ($flag) {
                    if ($summary_stmt->execute()) {

                        $order_ID = $con->lastInsertId();

                        $details_query = "INSERT INTO order_details SET order_ID=:order_ID, product_ID=:product_ID, quantity=:quantity";

                        for ($i = 1; $i <= 3; $i++) {
                            $details_stmt = $con->prepare($details_query);
                            $product_ID = $_POST["product_ID$i"];
                            $quantity = $_POST["quantity$i"];
                            $details_stmt->bindParam(':order_ID', $order_ID);
                            $details_stmt->bindParam(':product_ID', $product_ID);
                            $details_stmt->bindParam(':quantity', $quantity);
                            $details_stmt->execute();
                            $resultFlag = true;
                        }

                        if ($resultFlag) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                            $customer_ID = $product_ID1 = $quantity1 = $product_ID2 = $quantity2 = $product_ID3 = $quantity3 = '';
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        $selectCustomer_query = "SELECT ID,username FROM customers";
        $selectCustomer_stmt = $con->prepare($selectCustomer_query);
        $selectCustomer_stmt->execute();

        $selectProduct_query = "SELECT id,name FROM products";
        $selectProduct_stmt = $con->prepare($selectProduct_query);
        $selectProduct_stmt->execute();

        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Customer</td>
                    <td>
                        <select class="form-select" name="customer_ID" id="customer_ID">
                            <option value="">Select username</option>
                            <?php

                            while ($row = $selectCustomer_stmt->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $selected = ($ID == $customer_ID) ? 'selected' : '';
                                echo "<option value='$ID' $selected>$username</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $customer_IDEr; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Order1</td>
                    <td>
                        <select class="form-select" name="product_ID1" id="product_ID1">
                            <option value="">Select product</option>
                            <?php

                            while ($row = $selectProduct_stmt->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $selected = ($id == $product_ID1) ? 'selected' : '';
                                echo "<option value='$id' $selected>$name</option>";
                            }
                            $selectProduct_stmt->closeCursor();
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $product_ID1Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Quantity</td>
                    <td><input type='number' name='quantity1' class='form-control'
                            value="<?php echo isset($quantity1) ? $quantity1 : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $quantity1Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Order2</td>
                    <td>
                        <select class="form-select" name="product_ID2" id="product_ID2">
                            <option value="">Select product</option>
                            <?php

                            $selectProduct_stmt2 = $con->prepare($selectProduct_query);
                            $selectProduct_stmt2->execute();

                            while ($row = $selectProduct_stmt2->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $selected = ($id == $product_ID2) ? 'selected' : '';
                                echo "<option value='$id' $selected>$name</option>";
                            }
                            $selectProduct_stmt2->closeCursor();
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $product_ID2Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Quantity</td>
                    <td><input type='number' name='quantity2' class='form-control'
                            value="<?php echo isset($quantity2) ? $quantity2 : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $quantity2Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Order3</td>
                    <td>
                        <select class="form-select" name="product_ID3" id="product_ID3">
                            <option value="">Select product</option>
                            <?php

                            $selectProduct_stmt3 = $con->prepare($selectProduct_query);
                            $selectProduct_stmt3->execute();

                            while ($row = $selectProduct_stmt3->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $selected = ($id == $product_ID3) ? 'selected' : '';
                                echo "<option value='$id' $selected>$name</option>";
                            }
                            $selectProduct_stmt3->closeCursor();
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $product_ID3Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Quantity</td>
                    <td><input type='number' name='quantity3' class='form-control'
                            value="<?php echo isset($quantity3) ? $quantity3 : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $quantity3Er; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
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