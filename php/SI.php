<?php

    if(isset($_POST["submit_si"])){

        $username=$_POST["username"];
        $pwd = $_POST["password"];
        $pwd = hash('sha256', $pwd);

        require_once 'conexion_be.php';
        require_once 'functions.php';

        if (emptyInputSignin($username, $pwd) !== false){
            header("location: ../login.php?error=emptyinput");
            exit();
        }

        loginUser($conn, $username, $pwd);
    }else{
        header("location: ../login.php");
        exit();
    }