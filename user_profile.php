<?php
session_start();
require_once 'php/conexion_be.php';
require_once 'php/functions.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit();
}

$userId = $_SESSION["userid"];
$username = $_SESSION["username"];

// Obtener la información del usuario
$sql = "SELECT Username FROM usuarios WHERE ID = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: profile.php?error=stmtfailed");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $email = $row['Username'];
} else {
    header("location: profile.php?error=usernotfound");
    exit();
}

mysqli_stmt_close($stmt);

// Obtener estadísticas del usuario (esto es un ejemplo, ajusta según tu estructura de base de datos)
$daysOnPlatform = 365; // Ejemplo: calcular días desde la fecha de registro
$averageRating = 4.2; // Ejemplo: calcular promedio de calificaciones
$activeOffers = 12; // Ejemplo: contar ofertas activas

// Datos de ejemplo para las ofertas
$exampleOffers = [
    ['title' => 'Diseño de logo', 'price' => '$50', 'date' => '2023-08-15'],
    ['title' => 'Desarrollo web', 'price' => '$500', 'date' => '2023-08-10'],
    ['title' => 'Traducción de documentos', 'price' => '$100', 'date' => '2023-08-05']
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - LightWork</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">

    <style>
    
.profile-container {
    background-color: var(--secondary-background);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
}

.user-profile-picture {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

.profile-content {
    flex-grow: 1;
}

.user-stats {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}

.stat {
    text-align: center;
}

.stat-value {
    font-size: 1.5em;
    color: var(--primary-color);
    font-weight: bold;
}

.stat-label {
    font-size: 0.9em;
    color: var(--text-color);
}

.rating .stars {
    color: gold;
    font-size: 1.2em;
}

/* Estilos para las columnas de ofertas */
.offers-container {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
}

.offers-column {
    width: 30%;
    background-color: var(--secondary-background);
    border: 2px solid var(--primary-color);
    border-radius: 10px;
    padding: 20px;
    margin-left: 4px;
    margin-right: 4px;
}

.offers-column h3 {
    color: var(--primary-color);
    font-size: 1.5em;
    margin-bottom: 20px;
    text-align: center;
}

.offer {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
}

.offer h4 {
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 10px;
}

.offer p {
    margin: 5px 0;
    color: var(--text-color);
}

/* Responsive design */
@media (max-width: 768px) {
    .offers-container {
        flex-direction: column;
    }

    .offers-column {
        width: 100%;
        margin-bottom: 20px;
    }
}

.username {
    font-size: 2em;
    color: var(--primary-color);
    margin-left: 500px;
}
</style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/images/logo lightwork v0.2.png" alt="LightWork Logo"></a>
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
        <div class="profile-container">
            <img src="assets/images/Usericon.png" alt="User Profile Picture" class="user-profile-picture">
            <div class="profile-content">
                
                <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
                <div class="user-stats">
                    <div class="stat">
                        <span class="stat-value"><?php echo $daysOnPlatform; ?></span>
                        <span class="stat-label">days in LightWork</span>
                    </div>
                    <div class="stat">
                        <div class="rating">
                            <span class="stars">
                                <?php
                                $fullStars = floor($averageRating);
                                $halfStar = $averageRating - $fullStars >= 0.5;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $fullStars) {
                                        echo '★';
                                    } elseif ($i == $fullStars + 1 && $halfStar) {
                                        echo '½';
                                    } else {
                                        echo '☆';
                                    }
                                }
                                ?>
                            </span>
                            <span class="rating-value"><?php echo number_format($averageRating, 1); ?></span>
                        </div>
                        <span class="stat-label">Average rating</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value"><?php echo $activeOffers; ?></span>
                        <span class="stat-label">Active offers</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="offers-container">
            <div class="offers-column">
                <h3>Published Offers</h3>
                <div class="offer">
                    <h4>Web Design</h4>
                    <p>Price: $100</p>
                    <p>Status: Active</p>
                </div>
                <div class="offer">
                    <h4>Logo Creation</h4>
                    <p>Price: $50</p>
                    <p>Status: Active</p>
                </div>
                <div class="offer">
                    <h4>SEO Optimization</h4>
                    <p>Price: $200</p>
                    <p>Status: Pending</p>
                </div>
            </div>

            <div class="offers-column">
                <h3>Active Offers</h3>
                <div class="offer">
                    <h4>Mobile App UI</h4>
                    <p>Client: John Doe</p>
                    <p>Due Date: 2023-09-15</p>
                </div>
                <div class="offer">
                    <h4>Content Writing</h4>
                    <p>Client: Jane Smith</p>
                    <p>Due Date: 2023-09-20</p>
                </div>
                <div class="offer">
                    <h4>Video Editing</h4>
                    <p>Client: Mike Johnson</p>
                    <p>Due Date: 2023-09-25</p>
                </div>
            </div>

            <div class="offers-column">
                <h3>Past Offers</h3>
                <div class="offer">
                    <h4>E-commerce Site</h4>
                    <p>Client: Tech Solutions Inc.</p>
                    <p>Completion Date: 2023-08-30</p>
                </div>
                <div class="offer">
                    <h4>Social Media Campaign</h4>
                    <p>Client: Fashion Brand Co.</p>
                    <p>Completion Date: 2023-08-15</p>
                </div>
                <div class="offer">
                    <h4>3D Modeling</h4>
                    <p>Client: Game Dev Studio</p>
                    <p>Completion Date: 2023-07-25</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>