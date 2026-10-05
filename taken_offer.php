<?php
session_start();

include 'php/functions.php';
include 'php/conexion_be.php';

// Obtener el ID de la conversación desde la URL
$conversation_id = $_GET['id'];

// Comprobar si el usuario actual es el comprador o el vendedor
$user_id = $_SESSION['userid'];

$stmt = $conn->prepare("SELECT offer_id, seller_id, buyer_id FROM conversations WHERE ID = ?");
$stmt->bind_param("i", $conversation_id);
$stmt->execute();
$stmt->bind_result($offer_id, $seller_id, $buyer_id);
$stmt->fetch();
$stmt->close();

$user_type = '';
if ($user_id == $buyer_id) {
    $user_type = 'buyer';
    $status_message= "You have taken the offer";
} elseif ($user_id == $seller_id) {
    $user_type = 'seller';
    $status_message= "Your offer has been taken";
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        sendMessage($conversation_id, $user_type, $message);
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Oferta</title>
    <link rel="stylesheet" href="assets/css/styleslucas2.css">
    <style>
        .offer-details-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .offer-details, .offer-status, .chat-area {
            background-color: var(--secondary-background);
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .offer-details {
            flex: 2;
        }
        .offer-status {
            flex: 1;
        }
        .chat-area {
            flex: 2;
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid var(--primary-color);
            padding: 10px;
            margin-bottom: 10px;
        }
        .chat-input {
            display: flex;
        }
        .chat-input input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid var(--primary-color);
            border-radius: 5px 0 0 5px;
        }
        .chat-input button {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .status-tracker {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.status-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.status-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--primary-color);
    margin-bottom: 5px;
}

.status-step.completed .status-icon {
    background-color: var(--primary-color);
}

.status-step p {
    text-align: center;
    font-size: 0.9em;
    color: var(--text-color);
}

.status-message {
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

.status-instructions {
    text-align: center;
    font-size: 0.9em;
    margin-bottom: 20px;
}

.status-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-cancel, .btn-mark-paid {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.btn-cancel {
    background-color: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-mark-paid {
    background-color: var(--primary-color);
    color: white;
}

.chat-header {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid var(--primary-color);
    margin-bottom: 10px;
}

.profile-picture {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-info .username {
    font-size: 1.1em;
    font-weight: bold;
    margin: 0;
}

.status-container {
    display: flex;
    align-items: center;
    font-size: 0.9em;
    color: var(--text-color);
}

.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}

.status-indicator.online {
    background-color: var(--primary-color);
}

.status-indicator.offline {
    border: 1px solid var(--primary-color);
}

.chat-message {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
}

.chat-message.outgoing {
    align-items: flex-end;
}

.chat-message.incoming {
    align-items: flex-start;
}

.message-content {
    background-color: #670AB9;
    color: white;
    padding: 10px;
    border-radius: 10px;
    max-width: 60%;
    word-wrap: break-word;
}

.chat-message.outgoing .message-content {
    border-bottom-right-radius: 0;
}

.chat-message.incoming .message-content {
    border-bottom-left-radius: 0;
}

.chat-message .timestamp {
    font-size: 0.8em;
    color: var(--text-color);
    margin-top: 5px;
}
    </style>
    <script>
        function updateChatBox() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'php/update_chat.php?id=<?php echo $conversation_id; ?>&user_type=<?php echo $user_type; ?>', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('chat-box').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        setInterval(updateChatBox, 1000);

        function sendMessage() {
            var messageInput = document.getElementById('message');
            var message = messageInput.value;
            if (message.trim() !== '') {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'taken_offer.php?id=<?php echo $conversation_id; ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        updateChatBox();
                        messageInput.value = '';
                    }
                };
                xhr.send('message=' + encodeURIComponent(message));
            }
        }
    </script>
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
        <h1 class="section-title"><?php $status_message; ?></h1>
        <div class="offer-details-container">
            <div class="offer-details">
                <h2 class="description-title">Offer details</h2>
                <p>Seller offers: <span id="seller-offers"></span></p>
                <p>You pay: <span id="pay-amount"></span></p>
                <p>Lightning invoice: 
                    <!-- En el formulario de `taken_offer.php` -->
                    <form id="invoiceForm" method="post" action="lightning/submit_invoice.php">
                        <label for="ln_invoice">Proporcione su factura Lightning:</label>
                        <input type="text" id="ln_invoice" name="ln_invoice" required>
                        <button type="submit">Enviar Factura</button>
                    </form>

                <span id="ln-invoice"></span></p>
            </div>
            <div class="offer-status">
                <h2 class="description-title">Offer status</h2>
                <div class="status-tracker">
                    <div class="status-step completed">
                        <div class="status-icon"></div>
                        <p>Crypto in escrow</p>
                    </div>
                    <div class="status-step">
                        <div class="status-icon"></div>
                        <p>Service completed</p>
                    </div>
                    <div class="status-step">
                        <div class="status-icon"></div>
                        <p>Sats released</p>
                    </div>
                </div>
        
    <p class="status-message">Keep in touch with the seller</p>
    <p class="status-instructions">To continue completing the service under the established conditions and mark as paid</p>
    <div class="status-buttons">
        <button class="btn-cancel">Cancel</button>
        <button class="btn-mark-paid">Mark as paid</button>
    </div>
</div>
        </div>
<div class="chat-area">
    <div class="chat-header">
        <div class="user-info">
            <h3 class="username">Live chat with: Nombre del usuario</h3>
            <div class="status-container">
                <span class="status-indicator online"></span>
                <p class="status">Online</p>
            </div>
        </div>
    </div>
    <div class="chat-box" id="chat-box">
        <?php echo updateChatBox($conversation_id, $user_type); ?>
    </div>

    <div class="chat-input">
        <input type="text" id="message" placeholder="Write your message...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>
    </div>

</body>
</html>