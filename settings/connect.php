<?php
// mySQL connect string
$dbhost = 'yourhost.com';
$dbuser = 'youruser';
$dbpass = 'yourpass';
$dbname = 'ratings';
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('unable to connect to the rating database');
mysql_select_db('ratings');
?>