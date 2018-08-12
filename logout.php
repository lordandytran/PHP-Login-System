<?php
require "scripts/db_connect.php";
$refresh = "";
session_start();
$sql = sprintf("SELECT refresh_token, access_token FROM refresh_tokens WHERE access_token='%s'", $_SESSION['token']);
$result = $db->prepare($sql);
$result->execute();
if($result->rowCount() > 0) {
    $query = $db->query($sql);
    foreach($query as $row) {
        $refresh = $row['refresh_token'];
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
    for(var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i);
        if(key.match(/rtfront[-]refresh/)) {
            if(localStorage.getItem(key) === <?php echo $refresh ?>) {
                localStorage.removeItem(key);
            }
        }
    }
    window.location.href = "login.php";
</script>
</body>
</html>