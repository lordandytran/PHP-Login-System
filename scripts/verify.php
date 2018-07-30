<?php
require "db_connect.php";
if(isset($_POST['submit'])) {

    $sql = sprintf("SELECT * FROM users WHERE client_id='%s'", $_POST['client']);
    if($db->query($sql)->rowCount() > 0) {
        $sql = sprintf("UPDATE users SET verified = TRUE WHERE client_id = '%s'", $_POST['client']);
        $db->exec($sql);
    }
    header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<h4>Enter Client ID to verify user</h4>
<form method="post">
    <input type="text" name="client" placeholder="Client ID" required/>
    <button name="submit">Submit</button>
</form>
</body>
</html>