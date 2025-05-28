<?php
    $server = "localhost";
    $user = "easyadmin";
    $pass = "easy54321.0";
    $db = "easyfood";
try{
    $conn = new PDO("mysql:host=$server; dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "se estableció la conexión";
}catch(PDOException $e){
    echo "Error: " . $e->getMessage();
}
?>