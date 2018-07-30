<?php
$host        = "localhost";
$port        = "5432";
$dbname      = "dbname";
$db_user     = "postgres";
$db_pass     = "password";
$secret_code = "6ncxpTkSymCeTu1vtFpZ"; //DO NOT change after setting

$db = new PDO("pgsql:dbname=$dbname;host=$host;port=$port", $db_user, $db_pass);

?>