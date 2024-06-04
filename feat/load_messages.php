<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "live_chat";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT messages.id, messages.message, messages.created_at, users.username 
        FROM messages 
        JOIN users ON messages.user_id = users.id 
        ORDER BY messages.created_at DESC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['id'],
            'username' => $row['username'],
            'text' => $row['message'],
            'time' => date('H:i', strtotime($row['created_at']))
        ];
    }
}

echo json_encode(['messages' => $messages]);

$conn->close();
?>
