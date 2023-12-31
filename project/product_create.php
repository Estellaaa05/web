<!DOCTYPE HTML>
<html>

<head>
    <title>Create Product</title>
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

        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotionEr = $manufactureEr = $expiredEr = $submitted_category_ID = $file_upload_error_messages = "";

        $select_query = "SELECT category_ID,category_name FROM product_categories";
        $select_stmt = $con->prepare($select_query);
        $select_stmt->execute();

        if ($_POST) {
            // include database connection
        
            try {
                // posted values
                $name = strip_tags($_POST['name']);
                $submitted_category_ID = $_POST['category_ID'];
                $description = strip_tags($_POST['description']);
                $price = strip_tags($_POST['price']);
                $promotion_price = strip_tags($_POST['promotion_price']);
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = !empty($_POST['expired_date']) ? $_POST['expired_date'] : NULL;
                $created = date('Y-m-d H:i:s');

                $product_image = !empty($_FILES["product_image"]["name"])
                    ? sha1_file($_FILES['product_image']['tmp_name']) . "-" . str_replace(" ", "_", basename($_FILES["product_image"]["name"])) : "";
                $product_image = strip_tags($product_image);

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

                if (empty($manufacture_date)) {
                    $manufactureEr = "Please select the manucfacture date.";
                    $flag = false;
                } else if (strtotime($manufacture_date) > strtotime($created)) {
                    $manufactureEr = "Please select a valid manufacture date.";
                    $flag = false;
                }

                $cat_query = "SELECT category_ID,req_expiredDate FROM product_categories WHERE category_ID = ?";
                $cat_stmt = $con->prepare($cat_query);
                $cat_stmt->bindParam(1, $submitted_category_ID);
                $cat_stmt->execute();

                if ($cat_stmt->rowCount() > 0) {
                    $category_row = $cat_stmt->fetch(PDO::FETCH_ASSOC);
                    $req_expiredDate = $category_row['req_expiredDate'];
                } else {
                    $req_expiredDate = '';
                }

                if ($req_expiredDate == 'yes') {
                    if (empty($expired_date)) {
                        $expiredEr = "Please select the expired date.";
                        $flag = false;
                    }
                }

                if (!empty($expired_date)) {
                    if (strtotime($expired_date) < strtotime($manufacture_date)) {
                        $expiredEr = "Expired date must be later than manufacture date.";
                        $flag = false;
                    }
                }

                //image validation
                if ($product_image) {
                    // upload to file to folder
                    $target_directory = "product_uploads/";
                    $target_file = $target_directory . $product_image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                    if (!in_array($file_type, $allowed_file_types)) {
                        $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        $flag = false;
                    } else {
                        $check = list($width, $height, $type, $attr) = getimagesize($_FILES["product_image"]["tmp_name"]);
                        if ($check !== false) {
                            // submitted file is an image
                            if ($width !== $height) {
                                $file_upload_error_messages .= "<div>Image is not a square size.</div>";
                                $flag = false;
                            }
                            // make sure submitted file is not too large, can't be larger than 512KB
                            if ($_FILES['product_image']['size'] > 512 * 1024) {
                                $file_upload_error_messages .= "<div>Image must be less than 512 KB in size.</div>";
                                $flag = false;
                            }

                            if (file_exists($target_file)) {
                                $file_upload_error_messages = "<div>Image already exists. Try to change file name.</div>";
                                $flag = false;
                            }
                        } else {
                            $file_upload_error_messages .= "<div>Submitted file is not an image.</div>";
                            $flag = false;
                        }
                    }

                    // make sure the 'uploads' folder exists
                    // if not, create it
                    if (!is_dir($target_directory)) {
                        mkdir($target_directory, 0777, true);
                    }
                } else {
                    $target_file = NULL;
                }

                if ($flag) {
                    $submitFlag = true;
                    $query = "INSERT INTO products SET name=:name, category_ID=:category_ID, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, product_image=:product_image, created=:created";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':category_ID', $submitted_category_ID);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);
                    $stmt->bindParam(':product_image', $target_file);
                    $stmt->bindParam(':created', $created);

                    if ($product_image) {
                        if (empty($file_upload_error_messages)) {
                            // it means there are no errors, so try to upload the file
                            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                            } else {
                                $file_upload_error_messages .= "<div>Image upload failed.</div>";
                                $submitFlag = false;
                            }
                        }
                    }

                    if ($submitFlag) {
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                            $name = $submitted_category_ID = $description = $price = $promotion_price = $manufacture_date = $expired_date = '';
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <th>Name</th>
                        <td><input type='text' name='name' class='form-control'
                                value="<?php echo isset($name) ? $name : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $nameEr; ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Category</th>
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
                        <th>Description</th>
                        <td><textarea name='description'
                                class='form-control'><?php echo isset($description) ? $description : ''; ?></textarea>
                            <div class='text-danger'>
                                <?php echo $descriptionEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Price (RM)</th>
                        <td><input type='text' name='price' class='form-control'
                                value="<?php echo isset($price) ? $price : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $priceEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Promotion Price (RM)</th>
                        <td><input type='text' name='promotion_price' class='form-control'
                                value="<?php echo isset($promotion_price) ? $promotion_price : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $promotionEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Manufacture Date</th>
                        <td><input type='date' name='manufacture_date' class='form-control'
                                value="<?php echo isset($manufacture_date) ? $manufacture_date : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $manufactureEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Expired Date</th>
                        <td><input type='date' name='expired_date' class='form-control'
                                value="<?php echo isset($expired_date) ? $expired_date : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $expiredEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Product Photo (Optional)</th>
                        <td><input type="file" name="product_image" />
                            <div class='text-danger'>
                                <?php echo $file_upload_error_messages; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="readOneBtn">
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='product_read.php' class='btn btn-danger'>Back to Product Listing</a>
            </div>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>