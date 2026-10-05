<?php
session_start();
include 'php/conexion_be.php';

// Verificar si se proporcionó un ID de oferta
if (!isset($_GET['id'])) {
    die("No se proporcionó ID de oferta");
}

$offerId = $_GET['id'];

// Verificar si el usuario está logueado 
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION["username"];

// Obtener los detalles de la oferta
$query = "SELECT * FROM offers WHERE OfferID = $offerId AND SellerUsername = '$username'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$offer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Uploaded Successfully - LightWork</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
    <style>
        .success-container {
	text-align: center;
	background-color: var(--secondary-background);
	padding: 40px;
	border-radius: 10px;
	max-width: 500px;
	margin: 100px auto;
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
	margin-top:10px;
    }
    .success-icon {
    	width: 100px;
    	height: 100px;
    }
    .success-message {
    	font-size: 24px;
    	color: var(--text-color);
    	margin-bottom: 30px;
    	font-family: 'BNBobbieSans', Arial, sans-serif;
    	margin-top: 10px;
    }
    .button {
    	display: inline-block;
    	background-color: var(--primary-color);
    	color: white;
    	padding: 10px 20px;
    	text-decoration: none;
    	border-radius: 5px;
    	font-weight: bold;
    	transition: background-color 0.3s ease;
    	margin-bottom: 20px;
    }
    .button:hover {
    	background-color: #7a0bdb;
    }
    .text-link {
    	color: var(--primary-color);
    	text-decoration: none;
    	font-weight: bold;
    	transition: opacity 0.3s ease;
    }
    .text-link:hover {
    	opacity: 0.8;
    }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="assets/images/logo lightwork v0.2.png" alt="LightWork Logo">
            </a>
        </div>
        <nav>
            <ul>
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

    <main class="container">
        <div class="success-container">
            <img src="assets/images/checkmorado.png" alt="Success" class="success-icon">
            <p class="success-message">Offer uploaded successfully</p>
            <a href="display_offer.php?id=<?php echo $offerId; ?>" class="button">View my offer</a>
            <br>
            <a href="browse.php" class="text-link">See all offers</a>
        </div>
    </main>
</body>
</html>
