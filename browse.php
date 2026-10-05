<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightWork - Browse Offers</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">


    <style>
        .search-bar {
    display: flex;
    margin-bottom: 20px;
}

.search-bar input {
    flex-grow: 1;
    padding: 10px;
    font-size: 16px;
    border: 1px solid var(--primary-color);
    border-radius: 5px 0 0 5px;
}

.search-bar button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
}
.filters {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.filters select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid var(--primary-color);
    border-radius: 5px;
    background-color: var(--secondary-background);
    color: var(--text-color);
}

/*Añadido por claude:*/
.offer-card {
    background-color: var(--secondary-background);
    border: 1px solid var(--primary-color);
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
}

.offer-card h3 {
    margin-top: 0;
    color: var(--primary-color);
}

.offer-card .price {
    font-weight: bold;
    color: var(--accent-color);
}

.view-offer-btn {
    display: inline-block;
    background-color: var(--primary-color);
    color: white;
    padding: 5px 10px;
    border-radius: 3px;
    text-decoration: none;
    margin-top: 10px;
}

</style>

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
        <h1 class="titulo">Browse Offers</h1>

        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search offers...">
            <button id="search-button">Search</button>
        </div>

        <div class="filters">
            <select id="category-filter">
                <option value="">All Categories</option>
                <option value="web-development">Web Development</option>
                <option value="graphic-design">Graphic Design</option>
                <option value="seo">SEO</option>
                <option value="financial-advice">Financial Advice</option>
            </select>
            <select id="price-filter">
                <option value="">All Prices</option>
                <option value="0-50">$0 - $50</option>
                <option value="51-100">$51 - $100</option>
                <option value="101-200">$101 - $200</option>
                <option value="201+">$201+</option>
            </select>
        </div>

        <div id="offers-grid" class="offers-grid">
            <!-- Offers will be dynamically inserted here -->
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const offersGrid = document.getElementById('offers-grid');

    let offers = [];

    fetch('php/get_offers.php')
        .then(response => response.json())
        .then(data => {
            offers = data;
            renderOffers(offers);
        })
        .catch(error => console.error('Error:', error));

    function renderOffers(filteredOffers) {
        offersGrid.innerHTML = '';
        filteredOffers.forEach(offer => {
            const offerCard = document.createElement('div');
            offerCard.className = 'offer-card';
            offerCard.innerHTML = `
                <h3>${offer.title}</h3>
                <p>${offer.description.substring(0, 100)}...</p>
                <p class="price">${offer.price} sats</p>
                <p>Category: ${offer.category}</p>
                <p>Seller: ${offer.sellerUsername}</p>
                <a href="display_offer.php?id=${offer.id}" class="view-offer-btn">View Offer</a>
            `;
            offersGrid.appendChild(offerCard);
        });
    }

    function filterOffers() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        const priceRange = priceFilter.value;

        const filteredOffers = offers.filter(offer => {
            const matchesSearch = offer.title.toLowerCase().includes(searchTerm) || 
                                  offer.description.toLowerCase().includes(searchTerm);
            const matchesCategory = category === '' || offer.category === category;
            const matchesPrice = priceRange === '' || 
                                 (priceRange === '0-50' && offer.price <= 50) ||
                                 (priceRange === '51-100' && offer.price > 50 && offer.price <= 100) ||
                                 (priceRange === '101-200' && offer.price > 100 && offer.price <= 200) ||
                                 (priceRange === '201+' && offer.price > 200);

            return matchesSearch && matchesCategory && matchesPrice;
        });

        renderOffers(filteredOffers);
    }

    searchButton.addEventListener('click', filterOffers);
    searchInput.addEventListener('input', filterOffers);
    categoryFilter.addEventListener('change', filterOffers);
    priceFilter.addEventListener('change', filterOffers);
});
    </script>
</body>
</html>