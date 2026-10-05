<?php

session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightWork - Lightning P2P Trustless Freelance Marketplace</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    
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

    <div class="container">
        <h1 id="lightning-p2p-trustless-freelance-marketplace" class="titulo">Lightning P2P trustless freelance marketplace</h1>

        <p class="how-it-works">LightWork is a Web3 marketplace connecting freelancers and clients through Bitcoin Lightning Network payments. Our platform allows freelancers to showcase their services while clients can securely hire and pay using fast, low-cost Bitcoin transactions. We ensure trustless transaction safety with our automated escrow system.</p>

        <h3 class="subtitulo">Explore Job Categories</h3>

        <div class="category-carousel">
            <button class="arrow arrow-left">&lt;</button>
            <div class="category-container">
                <div class="category-wrapper">
                    <a href="#" class="category">
                        <img src="assets/images/web-development.jpg" alt="Web Development">
                        <span>Web Development</span>
                    </a>
                    <a href="#" class="category">
                        <img src="assets/images/graphic-design.jpg" alt="Graphic Design">
                        <span>Graphic Design</span>
                    </a>
                    <a href="#" class="category">
                        <img src="assets/images/seo.jpeg" alt="SEO">
                        <span>SEO</span>
                    </a>
                    <a href="#" class="category">
                        <img src="assets/images/financial-advice.jpg" alt="Financial Advice">
                        <span>Financial Advice</span>
                    </a>
                    <!-- Puedes añadir más categorías aquí -->
                </div>
            </div>
            <button class="arrow arrow-right">&gt;</button>
        </div>

        <a href="#" class="explore-all">Explore all categories and offers</a>

        <div class="how-it-works">
            <h2>How does LightWork function?</h2>
            <p>LightWork is a purely anonymous and trustless P2P freelance-to-customer bridging platform. Every payment is performed through the Bitcoin Lightning Network and the funds are first deposited in an escrow wallet before reaching the customer; that way both parties can be assured that neither their money nor their work are going to get stolen.</p>
            <p>Once the customer has placed the payment, the freelancer is notified and the job is delivered by them. When the customer receives the final product the funds are released from the escrow wallet to the seller's account.</p>
            <p>Any situations where the work is sent but the customer doesn't want to release the money to the seller because they are not satisfied will be treated and sorted as a dispute by our experienced moderators.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrapper = document.querySelector('.category-wrapper');
            const categories = document.querySelectorAll('.category');
            const leftArrow = document.querySelector('.arrow-left');
            const rightArrow = document.querySelector('.arrow-right');
            
            let position = 0;
            const categoryWidth = categories[0].offsetWidth + 20; // width + margin-right
            const totalWidth = categoryWidth * categories.length;
            
            function moveCarousel(direction) {
                position += direction * categoryWidth;
                if (position > 0) {
                    position = -(totalWidth - categoryWidth);
                } else if (position <= -(totalWidth)) {
                    position = 0;
                }
                wrapper.style.transform = `translateX(${position}px)`;
            }
            
            leftArrow.addEventListener('click', () => moveCarousel(1));
            rightArrow.addEventListener('click', () => moveCarousel(-1));
        });
    </script>
</body>
</html>
