<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - LightWork</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="assets/images/logo%20lightwork%20v0.2.png" alt="LightWork Logo">
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">What is Lightning?</a></li>
                <li><a href="upload.php">Post an offer</a></li>
                <li><a href="browse.php">Browse offers</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            
        </nav>

        <?php
                if (isset($_SESSION["username"])){
                    echo "<div class='auth-buttons'>";
                    echo "<a href='user_profile.php' class='login-btn'>Profile</a>";
                    echo "<a href='php/LO.php' class='signup-btn'>Log out</a>";
                    echo "</div>";
                }else{
                    echo "<div class='auth-buttons'>";
                    echo "<a href='login.php' class='login-btn'>Login</a>";
                    echo "<a href='login.php' class='signup-btn'>Signup</a>";
                    echo "</div>";
                }
                ?>
    </header>

    <div class="contact-container">
        <h1 class="titulo">Get in Touch with Us</h1>
        <p class="justificado">For any questions, support, or disputes, you can reach out to us through our dedicated Telegram bot. Click the button below to start chatting with our moderators.</p>

        <div class="contact-button-container">
            <a href="https://t.me/YourBotUsername" target="_blank" class="contact-button">Contact Us on Telegram</a>
        </div>
    </div>
</body>
</html>
