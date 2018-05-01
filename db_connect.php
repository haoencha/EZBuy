<?php

$host = "uscitp.com";
$username = "haoencha_user";
$password = "usc2015";
$database = "haoencha_finalProject_users_db";

$mysqli = new mysqli($host, $username, $password, $database);

if($mysqli->error){
    exit($mysqli->error);
}

