<?php
require "db_connect.php";
require "generate.php";
session_start();

if(isset($_POST['pass']) && isset($_POST['user'])) {
    $data = array();
    $sql = sprintf("SELECT username, password, client_id FROM users WHERE username='%s' AND verified='TRUE'", strtolower($_POST['user']));
    $result = $db->prepare($sql);
    $result->execute();
    if($result->rowCount() > 0) {
        $query = $db->query($sql);
        foreach($query as $row) {
            if(password_verify($_POST['pass'], $row['password'])) {
                $access_token = generateAccessToken($row['client_id']);
                $data['success'] = true;
                $data['token'] = generateRefreshToken($access_token, $row['client_id']);
                $_SESSION['token'] = $access_token;
                break;
            }
            else {
                $data['success'] = false;
            }
        }
    }
    echo json_encode($data);
}
?>