<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "b2";

$connect_database = mysqli_connect($host,$username,$password,$dbname );

if(mysqli_connect_errno()){

    echo "not connected -غير متصل ";
    exit();
}

else{

    echo "connected -متصل " ;
}


?>