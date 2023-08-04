<?php
require_once '../config.php';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Connessione al database (assumendo che tu abbia già il codice di connessione)

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
// Connessione al database (assumendo che tu abbia già il codice di connessione)

// Esegui la query per selezionare i dati
$sql = "SELECT * FROM volontari";
$result = $conn->query($sql);

// Definisci il nome del file TXT
$filename = "volontari.txt";

// Genera il contenuto del file TXT
$txt_content = "";

// Scrive i dati nel file TXT
while ($row = $result->fetch_assoc()) {
    $txt_content .= "ID: " . $row["ID"] . "\n";
    $txt_content .= "Codice Fiscale: " . $row["codicefiscale"] . "\n";
    $txt_content .= "Cognome: " . $row["cognome"] . "\n";
    $txt_content .= "Nome: " . $row["nome"] . "\n";
    $txt_content .= "Email: " . $row["email"] . "\n";
    $txt_content .= "Ruolo: " . $row["ruolo"] . "\n";
    $txt_content .= "Telefono: " . $row["telefono"] . "\n";
    $txt_content .= "Sesso: " . $row["sesso"] . "\n";
    $txt_content .= "Registrato da: " . $row["registratoda"] . "\n";
    $txt_content .= "Online: " . $row["stato"] . "\n";
    $txt_content .= "---------------------------------------\n";
}

// Scrive il contenuto nel file TXT
file_put_contents($filename, $txt_content);

// Imposta gli header per il download del file
header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=$filename");

// Legge e mostra il contenuto del file TXT
readfile($filename);

// Termina lo script per evitare l'output aggiuntivo
exit();
?>
