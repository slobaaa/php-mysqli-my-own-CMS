<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>
<?php include "admin/functions.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php

                if (isset($_GET['category'])) {
                    $post_category_id = $_GET['category'];
                    if (isset(is_admin($_SESSION['username']))) {
                        $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ?");
                    } else {
                        $stmt2 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = 'published'");
                        $published = 'published';
                    }
                    if (iseet($stmt1)) {
                        mysqli_stmt_bind_param($stmt1, "i", $post_category_id); // to i je da je u pitanju integer
                        mysqli_stmt_execute($stmt1);
                        mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                        $stm = $stmt1;
                    } else {
                        mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published); // to is je prvo ide integer pa string
                        mysqli_stmt_execute($stmt2);
                        mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);
                        $stm = $stmt2;
                    }
                    if (mysqli_stmt_num_rows($stmt) === 0) {
                        echo "<p>NO categories available</p>";
                    }
                }
                // $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id";
                // $select_all_posts = mysqli_query($connection, $query);

                while(mysqli_fetch($stmt)) {
                    // $post_id = $row['post_id'];
                    // $post_title = $row['post_title'];
                    // $post_author = $row['post_author'];
                    // $post_date = $row['post_date'];
                    // $post_image = $row['post_image'];
                    // $post_content = substr($row['post_content'], 0,150);
            ?>


            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
            </h2>
            <p class="lead">
                by <a href="index.php"><?php echo $post_author ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date ?></p>
            <hr>
            <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
            <hr>
            <p><?php echo $post_content ?></p>
            <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>






            <?php } ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->



<?php include "includes/footer.php"; ?>    
