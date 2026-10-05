<?php

if (isset($_POST["submit_su"])){

    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $contrasena = hash('sha256', $contrasena);

    require_once 'conexion_be.php';
    require_once 'functions.php';

    if (emptyInputSignup($email, $usuario, $contrasena) !== false){
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    if (invalidUsername($usuario) !== false){
        header("location: ../login.php?error=invalidusername");
        exit();
    }

    if (invalidEmail($email) !== false){
        header("location: ../login.php?error=invalidemail");
        exit();
    }

    if (UsernameExists($conn, $usuario, $email) !== false){
        header("location: ../login.php?error=usernametaken");
        exit();
    }

    createUser($conn, $email, $usuario, $contrasena);

}else{
    header("location: ../login.php");
    exit();
}