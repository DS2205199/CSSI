<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'ID dell'utente è stato passato
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Esegue la query per ottenere lo stato attuale dell'utente
    $query = "SELECT attivo FROM appartenenze WHERE ID = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $attivo = $row['attivo'];

        // Inverte lo stato dell'utente (0 diventa 1 e viceversa)
        $nuovoStato = $attivo == '0' ? '1' : '0';

        // Esegue la query per aggiornare lo stato dell'utente
        $query = "UPDATE appartenenze SET attivo = '$nuovoStato' WHERE ID = '$id'";
        $conn->query($query);
    }
}

// Reindirizza alla pagina della tabella degli utenti
header("Location: /amministrazione/tabellappartenenza.php");
exit;
?>
