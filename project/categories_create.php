<!DOCTYPE HTML>
<html>

<head>
    <title>Create Product Category</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product Category</h1>
        </div>

        <!-- html form to create product will be here -->
        <!-- PHP insert code will be here -->
        <?php

        $category_nameEr = $category_descriptionEr = "";

        if ($_POST) {
            include 'config/database.php';
            try {

                $category_name = strip_tags($_POST['category_name']);
                $category_description = strip_tags($_POST['category_description']);
                $req_expiredDate = isset($_POST['req_expiredDate']) ? $_POST['req_expiredDate'] : NULL;

                $flag = true;
                if (empty($category_name)) {
                    $category_nameEr = "Please fill in the category name.";
                    $flag = false;
                }

                if (empty($category_description)) {
                    $category_descriptionEr = "Please fill in the category description.";
                    $flag = false;
                }

                if ($flag) {
                    $query = "INSERT INTO product_categories SET category_name=:category_name, category_description=:category_description, req_expiredDate=:req_expiredDate";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':category_name', $category_name);
                    $stmt->bindParam(':category_description', $category_description);
                    $stmt->bindParam(':req_expiredDate', $req_expiredDate);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $category_name = $category_description = $req_expiredDate = $_POST['req_expiredDate'] = '';
                    }

                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <th>Category Name</th>
                        <td><input type='text' name='category_name' class='form-control'
                                value="<?php echo isset($category_name) ? $category_name : ''; ?>" />
                            <div class='text-danger'>
                                <?php echo $category_nameEr; ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Category Description</th>
                        <td><textarea name='category_description'
                                class='form-control'><?php echo isset($category_description) ? $category_description : ''; ?></textarea>
                            <div class='text-danger'>
                                <?php echo $category_descriptionEr; ?>
                            </div>
                            <?php $checked = (isset($_POST['req_expiredDate']) && $_POST['req_expiredDate'] == 'yes') ? 'checked' : ''; ?>
                            <input type="checkbox" name="req_expiredDate" value="yes" id="req_expiredDate" <?php echo $checked ?> />
                            Expired date is required</label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="readOneBtn">
                <input type='submit' value='Save' class='btn btn-primary' />
                <a href='categories_read.php' class='btn btn-danger'>Back to Category Listing</a>
            </div>
        </form>

    </div>  <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>