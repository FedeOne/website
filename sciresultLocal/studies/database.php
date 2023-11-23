<?php

$host = "localhost";
$dbname = "u551391167_sciresults";
$username = "u551391167_fedesci2";
$password = "Scire132!";

$mysqli = new mysqli($host,
                     $username,
                     $password,
                     $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;

?>