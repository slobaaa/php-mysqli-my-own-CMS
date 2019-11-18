<?php
    if (isset($_POST['checkBoxArray'])) {
        foreach($_POST['checkBoxArray'] as $postValueId) {
           echo $bulk_options = $_POST['bulk_options'];
           switch($bulk_options) {
                case 'published':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
                    $update_to_published_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_published_status);
                break;
               case 'draft':
                    $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}";
                    $update_to_draft_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_draft_status);
                break;
               case 'delete':
                    $query = "DELETE FROM posts WHERE post_id = {$postValueId}";
                    $update_to_delete_status = mysqli_query($connection, $query);
                    confirmQuery($update_to_delete_status);
                break;
           }
        }
    }
?>

<form action="" method="POST">

    <table class="table table-bordered table-hover">
        <div id="bulkOptionsContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>

        </div>
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllBoxes"></th>
                <th>Id</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Content</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // $query="SELECT * FROM posts";
                //ovo je novo - red ispod - ako hocu samo tog usera da pokazem
                // $user = currentUser();
                // $query = "SELECT * FROM posts WHERE post_user = '$user' ORDER BY post_id DESC";
                $query="SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image,";
                $query.="posts.post_tags, posts.post_content, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title";
                $query.=" FROM posts LEFT JOIN categories ON posts.post_category_id = categories.cat_id";
                $select_posts = mysqli_query($connection, $query);
                while($row = mysqli_fetch_assoc($select_posts)) {
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
                    $category_id = $row['cat_id'];
                    $category_title = $row['cat_title'];
                    echo "<tr>";
                    ?>
                        <td><input class='checkBoxes' type='checkbox' name="checkBoxArray[]" value='<?php echo $post_id; ?>'></td>
                    <?php
                    echo "<td>$post_id</td>";
                    echo "<td>$post_author</td>";
                    echo "<td>$post_title</td>";
                    
                    // $query="SELECT * FROM categories WHERE cat_id = $post_category_id";
                    // $select_categories_id = mysqli_query($connection, $query);
                    // while($row = mysqli_fetch_assoc($select_categories_id)) {
                    //     $cat_id = $row['cat_id'];
                    //     $cat_title = $row['cat_title'];


                        echo "<td>$category_title</td>";
                    // }




                    echo "<td>$post_status</td>";
                    echo "<td><img class='img-responsive' src='../images/$post_image' alt='image' width='100px'></td>";
                    echo "<td>$post_tags</td>";
                    echo "<td>$post_content</td>";
                    $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                    $send_comment_query = mysqli_query($connection, $query);
                    $row = mysqli_fetch_array($send_comment_query);
                    $comment_id = $row['comment_id'];
                    $count_comments = mysqli_num_rows($send_comment_query);
                    echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";
                    echo "<td>$post_date</td>";
                    echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                    echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";

                    ?>

                        <form method="POST">
                            <input type="hidden" name="post_id" value="<?php $post_id; ?>">
                            <?php
                            echo "<td><input class='btn btn-danger' type='submit' name='delete' value='delete'></td>";
                            ?>
                        </form>

                    <?php
                    echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete?');\" href='posts.php?delete={$post_id}'>Delete</a></td>";
                    echo "</tr>";
                }
            ?>

        </tbody>
    </table>
</form>
<?php 
// ovako se brise sa get
if (isset($_GET['delete'])) {
    $the_post_id = $_GET['delete'];
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
    $delete_query = mysqli_query($connection, $query);
    confirmQuery($delete_query);
    header("Location: posts.php");
}

// ovako se brise sa post
if (isset($_POST['delete'])) {
    $the_post_id = escape($_POST['post_id']);
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
    $delete_query = mysqli_query($connection, $query);
    confirmQuery($delete_query);
    header("Location: /cms/admin/posts.php");
}
?>