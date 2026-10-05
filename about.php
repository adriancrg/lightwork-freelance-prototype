<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What is Lightning? - LightWork</title>
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

    <div class="lightning-container">
        <h1>What is Bitcoin Lightning?</h1>

        <p class="highlight">The Bitcoin Lightning Network is a second-layer protocol built on top of the Bitcoin blockchain, designed to enable faster, cheaper transactions by allowing users to transact off-chain and settle only the final result on the Bitcoin mainnet.</p>

        <h2>How Does the Lightning Network Work?</h2>
        <p>The Lightning Network allows users to create payment channels between two parties, enabling transactions off the main Bitcoin blockchain. Here’s how it works:</p>
        <ul>
            <li><strong>Opening a Channel:</strong> Two parties open a payment channel by committing a certain amount of Bitcoin on the mainnet.</li>
            <li><strong>Off-Chain Transactions:</strong> Once the channel is open, the parties can transact freely off-chain with instant and low-fee transactions.</li>
            <li><strong>Closing the Channel:</strong> When ready, the channel is closed and the final balance is recorded on the Bitcoin blockchain.</li>
        </ul>

        <h2>Advantages of Using the Lightning Network</h2>
        <p>The Lightning Network offers several advantages, especially for a marketplace like LightWork:</p>
        <ul>
            <li><strong>Speed:</strong> Instant transactions ensure quick payments between freelancers and clients.</li>
            <li><strong>Low Fees:</strong> Off-chain transactions have minimal fees, making it cost-effective.</li>
            <li><strong>Scalability:</strong> Capable of handling millions of transactions per second, far surpassing the Bitcoin mainnet.</li>
            <li><strong>Privacy:</strong> Off-chain transactions are not publicly recorded, providing more privacy.</li>
        </ul>

        <h2>Why LightWork Uses the Lightning Network</h2>
        <p>LightWork leverages the Lightning Network to create a seamless and secure platform for freelancers and clients:</p>
        <ul>
            <li>Faster transaction times ensure freelancers are paid promptly.</li>
            <li>Lower transaction costs make hiring freelancers more affordable.</li>
            <li>A secure, trustless environment with payments held in escrow until both parties are satisfied.</li>
        </ul>
        <p>In essence, the Lightning Network is perfect for LightWork’s mission to create a decentralized, trustless freelance marketplace where freelancers and clients can thrive.</p>
    </div>
</body>
</html>
