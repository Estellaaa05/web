<!DOCTYPE HTML>
<html>

<head>
    <title>Category Listing</title>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="container">
        <div class="page-header">
            <h1>Category Listing</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='categories_create.php' class='btn btn-primary m-b-1em'>Create New Category</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-flex">
                <input type="search" name="search" class="searchField"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                <input type="submit" class='btn btn-warning' value="Search" />
            </form>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        $query = "SELECT category_ID, category_name, category_description FROM product_categories ORDER BY category_ID DESC";

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }

        if ($action == 'unableDelete') {
            echo "<div class='alert alert-danger'>This category contains product(s), unable to delete record.</div>";
        }

        if ($_GET && $action !== 'unableDelete' && $action !== 'deleted') {
            $search = $_GET['search'];

            if (empty($search)) {
                echo "<div class='alert alert-danger'>Please fill in keywords to search.</div>";
            }

            $query = "SELECT category_ID, category_name, category_description FROM product_categories WHERE category_ID LIKE '%$search%' OR category_name LIKE '%$search%' OR category_description LIKE '%$search%' ORDER BY category_ID DESC";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {
            echo "<div class='table-responsive table-mobile-responsive'>";
            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
        
            //creating our table heading
            echo "<tr>";
            echo "<th>Category ID</th>";
            echo "<th>Category Name</th>";
            echo "<th>Category Description</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
        
            // a "loop" repeatedly execute block of code when specified condition is true until the condition evaluates to false.
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$category_ID}</td>";
                echo "<td>{$category_name}</td>";
                echo "<td>{$category_description}</td>";
                echo "<td>";

                // we will use this links on next part of this post
                echo "<a href='categories_update.php?category_ID={$category_ID}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_category({$category_ID});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
            echo "<div class='table-responsive table-mobile-responsive'>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div> <!-- end .container -->

    <script type='text/javascript'>
        // confirm record deletion
        function delete_category(category_ID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'categories_delete.php?category_ID=' + category_ID;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

</body>

</html>