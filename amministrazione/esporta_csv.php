<?php
require_once '../config.php';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Connessione al database (assumendo che tu abbia già il codice di connessione)

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Esegui la query per selezionare i dati
$sql = "SELECT * FROM volontari";
$result = $conn->query($sql);

// Definisci l'intestazione del file CSV
$filename = "volontari.csv";
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filename");

// Apre il file CSV in output per la scrittura
$fp = fopen('php://output', 'w');

// Scrive l'intestazione del CSV
$header = array("ID", "Codice Fiscale", "Cognome", "Nome", "Email", "Ruolo", "Telefono", "Sesso", "Registrato da", "Online");
fputcsv($fp, $header);

// Scrive i dati del CSV
while ($row = $result->fetch_assoc()) {
    $data = array(
        $row["ID"],
        $row["codicefiscale"],
        $row["cognome"],
        $row["nome"],
        $row["email"],
        $row["ruolo"],
        $row["telefono"],
        $row["sesso"],
        $row["registratoda"],
        $row["stato"]
    );
    fputcsv($fp, $data);
}

// Chiude il file CSV
fclose($fp);

// Termina lo script per evitare l'output aggiuntivo
exit();
?>
