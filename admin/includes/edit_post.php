<?php 
if (isset($_GET['p_id'])) {
    $the_post_id = $_GET['p_id'];
}

$query="SELECT * FROM posts WHERE post_id = $the_post_id;";
$select_posts_by_id = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($select_posts_by_id)) {
    $post_id = $row['post_id'];
    $post_author = $row['post_author'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_tags = $row['post_tags'];
    $post_content = $row['post_content'];
    $post_comment_count = $row['post_comment_count'];
    $post_date = $row['post_date'];
}
if (isset($_POST['update_post'])) {
    $post_title = $_POST['post_title'];
    $post_author = $_POST['post_author'];
    $post_category_id = $_POST['post_category_id'];
    $post_status = $_POST['post_status'];
    $post_image = $_FILES['post_image']['name'];
    $post_image_temp = $_FILES['post_image']['tmp_name'];
    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    move_uploaded_file($post_image_temp, "images/$post_image");

    if (empty($post_image)) {
        $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
        $select_image = mysqli_query($connection, $query);
        while($row = mysqli_fetch_array($select_image)) {
            $post_image = $row['post_image'];
        }
    }

    $query = "UPDATE posts SET  post_title = '{$post_title}',
                                post_category_id = '{$post_category_id}',
                                post_date = now(),
                                post_author = '{$post_author}',
                                post_status = '{$post_status}',
                                post_tags = '{$post_tags}',
                                post_content = '{$post_content}',
                                post_image = '{$post_image}'
                                 WHERE post_id = {$the_post_id}";

    $update_post = mysqli_query($connection,$query);
    confirmQuery($update_post);
    echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'>View Post </a> or <a href='posts.php'>Edit More Posts</a></p>";
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->
    <div class="form-group">
        <label for="post_title">Post Title</label>
        <input type="text" value="<?php echo $post_title; ?>" class="form-control" name="post_title">
    </div>
    <div class="form-group">
        <select name="post_category_id" id="">
            <?php 
            // $query="SELECT * FROM categories WHERE cat_id = $cat_id";
            $query="SELECT * FROM categories";
            $select_categories = mysqli_query($connection, $query);
            confirmQuery($select_categories);
            while($row = mysqli_fetch_assoc($select_categories)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<option value='{$cat_id}'>$cat_title</option>";
            }
        ?>

        </select>
    </div>
    <div class="form-group">
        <label for="post_author">Post Author</label>
        <input type="text" value="<?php echo $post_author; ?>" class="form-control" name="post_author">
    </div>
    <div class="form-group">
        <select name="post_status">
            <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
            <?php
                if ($post_status == 'published') {
                    echo "<option value='draft'>Draft</option>";
                } else {
                    echo "<option value='published'>Publish</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <img src="../images/<?php echo $post_image; ?>" alt="image" width="100px">
    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="post_image">
    </div>
    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea id="body" type="text" class="form-control" name="post_content" cols="30" rows="10">
        <?php echo $post_content; ?>
        </textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="update_post" value="Edit Post">
    </div>
</form>