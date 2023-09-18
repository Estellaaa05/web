<!DOCTYPE HTML>

<html>

<head>
    <title>Update Product</title>
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

        $nameEr = $categoryEr = $descriptionEr = $priceEr = $promotionEr = $manufactureEr = $expiredEr = $file_upload_error_messages = "";

        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        include 'config/database.php';

        $select_query = "SELECT category_ID,category_name FROM product_categories";
        $select_stmt = $con->prepare($select_query);
        $select_stmt->execute();

        try {

            $query = "SELECT id, name, product_image, category_ID, description, price, promotion_price, manufacture_date, expired_date, created FROM products WHERE id = ? LIMIT 0,1";

            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $id);

            $stmt->execute();

            // store retrieved row to a variable
        
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
        
            $name = $row['name'];
            $previousImage = $row['product_image'];
            $previous_category_ID = $row['category_ID'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
            $created = $row['created'];
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
                $expired_date = !empty($_POST['expired_date']) ? $_POST['expired_date'] : NULL;

                $product_image = !empty($_FILES["product_image"]["name"])
                    ? sha1_file($_FILES['product_image']['tmp_name']) . "-" . str_replace(" ", "_", basename($_FILES["product_image"]["name"])) : "";
                $product_image = strip_tags($product_image);
                $remove_photo = (isset($_POST['remove_photo']) && ($_POST['remove_photo'] == 1)) ? $_POST['remove_photo'] : "";

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
                    if ($remove_photo) {
                        $target_file = NULL;
                    } else {
                        $target_file = $previousImage;
                    }
                }

                if ($flag) {
                    $submitFlag = true;

                    if ($remove_photo) {
                        if (file_exists($previousImage)) {
                            if (unlink($previousImage)) {
                                //previousImage deleted successfully
                            } else {
                                echo "Failed to delete the previous image.";
                                $submitFlag = false;
                            }
                        }
                    }

                    // if $file_upload_error_messages is still empty
                    if ($product_image) {

                        if (file_exists($previousImage)) {
                            if (unlink($previousImage)) {
                                //previousImage deleted successfully
                            } else {
                                echo "Failed to delete the previous image.";
                                $submitFlag = false;
                            }
                        }

                        if (empty($file_upload_error_messages)) {
                            // it means there are no errors, so try to upload the file
                            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                            } else {
                                $submitFlag = false;
                                $file_upload_error_messages .= "<div>Image upload failed.</div>";
                            }
                        }
                    }

                    if ($submitFlag) {
                        $query = "UPDATE products SET name=:name, category_ID=:category_ID, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, product_image=:product_image WHERE id = :id";
                        $stmt = $con->prepare($query);

                        $stmt->bindParam(':id', $id);
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':category_ID', $submitted_category_ID);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $stmt->bindParam(':product_image', $target_file);

                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was updated.</div>";
                            $previousImage = '';
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post"
            enctype="multipart/form-data">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-bordered'>
                    <tr>
                        <th>Name</th>
                        <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>"
                                class='form-control' />
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
                                    $selected = ($category_ID == (isset($submitted_category_ID) ? $submitted_category_ID : $previous_category_ID)) ? 'selected' : '';
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
                                class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES); ?></textarea>
                            <div class='text-danger'>
                                <?php echo $descriptionEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Price (RM)</th>
                        <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES); ?>"
                                class='form-control' />
                            <div class='text-danger'>
                                <?php echo $priceEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Promotion Price (RM)</th>
                        <td><input type='text' name='promotion_price'
                                value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES); ?>"
                                class='form-control' />
                            <div class='text-danger'>
                                <?php echo $promotionEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Manufacture Date</th>
                        <td><input type='date' name='manufacture_date' value="<?php echo $manufacture_date; ?>"
                                class='form-control' />
                            <div class='text-danger'>
                                <?php echo $manufactureEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Expired Date</th>
                        <td><input type='date' name='expired_date' value="<?php echo $expired_date; ?>"
                                class='form-control' />
                            <div class='text-danger'>
                                <?php echo $expiredEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Product Photo (Optional)</th>
                        <td>
                            <?php
                            $imageSource = !empty($previousImage) ? $previousImage : (!empty($target_file) ? $target_file : '');

                            if (!empty($previousImage) || !empty($target_file)) {
                                echo '<input type="checkbox" name="remove_photo" value="1" id="remove_photo" /> Remove Photo</label>
                        <br>';
                            }

                            $image = (!empty($previousImage) || !empty($target_file)) ? "<img src={$imageSource} class='img-thumbnail' width=100px height=100px/><br>" : '';
                            echo $image;
                            ?>
                            <input type="file" name="product_image" />
                            <div class='text-danger'>
                                <?php echo $file_upload_error_messages; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="readOneBtn">
                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='product_read.php' class='btn btn-danger'>Back to Product Listing</a>
            </div>
        </form>

    </div>
    <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>