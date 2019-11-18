<?php
if (isset($_GET['edit_user'])) {
    $the_user_id = escape($_GET['edit_user']);
    $query="SELECT * FROM users WHERE user_id = $the_user_id";
    $select_users_query = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_users_query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }
}

if (isset($_POST['edit_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];
    // $post_image = $_FILES['image']['name'];
    // $post_image_temp = $_FILES['image']['tmp_name'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    
   if (!empty($user_password)) {
       $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
       $get_user_query = mysqli_query($connection, $query);
       confirmQuery($get_user_query);

       $row = mysqli_fetch_array($get_user_query);
       $db_user_password = $row['user_password'];
   }

   if ($db_user_password != $user_password) {
       $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
   }
   $query = "UPDATE users SET  user_firstname = '{$user_firstname}',
                               user_lastname = '{$user_lastname}',
                               user_role = '{$user_role}',
                               username = '{$username}',
                               user_email = '{$user_email}',
                               user_password = '{$hashed_password}'
                               WHERE user_id = {$the_user_id}";

   $update_user_query = mysqli_query($connection,$query);
   confirmQuery($update_user_query);





    header("Location: users.php");
}

?>
<form action="" method="POST" enctype="multipart/form-data">

    <div class="form-group">
        <label for="firstname">Firstname</label>
        <input type="text" value="<?php echo $user_firstname ?>" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" value="<?php echo $user_lastname ?>" class="form-control" name="user_lastname">
    </div>
    
    <select name="user_role" id="">
        <option value="<?php echo $user_role ?>"><?php echo $user_role ?></option>
        <?php
            if ($user_role == 'admin') {
              echo "<option value='subscriber'>Subscriber</option>";
            } else {
                echo "<option value='admin'>Admin</option>";
            }
        ?>
    </select>
    
    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" value="<?php echo $username ?>" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="post_content">Email</label>
        <input type="email" value="<?php echo $user_email ?>" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="post_content">Password</label>
        <input type="password" value="<?php echo $user_password ?>" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit User">
    </div>
</form>