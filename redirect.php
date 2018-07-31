<?php
    require "scripts/db_connect.php";
    session_start();
    if(!isset($_SESSION['token'])) {
        header('location: login.php');
    }
    else {
        $sql = sprintf("SELECT expires FROM access_tokens WHERE access_token='%s'", $_SESSION['token']);
        $query = $db->query($sql);
        if($query->rowCount() > 0) {
            foreach($query as $row) {
                if(time() >= strtotime($row['expires'])) {
                    $delete = sprintf("DELETE FROM refresh_tokens WHERE refresh_token='%s'", $_SESSION['token']);
                    $db->exec($delete);
                    unset($_SESSION['token']);
                    session_unset();
                    session_destroy();
                    header('location: login.php');
                }
            }
        }
    }