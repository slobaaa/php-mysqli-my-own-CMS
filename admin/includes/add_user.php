
<?php
if (isset($_POST['create_user'])) {
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];

    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    $password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10)); // ovaj bcrypt je blowfish

    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) 
            VALUES('{$user_firstname}','{$user_lastname}','{$user_role}','{$username}','{$user_email}','{$password}')";
    $create_user_query = mysqli_query($connection, $query);

    confirmQuery($create_user_query);

    echo "User Created" . " " . "<a href='users.php'>View Users</a> ";

}

?>
<form action="" method="POST" enctype="multipart/form-data">

    <div class="form-group">
        <label for="firstname">Firstname</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="user_lastname">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>
    
    <select name="user_role" id="">
        <option value="subscriber">Select Options</option>
        <option value="admin">Admin</option>
        <option value="subscriber">Subscriber</option>
    </select>
    
    <!-- <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="image">
    </div> -->
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="post_content">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="post_content">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_user" value="Add User">
    </div>
</form>