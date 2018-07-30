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
                    session_destroy();

                }
            }
        }
    }