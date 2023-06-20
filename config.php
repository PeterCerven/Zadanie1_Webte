<?php
$hostname = "localhost";
$username = "xcervenp";
$password = "BU5tIZLgpHeD0GN";
$dbname = "olympic_games";

$db = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
