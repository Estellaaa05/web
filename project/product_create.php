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
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php

        include 'config/database.php';

        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotionEr = $manufactureEr = $expiredEr = $submitted_category_ID = "";

        if ($_POST) {
            // include database connection
        
            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, category_ID=:category_ID, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query); //con是连接database的钥匙
        
                // posted values
                $name = strip_tags($_POST['name']);
                $submitted_category_ID = $_POST['category_ID'];
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                // bind the parameters
                $stmt->bindParam(':name', $name); //bindParam = 把$name放进:name里面
                $stmt->bindParam(':category_ID', $submitted_category_ID);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promotion_price);
                $stmt->bindParam(':manufacture_date', $manufacture_date);
                $stmt->bindParam(':expired_date', $expired_date);
                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);
                // 前面都是在准备 最后在这里Execute the query
                $flag = true;
                if (empty($name)) {
                    $nameEr = "Please fill in your name.";
                    $flag = false;
                }

                if ($submitted_category_ID == "") {
                    $categoryEr = "Please select a category.";
                    $flag = false;
                }

                if (empty($description)) {
                    $descriptionEr = "Please fill in the description.";
                    $flag = false;
                }

                if (empty($price)) {
                    $priceEr = "Please fill in the price.";
                    $flag = false;
                } else if (!is_numeric($price)) {
                    $priceEr = "Please fill in the price using number.";
                }

                if (empty($promotion_price)) {
                    $promotionEr = "Please fill in the promotion price.";
                    $flag = false;
                } else if (!is_numeric($promotion_price)) {
                    $promotionEr = "Please fill in the promotion price using number.";
                } else if ($price < $promotion_price) {
                    $promotionEr = "Promotion price must be cheaper than original price.";
                    $flag = false;
                }

                if (empty($manufacture_date)) {
                    $manufactureEr = "Please select the manucfacture date.";
                    $flag = false;
                }

                if (empty($expired_date)) {
                    $expiredEr = "Please select the expired date.";
                    $flag = false;
                }

                if (strtotime($expired_date) < strtotime($manufacture_date)) {
                    $expiredEr = "Expired date must be later than manufacture date.";
                    $flag = false;
                }

                if ($flag) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $name = $submitted_category_ID = $description = $price = $promotion_price = $manufacture_date = $expired_date = '';
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

        }

        $select_query = "SELECT category_ID,category_name FROM product_categories";
        $select_stmt = $con->prepare($select_query);
        $select_stmt->execute();

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control'
                            value="<?php echo isset($name) ? $name : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $nameEr; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Category</td>
                    <td>
                        <select class="form-select" name="category_ID" id="category_ID">
                            <option value="">Select Category</option>
                            <?php

                            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

                                extract($row);

                                $selected = ($category_ID == $submitted_category_ID) ? 'selected' : '';
                                echo "<option value='$category_ID' $selected>$category_name</option>";
                            }
                            ?>
                        </select>
                        <div class='text-danger'>
                            <?php echo $categoryEr; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><textarea name='description'
                            class='form-control'><?php echo isset($description) ? $description : ''; ?></textarea>
                        <div class='text-danger'>
                            <?php echo $descriptionEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control'
                            value="<?php echo isset($price) ? $price : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $priceEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price' class='form-control'
                            value="<?php echo isset($promotion_price) ? $promotion_price : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $promotionEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' class='form-control'
                            value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $manufactureEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' class='form-control'
                            value="<?php echo isset($expired_date) ? $expired_date : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $expiredEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
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