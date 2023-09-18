<!DOCTYPE HTML>

<html>

<head>
    <title>Update Category</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Update Category</h1>
        </div>
        <?php

        $category_nameEr = $category_descriptionEr = "";

        $category_ID = isset($_GET['category_ID']) ? $_GET['category_ID'] : die('ERROR: Record ID not found.');

        include 'config/database.php';

        try {
            $query = "SELECT category_ID, category_name, category_description, req_expiredDate FROM product_categories WHERE category_ID = ? LIMIT 0,1";

            $stmt = $con->prepare($query);

            $stmt->bindParam(1, $category_ID);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $category_ID = $row['category_ID'];
            $category_name = $row['category_name'];
            $category_description = $row['category_description'];
            $req_expiredDate = $stmt->rowCount() > 0 ? $row['req_expiredDate'] : '';
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {
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
                    $query = "UPDATE product_categories SET category_name=:category_name, category_description=:category_description, req_expiredDate=:req_expiredDate WHERE category_ID = :category_ID";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':category_ID', $category_ID);
                    $stmt->bindParam(':category_name', $category_name);
                    $stmt->bindParam(':category_description', $category_description);
                    $stmt->bindParam(':req_expiredDate', $req_expiredDate);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?category_ID={$category_ID}"); ?>"
            method="post" enctype="multipart/form-data">
            <div class='table-responsive table-mobile-responsive'>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <th>Category Name</th>
                        <td><input type='text' name='category_name'
                                value="<?php echo htmlspecialchars($category_name, ENT_QUOTES); ?>"
                                class='form-control' />
                            <div class='text-danger'>
                                <?php echo $category_nameEr; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Category Description</th>
                        <td>
                            <textarea name='category_description'
                                class='form-control'><?php echo htmlspecialchars($category_description, ENT_QUOTES); ?></textarea>
                            <div class='text-danger'>
                                <?php echo $category_descriptionEr; ?>
                            </div>
                            <?php $checked = (isset($_POST['req_expiredDate']) ? $_POST['req_expiredDate'] == 'yes' : $req_expiredDate == 'yes') ? 'checked' : ''; ?>
                            <input type="checkbox" name="req_expiredDate" value="yes" id="req_expiredDate" <?php echo $checked ?> />
                            Expired date is required</label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="readOneBtn">
                <input type='submit' value='Save Changes' class='btn btn-primary' />
                <a href='categories_read.php' class='btn btn-danger'>Back to Category Listing</a>
            </div>
        </form>

    </div>
    <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>