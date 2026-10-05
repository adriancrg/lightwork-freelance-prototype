<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../upload.php");
    exit();
}

include_once 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $sellerUsername = $_SESSION['username']; // Asumiendo que el nombre de usuario está en la sesión

    // Insertar la oferta en la base de datos
    $sql = "INSERT INTO offers (Title, Descriptionn, Category, Price, SellerUsername) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    }
    mysqli_stmt_bind_param($stmt, "sssds", $title, $description, $category, $price, $sellerUsername);
    mysqli_stmt_execute($stmt);
    $offerId = mysqli_insert_id($conn);

    // Manejar la carga de imágenes
    $uploadDir = "../assets/offer_images/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['photos']['name'][$key];
        $file_tmp = $_FILES['photos']['tmp_name'][$key];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Generar un nombre único para el archivo
        $unique_name = uniqid() . "." . $file_ext;
        $file_path = $uploadDir . $unique_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Guardar la ruta de la imagen en la base de datos
            $relative_path = "assets/offer_images/" . $unique_name;
            $sql = "INSERT INTO images (offer_id, image_path) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "is", $offerId, $relative_path);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    header("Location: ../offer_uploaded_succesfully.php?id=" . $offerId);
    exit();
}