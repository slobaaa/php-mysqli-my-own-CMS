<?php


if (!function_exists('escape')) {
    // ... proceed to declare your function
    function escape($string) { // ovo je kao more security zbog hakera, samo primer
        global $connection;
       return mysqli_real_escape_string($connection, trim($string));
    }
    

function email_exists($email){
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    if(mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;

    }
}


function confirmQuery($result) {
    global $connection;
    if (!$result) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
}

function insertCategories() {
    global $connection;
    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];
        if($cat_title == "" || empty($cat_title)) {
            echo "This field shouldn't be empty";
        } else {

            $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES (?)");
            mysqli_stmt_bind_param($stmt, 's', $cat_title);
            mysqli_stmt_execute($stmt);



            // $query = "INSERT INTO categories(cat_title) VALUES ('{$cat_title}')";
            // $create_category_query = mysqli_query($connection, $query);
            if (!$stmt) {
            // if (!$create_category_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    }
}

function findAllCategories() {
    global $connection;
    $query="SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    while($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}

function deleteCategories() {
    global $connection;
    if (isset($_GET['delete'])) {
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php"); // sa onim ob_start mogu to da uradim
    }
}

function users_online() {
    if (isset($_GET['onLineusers'])) {
        global $connection;
        if (!$connection) {
            session_start();
            include("../includes/db.php");
            $session = session_id(); //svaki pu kad se ukljuci sesija, ovaj hvata id od sesije
            $time = time();
            $time_out_in_seconds = 5;
            $time_out = $time - $time_out_in_seconds;
            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            if (!$send_query) {
                die("greska" . mysqli_error($connection));
            }
            $count = mysqli_num_rows($send_query);
            if ($count == NULL) {
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES ('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
        
    

    } //get reqeust isset
}
users_online();

function is_admin($username) {
    global $connection;
    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    $row = mysqli_fetch_array($result);

    if ($row['user_role'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function username_exist($username) { // treba napraviti funckciju i za email
    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function redirect($location) {
    return header("Location:" . $location);
    exit;
}

function ifItIsMethod($method=null) {
    if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
        return true;
    }

    return false;
}

function isLoggedIn() {
    if (isset($_SESSION['user_role'])) {
        return true;
    }
    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null) {
    if (isLoggedIn()) {
        redirect($redirectLocation);
    }
}

function register_user($username, $email, $password) {
    global $connection;
    // $username = $_POST['username'];
    // $email = $_POST['email'];
    // $password = $_POST['password'];

    if (username_exist($username)) {
        $message = "user exists";
        die("username exist" . mysqli_error($connection) . ' ' . mysqli_errno($connection));
    }

    $username = mysqli_real_escape_string($connection, $username);
    $email = mysqli_real_escape_string($connection, $email);
    $password = mysqli_real_escape_string($connection, $password);

    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)); // ovaj bcrypt je blowfish

    $query = "INSERT INTO users(username, user_email, user_password, user_role)
        VALUES('{$username}','{$email}','{$password}','subscriber')";
    $register_user_query = mysqli_query($connection, $query);

    if (!$register_user_query) {
        die("Uqery failde" . mysqli_error($connection) . ' ' . mysqli_errno($connection));
    };
}


function login_user($username, $password)
{

    global $connection;

    $username = trim($username);
    $password = trim($password);

    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
    while ($row = mysqli_fetch_array($select_user_query)) {

        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];

        if (password_verify($password,$db_user_password)) {
            $_SESSION['username'] = $db_username;
            $_SESSION['firstname'] = $db_user_firstname;
            $_SESSION['lastname'] = $db_user_lastname;
            $_SESSION['user_role'] = $db_user_role;
            redirect("../cms/admin/index.php");
        } else {
            return false;
        }
    }
    return true;
}


function currentUser() {
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }
    return false;
}

function imagePlaceholder($image = '') {
    if (!$image) {
        return 'placeholder.jpg';
    } else {
        return $image;
    }
}
}

?>


