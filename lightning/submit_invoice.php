<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ln_invoice = $_POST['ln_invoice'];
    $amount = 1000; // El monto en satoshis (debería estar en la oferta)

    // Aquí llamamos al script de Node.js para crear la hold invoice
    $output = shell_exec("node create_hold_invoice.js '$ln_invoice' '$amount'");
    $response = json_decode($output, true);

    if ($response && isset($response['request'])) {
        echo "Hold Invoice creada: " . $response['request'];
    } else {
        echo "Error al crear la Hold Invoice.";
    }
}
?>
