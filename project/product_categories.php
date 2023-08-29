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
    <title>Create Product Categories</title>
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
            <h1>Create Product Categories</h1>
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
                    $query = "INSERT INTO product_categories SET category_name=:category_name, category_description=:category_description";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':category_name', $category_name);
                    $stmt->bindParam(':category_description', $category_description);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                        $category_name = $category_description = '';
                    }

                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='category_name' class='form-control'
                            value="<?php echo isset($category_name) ? $category_name : ''; ?>" />
                        <div class='text-danger'>
                            <?php echo $category_nameEr; ?>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Category Description</td>
                    <td><textarea name='category_description'
                            class='form-control'><?php echo isset($category_description) ? $category_description : ''; ?></textarea>
                        <div class='text-danger'>
                            <?php echo $category_descriptionEr; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_create.php' class='btn btn-danger'>Create New Product</a>
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