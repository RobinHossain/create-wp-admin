<?php

# Database Configuration

if(file_exists("wp-config.php")){
    include_once('wp-config.php');
    $servername = DB_HOST; //server name
    $username = DB_USER; // username
    $password = DB_PASSWORD; // password
    $dbname = DB_NAME; // database name
    $prefix = $table_prefix;
}else{
    $servername = "localhost"; //server name
    $username = "robin"; // username
    $password = "robin"; // password
    $dbname = "wp"; // database name
    $prefix = "wp_";
}

//print_r($servername);echo '<hr>';
//print_r($username);echo '<hr>';
//print_r($password);echo '<hr>';
//print_r($dbname);echo '<hr>';
//print_r($prefix);echo '<hr>';

if(isset($_POST['wp_u']) && $_POST['wp_p'] && $_POST['wp_fname'] && $_POST['wp_lname'] && $_POST['wp_email']){
    $wp_username = $_POST['wp_u']; //wordpress admin username
    $wp_password = $_POST['wp_p'];  // admin password
    $wp_firstname = $_POST['wp_fname']; //first name
    $wp_lastname = $_POST['wp_lname']; //last name
    $wp_email = $_POST['wp_email']; // email address
}else{ ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Wordpress Admin User Creation</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    </head>
        <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Please input your new admin user information</h2>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Username: </label>
                            <input type="text" class="form-control" id="wp_u" name="wp_u" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="username">Password: </label>
                            <input type="text" class="form-control" id="wp_p" name="wp_p" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="username">First Name: </label>
                            <input type="text" class="form-control" id="wp_fname" name="wp_fname" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <label for="username">Last Name: </label>
                            <input type="text" class="form-control" id="wp_lname" name="wp_lname" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <label for="username">Email Address: </label>
                            <input type="email" class="form-control" id="wp_email" name="wp_email" placeholder="Email Address">
                        </div>

                        <input type="submit" class="btn btn-default" value="Submit">
                    </form>
                </div>
            </div>
        </div>


        </body>

    </html>
<?php }

//print_r($wp_username);echo '<hr>';
//print_r($wp_password);echo '<hr>';
//print_r($wp_firstname);echo '<hr>';
//print_r($wp_lastname);echo '<hr>';
//print_r($wp_email);echo '<hr>';



$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$wp_f_pass = md5($wp_password);

$sql_1 = "INSERT INTO `{$prefix}users` (`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_status`, `display_name`)
VALUES ( '$wp_username', '$wp_f_pass', '$wp_firstname', '$wp_email', '0', '$wp_firstname');";


$sql_2 = "INSERT INTO `{$prefix}usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`)
VALUES (NULL, (Select max(id) FROM wp_users), 'wp_capabilities', 'a:1:{s:13:\"administrator\";s:1:\"1\";}');";

$sql_3 = "INSERT INTO `{$prefix}usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`)
VALUES (NULL, (Select max(id) FROM wp_users), 'wp_user_level', '10');";


//print_r($sql_1);echo '<hr>';
//print_r($sql_1);echo '<hr>';
//print_r($sql_1);echo '<hr>';
//exit;


if ($conn->query($sql_1) &&  $conn->query($sql_2) && $conn->query($sql_3) === TRUE) {
    echo "Admin Created Successfully with <br> <strong>username:</strong> $wp_username <br> <strong>password:</strong> $wp_password";
} else {
    echo "Error: " . $sql_1 . "<br>" . $conn->error . "<br>";
    echo "Error: " . $sql_2 . "<br>" . $conn->error . "<br>";
    echo "Error: " . $sql_3 . "<br>" . $conn->error . "<br>";
}

$conn->close();

?>
