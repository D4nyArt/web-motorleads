<?php 


function start_login(){
    include("login.html");
}


$email = $_GET['email'] ?? null;
$contrasena = $_GET['contrasena'] ?? null;

if($email != null && $contrasena != null){
    include("start.php");
}

else{
    start_login();
}

?>