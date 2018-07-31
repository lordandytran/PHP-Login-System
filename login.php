<?php
require "scripts/db_connect.php";
session_start();
if(isset($_SESSION['token'])) {
    $sql = sprintf("SELECT expires FROM access_tokens WHERE access_token='%s'", $_SESSION['token']);
    $query = $db->query($sql);
    if($query->rowCount() > 0) {
        foreach($query as $row) {
            if(time() < strtotime($row['expires'])) {
                header('location: index.php');
            }
            else {
                $delete = sprintf("DELETE FROM refresh_tokens WHERE refresh_token='%s'", $_SESSION['token']);
                $db->exec($delete);
                unset($_SESSION['token']);
                session_unset();
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<script>
$(document).ready(function(){
    var refresh = localStorage.getItem('site-refresh');
    if(refresh) {
        $.get("scripts/refresh.php", {token : refresh}, function(data) {
            data = JSON.parse(data);
            if(data.success === true) {
                localStorage.setItem('rtfront-refresh', data.token);
                window.location.href = "index.php";
            }
        });
    }
});
function formSubmit() {
    $(".login-form").submit(function(event) {
        event.preventDefault();
        $.post("scripts/authorize.php", $(".login-form").serialize(), function(data) {
            data = JSON.parse(data);
            if(data.success === true) {
                localStorage.setItem('site-refresh', data.token);
                window.location.href = "index.php";
            }
            else {
                $(".error").text("Nope!");
            }
        });
    });
}
</script>
<div class="login-page">
    <div class="form">
        <h3>rTfront</h3>
        <form class="login-form" id="login" method="post">
            <div class="error"></div><p></p>
            <input type="text" name="user" placeholder="username" required/>
            <input type="password" name="pass" placeholder="password" required/>
            <button id="form-submit" onclick="formSubmit()" name="submit">login</button>
        </form>
    </div>
</div>
</body>
</html>