<?php

// Incluir la conexión a la base de datos
include 'conexion_be.php';

// Asegurar que $conn esté disponible globalmente
global $conn;

function emptyInputSignup($email, $usuario, $contrasena){
    $result;
    if(empty($email) || empty($usuario) || empty($contrasena)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

function invalidUsername($usuario){
    $result;
    if(!preg_match("/^[a-zA-Z0-9]*$/", $usuario)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

function invalidEmail($email){
    $result;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}



function UsernameExists($conn, $usuario, $email){
    $sql = "SELECT * FROM usuarios WHERE Username = ? OR Email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../login.php?error=stmtfailed");
        exit();
    }


    mysqli_stmt_bind_param($stmt, "ss", $usuario, $email);
    mysqli_stmt_execute($stmt);

    $resultData= mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }else{
        $result=false;
        return $result;
    }

    mysqli_stmt_close($stmt);

}


function createUser($conn, $email, $usuario, $contrasena){
    $sql = "INSERT INTO usuarios(Email, Username, Pword) VALUES(?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../login.php?error=stmtfailed");
        exit();
    }


    mysqli_stmt_bind_param($stmt, "sss", $email, $usuario, $contrasena);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../login.php?error=none");
    exit();

}

function emptyInputSignin($username, $pwd){
    $result;
    if(empty($username) || empty($pwd)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;
}

function loginUser($conn, $username, $pwd) {
    $uidExists = UsernameExists($conn, $username, $username);

    if ($uidExists === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["Pword"];
    //$checkPwd = password_verify($pwd, $pwdHashed);



    if ($pwd !== $pwdHashed){
        //header("location: ../login.php?error=wrongpassword");
        //exit();
    }else if($pwd == $pwdHashed){
        session_start();
        $_SESSION["userid"] = $uidExists["ID"];
        $_SESSION["username"] = $uidExists["Username"];
        header("location: ../index.php");
        exit();
    }
}

// Función para enviar un mensaje
function sendMessage($conversation_id, $sender, $message) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO chat_messages (conversation_id, buyer_seller, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $conversation_id, $sender, $message);
    $stmt->execute();
    $stmt->close();
}

// Función para obtener los mensajes de una conversación
function getMessages($conversation_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT buyer_seller, message, timestamp FROM chat_messages WHERE conversation_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $conversation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $messages;
}

// Función para actualizar la caja del chat
function updateChatBox($conversation_id, $user_type) {
    $messages = getMessages($conversation_id);
    $chatHTML = '';

    foreach ($messages as $msg) {
        $isOutgoing = ($msg['buyer_seller'] == $user_type);
        $messageClass = $isOutgoing ? 'outgoing' : 'incoming';
        $chatHTML .= '<div class="chat-message ' . $messageClass . '">';
        $chatHTML .= '<div class="message-content"><p>' . htmlspecialchars($msg['message']) . '</p></div>';
        $chatHTML .= '<p class="timestamp">' . htmlspecialchars($msg['timestamp']) . '</p>';
        $chatHTML .= '</div>';
    }

    return $chatHTML;
}