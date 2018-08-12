<?php
require "db_connect.php";
require "generate.php";
session_start();

if(isset($_GET['token'])) {
    $data = array();
    $refresh = $_GET['token'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $sql = sprintf("SELECT client_id, expires, user_agent, hostname, access_token FROM refresh_tokens WHERE refresh_token='%s'", $refresh);
    $result = $db->prepare($sql);
    $result->execute();
    if($result->rowCount() > 0) {
        $query = $db->query($sql);
        foreach($query as $row) {
            if(time() < strtotime($row['expires']) && $user_agent == $row['user_agent'] && $hostname == $row['hostname']) {
                $data['success'] = true;
                $access_token = authenticateFromRefreshToken($refresh, $row['access_token'], $row['client_id']); //Pretty dangerous operation
                $data['token'] = generateRefreshToken($access_token, $row['client_id']);
                $_SESSION['token'] = $access_token;
                break;
            }
            else if(time() >= strtotime($row['expires'])) {
                sanitizeAccessTokens($row['access_token']);
                sanitizeRefreshTokens($refresh);
                $data['success'] = false;
            }
            else {
                $data['success'] = false;
            }
        }
    }
    echo json_encode($data);
}

function authenticateFromRefreshToken($refresh_token, $access_token, $id) {
    sanitizeAccessTokens($access_token);
    sanitizeRefreshTokens($refresh_token);
    return generateAccessToken($id);
}

function sanitizeAccessTokens($token) {
    global $db;
    $delete = sprintf("DELETE FROM access_tokens WHERE access_token='%s'", $token);
    $db->exec($delete);
}

function sanitizeRefreshTokens($token) {
    global $db;
    $delete = sprintf("DELETE FROM refresh_tokens WHERE refresh_token='%s'", $token);
    $db->exec($delete);
}