<?php
require "scripts/db_connect.php";
session_start();
if(isset($_SESSION['token'])) {
    $sql = sprintf("SELECT expires FROM access_tokens WHERE access_token='%s'", $_SESSION['token']);
    $result = $db->prepare($sql);
    $result->execute();
    if($result->rowCount() > 0) {
        $query = $db->query($sql);
        foreach($query as $row) {
            if(time() < strtotime($row['expires'])) {
                header('location: index.php');
            }
            else {
                $delete = sprintf("DELETE FROM refresh_tokens WHERE access_token='%s'", $_SESSION['token']);
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
    <script>
        if(localStorage.length > 0) {
            for(var i = 0; i < localStorage.length; i++) {
                var key = localStorage.key(i);
                if(key.match(/rtfront[-]refresh/)) {
                    var refresh = localStorage.getItem(key);
                    $.get("scripts/refresh.php", {token : refresh}, function(data) {
                        data = JSON.parse(data);
                        if(data.success === true) {
                            const now = new Date();
                            localStorage.setItem('rtfront-refresh' + now.getTime().toString(), data.token);
                            window.location.href = "index.php";
                        }
                    });
                }
            }
            $( document ).ready(function() {
                $("body").show();
            });
        }
        else {
            $( document ).ready(function() {
                $("body").show();
            });
        }
    </script>
</head>
<body>
<script>
function formSubmit() {
    $(".login-form").submit(function(event) {
        event.preventDefault();
        $.post("scripts/authorize.php", $(".login-form").serialize(), function(data) {
            data = JSON.parse(data);
            $('.login-form').off('submit').submit();
            if(data.success === true) {
                const now = new Date();
                localStorage.setItem('rtfront-refresh' + now.getTime().toString(), data.token);
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