<?php
session_start();

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "cssi";

// Connessione al database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
  die("Connessione al database fallita: " . $conn->connect_error);
}

$ID = $_SESSION['ID'];

// Aggiorna lo stato a offline nel database
$sql = "UPDATE volontari SET stato = 0 WHERE ID = '$ID'";
$conn->query($sql);

// Elimina la sessione e reindirizza al login
session_unset();
session_destroy();
header("Location: /index.php");
exit();
?>