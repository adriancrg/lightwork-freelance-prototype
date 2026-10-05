<?php
session_start();
include_once 'php/conexion_be.php'; // Asegúrate de que esta ruta sea correcta para incluir tu archivo de conexión a la base de datos

// Verificar si se proporcionó un ID de oferta
if (!isset($_GET['id'])) {
    die("No se proporcionó ID de oferta");
}

$offerId = $_GET['id'];

// Consulta para obtener los detalles de la oferta
$sql = "SELECT o.*, u.Username as SellerUsername 
        FROM offers o 
        LEFT JOIN usuarios u ON o.SellerUsername = u.Username 
        WHERE o.OfferID = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL error");
}

mysqli_stmt_bind_param($stmt, "i", $offerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $title = htmlspecialchars($row['Title']);
    $description = htmlspecialchars($row['Descriptionn']);
    $price = $row['Price'];
    $category = htmlspecialchars($row['Category']);
    $sellerUsername = htmlspecialchars($row['SellerUsername']);
    $createdAt = $row['created_at'];

    // Obtener el ID del vendedor de la tabla usuarios
    $sqlSeller = "SELECT ID FROM usuarios WHERE Username = ?";
    $stmtSeller = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtSeller, $sqlSeller)) {
        die("SQL error");
    }
    mysqli_stmt_bind_param($stmtSeller, "s", $sellerUsername);
    mysqli_stmt_execute($stmtSeller);
    $resultSeller = mysqli_stmt_get_result($stmtSeller);
    if ($rowSeller = mysqli_fetch_assoc($resultSeller)) {
        $sellerId = $rowSeller['ID'];
    } else {
        die("Error: No se pudo obtener el ID del vendedor");
    }
} else {
    die("Oferta no encontrada");
}

// Aquí puedes agregar una consulta adicional para obtener las imágenes relacionadas con esta oferta si las tienes en otra tabla
// Obtener las imágenes de la oferta
$sql = "SELECT image_path FROM images WHERE offer_id = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    die("SQL error");
}
mysqli_stmt_bind_param($stmt, "i", $offerId);
mysqli_stmt_execute($stmt);
$imageResult = mysqli_stmt_get_result($stmt);
$images = mysqli_fetch_all($imageResult, MYSQLI_ASSOC);


// Función para procesar la toma de la oferta
function takeOffer($conn, $offerId, $sellerId, $buyerId) {
    $sql = "INSERT INTO conversations (offer_id, seller_id, buyer_id) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "iii", $offerId, $sellerId, $buyerId);
    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($conn);
    }
    return false;
}

// Procesar la toma de la oferta si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['take_offer'])) {
    if (!isset($_SESSION['userid'])) {
        echo "<script>alert('Por favor, inicia sesión para tomar esta oferta.');</script>";
    } else {
        $buyerId = $_SESSION['userid'];
        $conversationId = takeOffer($conn, $offerId, $sellerId, $buyerId);
        if ($conversationId) {
            header("Location: taken_offer.php?id=" . $conversationId);
            exit();
        } else {
            echo "<script>alert('Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.');</script>";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - LightWork</title>
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
        <h2 class="section-title">Offer details</h2>

        <div class="offer-header">
            <h1 class="offer-title"><?php echo $title; ?></h1>
            <div class="user-info-price">
                <div class="user-info">
                    <img src="assets/images/Usericon.png" alt="User Profile Picture" class="user-profile-picture">
                    <span class="username"><?php echo $sellerUsername; ?></span>
                </div>
                <hr class="user-price-divider">
                <div class="offer-price"><?php echo number_format($price); ?> sats</div>
            </div>
        </div>

        <!-- Galería de Imágenes en Carrusel -->
        <!-- Aquí deberías cargar dinámicamente las imágenes relacionadas con esta oferta -->
        <div class="carousel-container">
            <button class="carousel-button left">&lt;</button>
            <div class="carousel">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Offer Image" class="carousel-image">
                <?php endforeach; ?>
            </div>
            <button class="carousel-button right">&gt;</button>
        </div>

        <div class="offer-details">
            <h3 class="description-title">Description</h3>
            <p class="offer-description"><?php echo $description; ?></p>
            <p>Category: <?php echo $category; ?></p>
            <p>Posted on: <?php echo $createdAt; ?></p>

            <!-- Añadir el botón "Take Offer" -->
            <form method="post" action="">
                <button type="submit" name="take_offer" class="take-offer-btn">Take Offer</button>
            </form>
        </div>

        

    </div>

    


    <script>
        // JavaScript para el Carrusel
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('.carousel');
            const images = document.querySelectorAll('.carousel-image');
            const leftButton = document.querySelector('.carousel-button.left');
            const rightButton = document.querySelector('.carousel-button.right');

            let index = 0;

            function showImage(index) {
                carousel.style.transform = `translateX(-${index * 100}%)`;
            }

            rightButton.addEventListener('click', () => {
                index = (index + 1) % images.length;
                showImage(index);
            });

            leftButton.addEventListener('click', () => {
                index = (index - 1 + images.length) % images.length;
                showImage(index);
            });
        });
    </script>
</body>
</html>
