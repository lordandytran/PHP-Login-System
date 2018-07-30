<?php
function generateAccessToken($id) {
    global $db, $secret_code;
    $rand_num = random_bytes(24);
    $token = hash('sha256', $rand_num . $secret_code . $id);
    $timestamp =  date('Y-m-d G:i:s', strtotime("+1 Days"));
    $sql = sprintf("INSERT INTO access_tokens(access_token, client_id, expires) VALUES('%s', '%s', '%s')",
        $token, $id, $timestamp);
    $db->exec($sql);
    return $token;
}

function generateRefreshToken($access_token, $id) {
    global $db, $secret_code;
    $rand_num = random_bytes(24);
    $token = hash('sha256', $rand_num . $secret_code . $id);
    $timestamp =  date('Y-m-d G:i:s', strtotime("+7 Days"));
    $sql = sprintf("INSERT INTO refresh_tokens(refresh_token, client_id, expires, user_agent, hostname, access_token) VALUES('%s', '%s', '%s', '%s', '%s', '%s')",
        $token, $id, $timestamp, $_SERVER['HTTP_USER_AGENT'], gethostbyaddr($_SERVER['REMOTE_ADDR']), $access_token);
    $db->exec($sql);
    return $token;
}