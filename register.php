<?php
require "scripts/db_connect.php";
$error = "";
if(isset($_POST['submit'])) {
    $sql = sprintf("SELECT username FROM users WHERE username='%s'", strtolower($_POST['user']));
    if($db->query($sql)->rowCount() > 0) {
        $error = "Username already taken";
    }
    else {
        $options = [
            'cost' => 10,
        ];
        $client_id = hash('sha256', strtolower($_POST['user']));
        $sql = sprintf("INSERT INTO users(username, password, client_id, verified) VALUES('%s', '%s', '%s', FALSE)",
            strtolower($_POST['user']), password_hash($_POST['pass'], PASSWORD_BCRYPT, $options), $client_id);
        $db->exec($sql);
        unset($client_id);
        unset($client_secret);
        unset($sql);
        header("location: login.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<h4>Add User</h4>
<span style="color:red;"><?php echo $error ?></span>
<p>
<form method="post">
    <input type="text" name="user" placeholder="username" required/>
    <input type="password" name="pass" placeholder="password" required/>
    <button name="submit">Submit</button>
</form>
</body>
</html>