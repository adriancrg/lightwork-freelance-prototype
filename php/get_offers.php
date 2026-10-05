<?php
include_once 'conexion_be.php'; // Asegúrate de que esta ruta sea correcta

$sql = "SELECT * FROM offers ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$offers = [];
while ($row = mysqli_fetch_assoc($result)) {
    $offers[] = [
        'id' => $row['OfferID'],
        'title' => $row['Title'],
        'description' => $row['Descriptionn'],
        'price' => $row['Price'],
        'category' => $row['Category'],
        'sellerUsername' => $row['SellerUsername'],
        'createdAt' => $row['created_at']
    ];
}

header('Content-Type: application/json');
echo json_encode($offers);