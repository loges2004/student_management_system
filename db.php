<?php
//it is procedural methods
$hostname = "localhost";
$username = "root";
$password = "";
$databasename = "lk";
$port = 4306;
$mysqli= mysqli_connect($hostname,$username,$password,$databasename,$port);
if(!$mysqli){
    echo ('connect error: '.mysqli_connect_error());
}

?>