<?php
// update_chat.php

include 'functions.php';
include 'conexion_be.php';

$conversation_id = $_GET['id'];
$user_type = $_GET['user_type'];

echo updateChatBox($conversation_id, $user_type);
?>
