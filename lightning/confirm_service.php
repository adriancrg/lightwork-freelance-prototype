<?php
// Confirmación del comprador en `confirm_service.php`
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $secret = $_POST['secret']; // Este sería el secreto de la hold invoice
    
    // Llamada al script de Node.js para liberar la hold invoice
    $output = shell_exec("node settle_hold_invoice.js '$secret'");
    echo "Pago liberado.";
}
?>
