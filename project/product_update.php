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

    <title>Update Product</title>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <?php

        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotionEr = $manufactureEr = $expiredEr = "";

        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        include 'config/database.php';

        $select_query = "SELECT category_ID,category_name FROM product_categories";
        $select_stmt = $con->prepare($select_query);
        $select_stmt->execute();

        try {

            $query = "SELECT id, name, category_ID, description, price, promotion_price, manufacture_date, expired_date FROM products WHERE id = ? LIMIT 0,1";

            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $id);

            $stmt->execute();

            // store retrieved row to a variable
        
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
        
            $name = $row['name'];
            $category_ID = $row['category_ID'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        ?>
        <?php
        if ($_POST) {
            try {

                $name = strip_tags($_POST['name']);
                $submitted_category_ID = $_POST['category_ID'];
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];

                $flag = true;
                if (empty($name)) {
                    $nameEr = "Please fill in the name.";
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

                if (!empty($promotion_price)) {
                    if (!is_numeric($promotion_price)) {
                        $promotionEr = "Please fill in the promotion price using number.";
                        $flag = false;
                    } else if ($price < $promotion_price) {
                        $promotionEr = "Promotion price must be cheaper than original price.";
                        $flag = false;
                    }
                } else {
                    $promotion_price = 0;
                }

                if (strtotime($expired_date) < strtotime($manufacture_date)) {
                    $expiredEr = "Expired date must be later than manufacture date.";
                    $flag = false;
                }

                if ($flag) {
                    $query = "UPDATE products SET name=:name, category_ID=:category_ID, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE id = :id";
                    $stmt = $con->prepare($query);

                    $stmt->bindParam(':id', $id);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':category_ID', $submitted_category_ID);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                            class='form-control' />
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
                                $selected = ($category_ID == (isset($submitted_category_ID) ? $submitted_category_ID : $category_ID)) ? 'selected' : '';
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
                            class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea>
                        <div class='text-danger'>
                            <?php echo $descriptionEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>"
                            class='form-control' />
                        <div class='text-danger'>
                            <?php echo $priceEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promotion_price'
                            value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>"
                            class='form-control' />
                        <div class='text-danger'>
                            <?php echo $promotionEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manufacture_date' value="<?php echo $manufacture_date; ?>"
                            class='form-control' />
                        <div class='text-danger'>
                            <?php echo $manufactureEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='expired_date' value="<?php echo $expired_date; ?>"
                            class='form-control' />
                        <div class='text-danger'>
                            <?php echo $expiredEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->

</body>

</html>