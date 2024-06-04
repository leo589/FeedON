<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "live_chat";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
</head>
<body>
    <div id="live-chat">
        <header class="clearfix">
            <a href="#" class="chat-close">x</a>
            <h4><?php echo htmlspecialchars($username); ?></h4>
            <span class="chat-message-counter">0</span>
        </header>
        <div class="chat">
            <div class="chat-history" id="chat-history">
                <!-- Messages will be loaded here by JavaScript -->
            </div>
            <p class="chat-feedback">...</p>
            <form action="#" method="post" id="chat-form">
                <fieldset>
                    <input type="text" placeholder="Mesajınızı Yazın" autofocus id="message" required>
                </fieldset>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
