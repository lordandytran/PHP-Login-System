<?php
require "scripts/db_connect.php";
session_start();
$sql = sprintf("SELECT refresh_token, access_token FROM refresh_tokens WHERE access_token='%s'", $_SESSION['token']);
$query = $db->query($sql);
if($query->rowCount() > 0) {
    foreach($query as $row) {
        $deleteA = sprintf("DELETE FROM access_tokens WHERE access_token='%s'", $_SESSION['token']);
        $deleteR = sprintf("DELETE FROM refresh_tokens WHERE refresh_token='%s'", $row['refresh_token']);
        $db->exec($deleteA);
        $db->exec($deleteR);
    }
}
unset($_SESSION['token']);
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<script>
    localStorage.removeItem("rtfront-refresh");
    window.location.replace("login.php");
</script>
</body>
</html>

