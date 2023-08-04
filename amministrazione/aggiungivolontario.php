<?php
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

require_once '../config.php';
// Avvia la sessione prima di qualsiasi output
session_start();

// Verifica se l'utente ha effettuato l'accesso
if (!isset($_SESSION["email"])) {
    header("Location: /index.php");
    exit();
}


$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

$ID = $_SESSION["ID"];

// Aggiorna lo stato a "online" nel database
$sql = "UPDATE volontari SET stato = 1 WHERE ID = '$ID'";
$conn->query($sql);

// Recupera lo stato online degli utenti dalla tabella
$sql = "SELECT ID, stato FROM volontari";
$result = $conn->query($sql);

// Verifica se la variabile di sessione dell'ultimo accesso è impostata
if (isset($_SESSION["last_activity"])) {
    // Imposta il limite di inattività desiderato in secondi (ad esempio, 30 minuti)
    $inactivity_limit = 10 * 60; // 10 minuti in secondi
    // Calcola il tempo trascorso dall'ultimo accesso
    $elapsed_time = time() - $_SESSION["last_activity"];
    // Verifica se il tempo trascorso supera il limite di inattività
    if ($elapsed_time > $inactivity_limit) {
        // Disconnetti l'utente e reindirizza alla pagina di login
        session_destroy();
        header("Location: /index.php");
        exit();
    }
}



// Recupera il nome e il cognome dell'utente dalle variabili di sessione
$ID = $_SESSION["ID"];
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$ruolo = $_SESSION["ruolo"];
$registratoda = $_SESSION["registratoda"];
$codicefiscale = $_SESSION["codicefiscale"];
$datadinascita = $_SESSION["datadinascita"];
$email = $_SESSION["email"];
$comunedinascita = $_SESSION["comunedinascita"];
$provinciadinascita = $_SESSION["provinciadinascita"];
$statodinascita = $_SESSION["statodinascita"];
$indirizzodiresidenza = $_SESSION["indirizzodiresidenza"];
$comunediresidenza = $_SESSION["comunediresidenza"];
$provinciadiresidenza = $_SESSION["provinciadiresidenza"];
$statodiresidenza = $_SESSION["statodiresidenza"];
$capdiresidenza = $_SESSION["capdiresidenza"];
$telefono = $_SESSION["telefono"];
$ruolo = $_SESSION["ruolo"];
$sesso = $_SESSION["sesso"];
$registratoda = $_SESSION["registratoda"];
$note = $_SESSION["note"];

// Aggiorna il timestamp dell'ultimo accesso
$_SESSION["last_activity"] = time();

$conn = mysqli_connect("localhost", "root", "", "cssi");
if (!$conn) {
    die("Error connecting to database: " . mysqli_connect_error());
}

// Controlla il ruolo dell'utente corrente.
if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'Presidente' || $_SESSION['ruolo'] === 'Segretaria') {
    // L'utente è un Presidente e può visualizzare la pagina.
} else {
    // L'utente non è un presidente e non può visualizzare la pagina.
    echo '<div class="alert alert-danger" role="alert">
     <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Accesso Negato</title>
  <link rel="stylesheet" href="../CSS/403forbiden.css">
</head>
<body>
<center><div class="lock"></div></center>
<div class="message">
  <center><h1>Accesso Negato</h1></center>
  <center> <p> Mi dispiace, ma non sei autorizzato a inserire volontari in questa pagina. Se hai bisogno di assistenza o se per te e un errore, contatta amministratore del sistema.</p></center>
  <br>
  <center> <p> La tua richiesta è stata registrata e sarà inoltrata all amministratore del sistema per ulteriori indagini.</p></center>
  <br>
  <center> <p>Il tuo Email, Cognome, Nome, e Indirizzo IP sono stati registrati per motivi di sicurezza.</p></center>
  <br>
  <center> <p> Grazie per la tua comprensione. </p></center>
</div>
</body>
</html>
    </div>';
	$timestamp = date("Y-m-d H:i:s");
    $nomePagina = $_SERVER['REQUEST_URI'];
    $indirizzoIP = $_SERVER['REMOTE_ADDR'];
	
	// Inserisci il nome della pagina nella tabella di accessi
    $query = "INSERT INTO accessi (email, cognome, nome, timestamp, NomePagina, indirizzo_ip, accesso_negato) 
    VALUES ('$email', '$cognome', '$nome', '$timestamp', '$nomePagina','$indirizzoIP', 1)";
    mysqli_query($conn, $query);
    exit();
}

// Ottieni il nome della pagina corrente
$pageName = $_SERVER['REQUEST_URI'];

// Connetti al database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Inserisci il nome della pagina nella tabella degli accessi
$indirizzo_ip = $_SERVER['REMOTE_ADDR'];
$accesso_negato = false;

// Utilizza una query preparata con MySQLi per evitare SQL injection
$stmt = $conn->prepare('INSERT INTO accessi (email, cognome, nome, timestamp, NomePagina, indirizzo_ip, accesso_negato) VALUES (?, ?, ?, NOW(), ?, ?, ?)');
$stmt->bind_param('sssssi', $email, $cognome, $nome, $pageName, $indirizzo_ip, $accesso_negato);
$stmt->execute();
$stmt->close();

?>


<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- TITOLO DELLA PAGINA -->
    <title>Pagina di Amministrazione - Aggiungi Volontario</title>
	<!------------------------------------------------------------------------>
    <!-- CSS ESTERNI -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
	
    <!------------------------------------------------------------------------>
     <!-- FONT -->
    <link href="https://it.allfont.net/allfont.css?fonts=capture-it" rel="stylesheet" type="text/css" />
    <!------------------------------------------------------------------------>
    <!-- ICONE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!------------------------------------------------------------------------>
    <!-- CSS INTERNI -->

    <link href="../css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet"></head>

    <!------------------------------------------------------------------------>
	<style>
  
</style>

<!------------------------------------------------------------------------>
<!------------------------------------------------------------------------>
	
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <!-- Contenuto della sidebar -->
        <div class="sidebar-title">
          <!-- Aggiungi qui il titolo della sidebar -->
        </div>
        <ul class="nav flex-column">
          <!-- Aggiungi qui le voci del menu della sidebar -->
          
		  <br>
		  <center><h3>CSSI</h3></center> 
		  <hr>
		  
		  <!-- INZIO HTML - MOSTRA NOME COGNOME E RUOLO -->
		  <ul class="nav flex-column">
		  <li class="nav-item">
  <b><center><a class="nav-link"><?php echo "Benvenuto, $cognome $nome" ?></a></center></b> 
  </li>
          
  <li class="nav-item">
  <b><center><a class="nav-link"><?php echo "$ruolo" ?></a></center></b>
  <hr> </li>
  
<!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU ANAGRAFICA VOLONTARI -->  

		<li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneAnagraficaMenu" role="button" data-toggle="collapse" data-target="#gestioneAnagraficaSubMenu" aria-expanded="false" aria-controls="gestioneAnagraficaSubMenu">
  Gestione Anagrafica </a>
            
  <div class="collapse" id="gestioneAnagraficaSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellavolontari.php">Tabella Volontari</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungivolontario.php">Inserisci Volontario</a>
  </li> </ul> </div> </li>
  
  
		
         <!-- HTML FINE MENU ANAGRAFICA VOLONTARI -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU PAGAMENTI -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestionePagamentoMenu" role="button" data-toggle="collapse" data-target="#gestionePagamentoSubMenu" aria-expanded="false" aria-controls="gestionePagamentoSubMenu">
  Gestione Pagamento </a>
            
  <div class="collapse" id="gestionePagamentoSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellapagamenti.php">Tabella Pagamenti</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungipagamento.php">Aggiungi Pagamento</a>
  </li> </ul> </div> </li>
  
  <!-- HTML FINE MENU PAGAMENTI -->
  <!------------------------------------------------------------------------>
  <!-- HTML INIZIO MENU ATTIVITA -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneAttivitaMenu" role="button" data-toggle="collapse" data-target="#gestioneAttivitaSubMenu" aria-expanded="false" aria-controls="gestioneAttivitaSubMenu">
  Gestione Attività </a>
  
  <div class="collapse" id="gestioneAttivitaSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellattivita.php">Tabella Attività</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="insertattivita.php">Inserisci Attività</a>
  </li> </ul> </div> </li>

  <!-- HTML FINE MENU ATTIVITA -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU DOCUMENTI -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneDocumentiMenu" role="button" data-toggle="collapse" data-target="#gestioneDocumentiSubMenu" aria-expanded="false" aria-controls="gestioneDocumentiSubMenu">
  Gestione Documenti </a>
            
  <div class="collapse" id="gestioneDocumentiSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabelladocumenti.php">Tabella Documenti</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungidocumento.php">Aggiungi Documenti</a>
  </li> </ul> </div> </li>
	
  <!-- HTML FINE MENU DOCUMENTI -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU ESTENSIONI -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneEstensioniMenu" role="button" data-toggle="collapse" data-target="#gestioneEstensioniSubMenu" aria-expanded="false" aria-controls="gestioneEstensioniSubMenu">
  Gestione Estensioni </a>
            
  <div class="collapse" id="gestioneEstensioniSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellappartenenza.php">Tabella Estensioni</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungiestensioni.php">Inserisci Estensioni</a>
  </li> </ul> </div> </li>
  
  <!-- HTML FINE MENU ESTENSIONI -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU TRASFERIMENTI -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneTrasferimentiMenu" role="button" data-toggle="collapse" data-target="#gestioneTrasferimentiSubMenu" aria-expanded="false" aria-controls="gestioneTrasferimentiSubMenu">
  Gestione Trasferimento </a>
            
  <div class="collapse" id="gestioneTrasferimentiSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellatrasferimenti.php">Tabella Trasferimento</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungitrasferimento.php">Inserisci Trasferimento</a>
  </li> </ul> </div> </li>
  
  <!-- HTML FINE MENU TRASFERIMENTI -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU RISERVE -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="gestioneRiserveMenu" role="button" data-toggle="collapse" data-target="#gestioneRiserveSubMenu" aria-expanded="false" aria-controls="gestioneRiserveSubMenu">
  Gestione Riserve </a>
  
  <div class="collapse" id="gestioneRiserveSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellariserve.php">Tabella Riserve</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungiriserva.php">Inserisci Riserva</a>
  </li> </ul> </div> </li>
  
  <!-- HTML FINE MENU RISERVE -->
  <!------------------------------------------------------------------------>

  <li class="nav-item">
  <hr>

  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU VIDEO -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="GestioneMenuVideo" role="button" data-toggle="collapse" data-target="#gestioneVideoSubMenu" aria-expanded="false" aria-controls="gestioneVideoSubMenu">
  Gestione dei Video </a>
  
  <div class="collapse" id="gestioneVideoSubMenu">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellavideo.php">Visualizza Tabella Video</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="aggiungivideo.php">Inserisci un Nuovo Video</a>
  </li> </ul> </div> </li>
  
  <!-- HTML FINE MENU VIDEO -->
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU NEWS -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="GestioneMenuNews" role="button" data-toggle="collapse" data-target="#gestioneMenuSubNews" aria-expanded="false" aria-controls="gestioneMenuSubNews">
  Gestione News </a>
  
  <div class="collapse" id="gestioneMenuSubNews">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellanews.php">Visualizza Tabella News</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="inseriscinews.php">Inserisci una nuova News</a>
  </li> </ul> </div> </li>

<!-- HTML FINE MENU NEWS -->
  <!------------------------------------------------------------------------>

  <li class="nav-item">
  <hr>
  
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU LOG DEGLI ACCESSI -->

  <li class="nav-item">
  <a class="nav-link dropdown-toggle" href="#" id="GestioneMenuAccessi" role="button" data-toggle="collapse" data-target="#GestioneMenuSubAccessi" aria-expanded="false" aria-controls="GestioneMenuSubAccessi">
  Log degli Accessi </a>
  
  <div class="collapse" id="GestioneMenuSubAccessi">
  <ul class="nav flex-column">
  <li class="nav-item">
  <a class="nav-link" href="tabellaccessi.php">Visualizza Log degli Accessi</a>
  </li> </ul> </div> </li>

  </ul> </div> </nav> </div> </div>
   <!-- HTML FINE MENU LOG DEGLI ACCESSI -->
   <!------------------------------------------------------------------------>
   <!------------------------------------------------------------------------>

<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link text-white" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white" href="">Oggi è: <span id="current-time"> </a>

      </li>
	  <li class="nav-item">
        
      </li>
    </ul>

    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <?php echo "Benvenuto $ruolo, $cognome, $nome"; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="profile.php">Profilo</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Esci</a>
        </div>
      </li>
    </ul>
  </div>
</nav>




  <!------------------------------------------------------------------------>
  <!-- HTML INIZIO ALERT INFORMAZIONI -->
  <main role="main" class="col-md-5 ml-sm-auto col-lg-10 content">
  
  <div class="alert alert-info" role="alert">
  <i class="fa fa-info-circle" aria-hidden="true"></i>
      
  <!-- HTML FINE ALERT INFORMAZIONI -->
  <!------------------------------------------------------------------------>
  <!-- INZIO PHP ALERT INFORMAZIONI -->
<?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Verifica la connessione
  if ($conn->connect_error) {
  die("Connessione al database fallita: " . $conn->connect_error); }

  // Recupera l'ultima informazione dal database
  $sql = "SELECT cognome, nome, dataeora, info, link FROM informazioni ORDER BY id DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $info = $row["info"];
  $cognome = $row["cognome"];
  $nome = $row["nome"];
  $dataOra = $row["dataeora"];
  $link = $row["link"];

    echo $info . ":  " . (!empty($link) ? " <a href='" . $link . "'>" . basename(parse_url($link, PHP_URL_PATH)) . "</a>" : "");
    echo "<hr>";
    echo "Cognome: " . $cognome . " - Nome: " . $nome . " - Data e Ora: " . $dataOra . "<br>";
  } else {
    echo "Nessuna informazione disponibile."; }

  $conn->close();
  ?> </div>
  
  <!-- FINE PHP ALERT INFORMAZIONI -->  
  
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
  <!-- MENU A TENDINA -->
  
<?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Esecuzione della query per ottenere i dati dal database
$sql = "SELECT sesso FROM sesso";
$result = $conn->query($sql);

$options = '';

if ($result->num_rows > 0) {
  // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina
  while ($row = $result->fetch_assoc()) {
      $sesso = $row["sesso"];
      $options .= "<option value='$sesso'>$sesso</option>";
  }
}

// Esecuzione della query per ottenere i dati dal database
$sqlruolo = "SELECT ruolo FROM ruoli";
$resultruolo = $conn->query($sqlruolo);

$optionsruolo = '';

if ($resultruolo->num_rows > 0) {
  // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina
  while ($ruolorow = $resultruolo->fetch_assoc()) {
      $ruolo = $ruolorow["ruolo"];
      $optionsruolo .= "<option value='$ruolo'>$ruolo</option>";
  }
}

// Esecuzione della query per ottenere i dati dal database
$sql = "SELECT anno FROM anni";
$result = $conn->query($sql);

$options = '';

if ($result->num_rows > 0) {
  // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina
  while ($row = $result->fetch_assoc()) {
      $anno = $row["anno"];
      $options .= "<option value='$anno'>$anno</option>";
  }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlDescrizioni = "SELECT descrizione FROM descrizione";
$resultDescrizioni = $conn->query($sqlDescrizioni);

$optionsDescrizioni = '';

if ($resultDescrizioni->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($rowDescrizioni = $resultDescrizioni->fetch_assoc()) {
        $descrizione = $rowDescrizioni["descrizione"];
        $optionsDescrizioni .= "<option value='$descrizione'>$descrizione</option>";
    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlnomecognome = "SELECT cognome,nome FROM volontari";
$resultnomecognome = $conn->query($sqlnomecognome);

$optionsnomecognome = '';

if ($resultnomecognome->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($rownomecognome = $resultnomecognome->fetch_assoc()) {
        $cognome = $rownomecognome["cognome"];
        $optionsnomecognome .= "<option value='$cognome'>$cognome</option>";

    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlnome = "SELECT nome FROM volontari";
$resultnome = $conn->query($sqlnome);

$optionsnome = '';

if ($resultnome->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($rownome = $resultnome->fetch_assoc()) {
        $nome = $rownome["nome"];
        $optionsnome .= "<option value='$nome'>$nome</option>";

    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlsesso = "SELECT sesso FROM sesso";
$resultsesso = $conn->query($sqlsesso);

$optionssesso = '';

if ($resultsesso->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($rowsesso = $resultsesso->fetch_assoc()) {
        $sesso = $rowsesso["sesso"];
        $optionssesso .= "<option value='$sesso'>$sesso</option>";

    }
}

$conn->close();
?>

  <!-- FINE MENU A TENDINA -->
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  <!-- INZIO PHP INSERIMENTO SU TABELLA VOLONTARI -->
  
  <?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Controllo dei privilegi
if (!isset($_SESSION['ruolo']) || ($_SESSION['ruolo'] !== 'Presidente' && $_SESSION['ruolo'] !== 'Segretaria')) {
    echo '<div class="alert alert-danger" role="alert">';
    echo "Non hai i privilegi necessari per aggiornare i dati dei Volontari. Si prega di contattare amministratore per ulteriori informazioni.";
    echo '</div>';
    $conn->close();
    exit();
}

// Controllo se il modulo di aggiornamento è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include il file di validazione
    $errors = require '../include/validazioni/validazionevolontario.php';

    // Verifica se sono presenti errori
    if (count($errors) > 0) {
        // Visualizza gli errori
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $error;
            echo '</div>';
        }
    } else {
    // Recupero dei dati dal form
    $codicefiscale = isset($_POST['codicefiscale']) ? mysqli_real_escape_string($conn, $_POST['codicefiscale']) : '';
    $cognome = isset($_POST['cognome']) ? mysqli_real_escape_string($conn, $_POST['cognome']) : '';
    $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conn, $_POST['nome']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $datadinascita = isset($_POST['datadinascita']) ? mysqli_real_escape_string($conn, $_POST['datadinascita']) : '';
    $pass = isset($_POST['pass']) ? mysqli_real_escape_string($conn, $_POST['pass']) : '';
    $cpass = isset($_POST['cpass']) ? mysqli_real_escape_string($conn, $_POST['cpass']) : '';
    $comunedinascita = isset($_POST['comunedinascita']) ? mysqli_real_escape_string($conn, $_POST['comunedinascita']) : '';
    $provinciadinascita = isset($_POST['provinciadinascita']) ? mysqli_real_escape_string($conn, $_POST['provinciadinascita']) : '';
    $statodinascita = isset($_POST['statodinascita']) ? mysqli_real_escape_string($conn, $_POST['statodinascita']) : '';
    $statodiresidenza = isset($_POST['statodiresidenza']) ? mysqli_real_escape_string($conn, $_POST['statodiresidenza']) : '';
    $indirizzodiresidenza = isset($_POST['indirizzodiresidenza']) ? mysqli_real_escape_string($conn, $_POST['indirizzodiresidenza']) : '';
    $comunediresidenza = isset($_POST['comunediresidenza']) ? mysqli_real_escape_string($conn, $_POST['comunediresidenza']) : '';
    $provinciadiresidenza = isset($_POST['provinciadiresidenza']) ? mysqli_real_escape_string($conn, $_POST['provinciadiresidenza']) : '';
    $capdiresidenza = isset($_POST['capdiresidenza']) ? mysqli_real_escape_string($conn, $_POST['capdiresidenza']) : '';
    $telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conn, $_POST['telefono']) : '';
    $ruolo = isset($_POST['ruolo']) ? mysqli_real_escape_string($conn, $_POST['ruolo']) : '';
    $sesso = isset($_POST['sesso']) ? mysqli_real_escape_string($conn, $_POST['sesso']) : '';
    $registratoda = isset($_POST['registratoda']) ? mysqli_real_escape_string($conn, $_POST['registratoda']) : '';
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : '';
    
    // Recupera il valore del campo "attivo" dal form
    $attivo = isset($_POST['attivo']) && $_POST['attivo'] == 1 ? 1 : 0;

	// Hash the passwords
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
$hashedPassword1 = password_hash($cpass, PASSWORD_BCRYPT);

// Impostare il valore predefinito per il campo "attivo"

// Creazione della query di inserimento
$sql = "INSERT INTO volontari (codicefiscale, cognome, nome, email, datadinascita, pass, cpass, comunedinascita, provinciadinascita, statodinascita, statodiresidenza, indirizzodiresidenza, comunediresidenza, provinciadiresidenza, capdiresidenza, telefono, ruolo, sesso, registratoda, note, attivo)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

// Preparazione dell'istruzione
$stmt = $conn->prepare($sql);

// Associazione dei valori ai parametri, use the hashed password variables
$stmt->bind_param("sssssssssssssssssssss", $codicefiscale, $cognome, $nome, $email, $datadinascita, $hashedPassword, $hashedPassword1, $comunedinascita, $provinciadinascita, $statodinascita,
$statodiresidenza, $indirizzodiresidenza, $comunediresidenza, $provinciadiresidenza, $capdiresidenza, $telefono, $ruolo, $sesso, $registratoda, $note, $attivo);
  	
	  //!------------------------------------------------------------------------>
	  //!------------------------------------------------------------------------>
	require 'vendor/autoload.php';

// Inizializza l'oggetto PHPMailer
$mail = new PHPMailer(true);

    try {
        // Configura il server SMTP (cambia queste impostazioni con quelle del tuo provider di posta)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'csicurezzastradale@gmail.com';
        $mail->Password   = 'oqfzqsrrvfplrzic';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Oppure PHPMailer::ENCRYPTION_SMTPS se necessario
        $mail->Port       = 587; // O la porta corretta del tuo server SMTP

// Imposta il mittente e il destinatario
    $mail->setFrom('csicurezzastradale@gmail.com', 'CSSI');
    $mail->addAddress($email, $nome);

    // Imposta il soggetto dell'email
    $mail->Subject = 'Conferma Iscrizione Volontario';

    // Imposta il corpo dell'email come HTML
    $mail->isHTML(true);

    // Corpo dell'email
    $mail->Body = '
        <p>Gentile ' . $nome . ' '. $cognome .',</p>
        <p>Siamo lieti di confermare la tua iscrizione come volontario presso CSSI. Il tuo impegno è di grande valore per noi e la tua partecipazione è molto apprezzata.</p>
        <p>Di seguito, troverai un riepilogo dei dati forniti al momento dell\'iscrizione:</p>
        <ul>
			<li>Codice Fiscale: ' . $codicefiscale . '</li>
			<li>Cognome: ' . $cognome . '</li>
            <li>Nome: ' . $nome . '</li>
            <li>Email: ' . $email . '</li>
            <li>Comune di Nascita: ' . $comunedinascita . '</li>
            <li>Provincia di Nascita: ' . $provinciadinascita . '</li>
			<li>Stato di Nascita: ' . $statodinascita . '</li>
			<li>Indirizzo di Residenza: ' . $indirizzodiresidenza . '</li>
			<li>Comune di Residenza: ' . $comunediresidenza . '</li>
			<li>Provincia di Residenza: ' . $provinciadiresidenza . '</li>
			<li>Stato di Residenza: ' . $statodiresidenza . '</li>
			<li>CAP di Residenza: ' . $capdiresidenza . '</li>
           <br>
		    <li>Data di Nascita: ' . $datadinascita . '</li>
			<li>Telefono: ' . $telefono . '</li>
			<li>Ruolo: ' . $ruolo . '</li>
			<li>Sesso: ' . $sesso . '</li>
			<li>Registrato Da: ' . $registratoda . '</li>
        </ul>
		
        <p>Ti contatteremo a breve per fornirti ulteriori dettagli sulle attività e sugli eventi in cui potresti essere coinvolto come volontario.</p>
		<p>Nel frattempo, puoi trovare alcune informazioni sul progetto sul nostro sito web</p>
		<p>Siamo convinti che il tuo contributo sarà prezioso per il nostro progetto non vediamo ora di iniziare questa avventura insieme!</p>
        <p>Grazie ancora per la tua dedizione e il tuo sostegno.</p>
        <p>Cordiali saluti,</p>
        <p>Il Team e Presidente di CSSI</p>
		<p>P.S. Se hai domande o hai bisogno di assistenza, non esitare a contattarci!</p>
	';
	
    // Invia l'email
    $mail->send();

    echo '';
} catch (Exception $e) {
    echo ": {$mail->ErrorInfo}";
}
  // Esecuzione della query
  if ($stmt->execute()) {
	  echo '<div id="success-message" class="alert alert-primary" role="alert">'; 
      echo 'Il nuovo volontario è stato correttamente inserito nel sistema!';
      echo '</div>';
    echo '<script>
   setTimeout(function() {
       var successMessage = document.getElementById("success-message");
       successMessage.remove();
	     window.location.replace("tabellavolontari.php");
   }, 1000);
 </script>';
   } else {
      echo '<div class="alert alert-danger" role="alert">';
      echo "Mi scuso per l'inconveniente riscontrato durante l'aggiornamento dei dati.: " . $conn->error;
      echo '</div>';
	}
        $stmt->close();
    }
}
// Chiudi la connessione al database
$conn->close();
?>

  <!-- FINE PHP INSERIMENTO SU TABELLA VOLONTARI -->
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  <!-- INZIO FORM CAMPI -->
   
   <form method="post" action="" enctype="multipart/form-data">
  
  <!-- FORM DI REGISTRAZIONE ATTIVITA -->
  <div class="row">
  <div class="col-lg-10">
  <div class="card w-100 mb-3 dark-profile">
  <div class="card-header"><center>Inserisci Volontario</center></div>
  <div class="card-body">
  <h5 class="card-title"><center><b>Nota importante: I campi contrassegnati con un asterisco (<i class="fa fa-asterisk"></i> ) sono obbligatori. Si prega di compilare tutti i campi richiesti correttamente.</center></h5></b>

    <input type="hidden" name="ID" id="ID">
	
<div class="mb-3">
    <div class="form-group custom-rounded-form-group">
        <label class="form-label">Codice Fiscale <i class="fa fa-asterisk"></i> </label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="codicefiscale" placeholder="Codice Fiscale" class="form-control dark-input" required>
        </div>
    </div>
</div>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

<div class="row">
    <div class="col-lg-6">
        <div class="mb-3">
            <label class="form-label">Cognome <i class="fa fa-asterisk"></i> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="cognome" placeholder="Cognome" class="form-control dark-input" required>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="mb-3">
            <label class="form-label">Nome <i class="fa fa-asterisk"></i> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="nome" placeholder="Nome" class="form-control dark-input" required>
            </div>
        </div>
    </div>
</div>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

<div class="row">
    <div class="col-lg-3">
        <div class="mb-3">
            <label class="form-label">Password <i class="fa fa-asterisk"></i></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                </div>
                <input type="password" name="pass" placeholder="Password" class="form-control dark-input" required>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="mb-3">
            <label class="form-label">Conferma Password <i class="fa fa-asterisk"></i></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                </div>
                <input type="password" name="cpass" placeholder="Conferma Password" class="form-control dark-input" required>
            </div>
        </div>
    </div>
	
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

<div class="col-lg-6">
    <div class="mb-3">
        <label class="form-label">Email <i class="fa fa-asterisk"></i></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
            </div>
            <input type="email" name="email" placeholder="Email" class="form-control dark-input" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
        </div>
    </div>
</div>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

	<div class="col-lg-3">
      <div class="mb-3">
      <label class="form-label">Comune di Nascita <i class="fa fa-asterisk"></i> </label>
	  <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <input type="text" name="comunedinascita" placeholder="Comune di Nascita" class="form-control dark-input" required">
      </div> </div> </div>
	  
	  <div class="col-lg-3">
      <div class="mb-3">
      <label class="form-label">Provincia di Nascita <i class="fa fa-asterisk"></i></label>
	  <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <input type="text" name="provinciadinascita" placeholder="Provincia di Nascita" class="form-control dark-input" required">
      </div> </div> </div>
	   
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
	  <div class="col-lg-6">
      <div class="mb-3">
      <label class="form-label">Stato di Nascita</label>
	  <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <select name="statodinascita" class="form-control dark-input" required id="lang" <i class="fa fa-asterisk"></i>
      <option value="US">United States</option>
    <option value="CA">Canada</option>
    <option value="AF">Afghanistan</option>
    <option value="AL">Albania</option>
    <option value="DZ">Algeria</option>
    <option value="AS">American Samoa</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarctica</option>
    <option value="AG">Antigua and Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenia</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="AZ">Azerbaijan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus</option>
    <option value="BE">Belgium</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BA">Bosnia and Herzegovina</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei Darussalam</option>
    <option value="BG">Bulgaria</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KH">Cambodia</option>
    <option value="CM">Cameroon</option>
    <option value="CV">Cape Verde</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic</option>
    <option value="TD">Chad</option>
    <option value="CL">Chile</option>
    <option value="CN">China</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos (Keeling) Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoros</option>
    <option value="CG">Congo</option>
    <option value="CD">Congo (Democratic Republic)</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="HR">Croatia</option>
    <option value="CU">Cuba</option>
    <option value="CY">Cyprus</option>
    <option value="CZ">Czech Republic</option>
    <option value="DK">Denmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="TP">East Timor</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea</option>
    <option value="ER">Eritrea</option>
    <option value="EE">Estonia</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland</option>
    <option value="FR">France</option>
    <option value="FX">France (European Territory)</option>
    <option value="GF">French Guiana</option>
    <option value="TF">French Southern Territories</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia</option>
    <option value="DE">Germany</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HM">Heard and McDonald Islands</option>
    <option value="VA">Holy See (Vatican)</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary</option>
    <option value="IS">Iceland</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran</option>
    <option value="IQ">Iraq</option>
    <option value="IE">Ireland</option>
    <option value="IL">Israel</option>
    <option value="IT"selected="selected">Italy</option>
    <option value="CI">Cote D&rsquo;Ivoire</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="JO">Jordan</option>
    <option value="KZ">Kazakhstan</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KW">Kuwait</option>
    <option value="KG">Kyrgyzstan</option>
    <option value="LA">Laos</option>
    <option value="LV">Latvia</option>
    <option value="LB">Lebanon</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libya</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania</option>
    <option value="LU">Luxembourg</option>
    <option value="MO">Macau</option>
    <option value="MK">Macedonia</option>
    <option value="MG">Madagascar</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexico</option>
    <option value="FM">Micronesia</option>
    <option value="MD">Moldova</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia</option>
    <option value="ME">Montenegro</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco</option>
    <option value="MZ">Mozambique</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NP">Nepal</option>
    <option value="NL">Netherlands</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="KP">North Korea</option>
    <option value="MP">Northern Mariana Islands</option>
    <option value="NO">Norway</option>
    <option value="OM">Oman</option>
    <option value="PK">Pakistan</option>
    <option value="PW">Palau</option>
    <option value="PS">Palestinian Territory</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PH">Philippines</option>
    <option value="PN">Pitcairn</option>
    <option value="PL">Poland</option>
    <option value="PF">Polynesia</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania</option>
    <option value="RU">Russian Federation</option>
    <option value="RW">Rwanda</option>
    <option value="GS">S. Georgia &amp; S. Sandwich Isls.</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts &amp; Nevis Anguilla</option>
    <option value="LC">Saint Lucia</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent &amp; Grenadines</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">Sao Tome and Principe</option>
    <option value="SA">Saudi Arabia</option>
    <option value="SN">Senegal</option>
    <option value="RS">Serbia</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SK">Slovakia</option>
    <option value="SI">Slovenia</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia</option>
    <option value="ZA">South Africa</option>
    <option value="KR">South Korea</option>
    <option value="ES">Spain</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan</option>
    <option value="SR">Suriname</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="SY">Syrian Arab Republic</option>
    <option value="TW">Taiwan</option>
    <option value="TJ">Tajikistan</option>
    <option value="TZ">Tanzania</option>
    <option value="TH">Thailand</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia</option>
    <option value="TR">Turkey</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine</option>
    <option value="AE">United Arab Emirates</option>
    <option value="GB">United Kingdom</option>
    <option value="UY">Uruguay</option>
    <option value="UM">USA Minor Outlying Islands</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Vietnam</option>
    <option value="VG">Virgin Islands (British)</option>
    <option value="VI">Virgin Islands (USA)</option>
    <option value="WF">Wallis and Futuna Islands</option>
    <option value="EH">Western Sahara</option>
    <option value="YE">Yemen</option>
    <option value="ZR">Zaire</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
    </select> </div> </div> </div>
	
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
	<div class="col-lg-6">
    <div class="mb-3">
    <label class="form-label">Indirizzo di Residenza <i class="fa fa-asterisk"></i> </label>
	 <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
    <input type="text" name="indirizzodiresidenza" placeholder="Indirizzo di Residenza" class="form-control dark-input" required">
    </div> </div>  </div>
	

 <div class="col-lg-3">
    <div class="mb-3">
    <label class="form-label">Comune di Residenza <i class="fa fa-asterisk"></i> </label>
	<div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <input type="text" name="comunediresidenza" placeholder="Comune di Residenza" class="form-control dark-input" required">
    </div>
  </div>
  </div>
  
   <div class="col-lg-3">
    <div class="mb-3">
    <label class="form-label">Provincia di Residenza <i class="fa fa-asterisk"></i> </label>
	<div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <input type="text" name="provinciadiresidenza" placeholder="Provincia di Residenza" class="form-control dark-input" required">
    </div>
  </div>
  </div>
  
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
  <div class="col-lg-6">
    <div class="mb-3">
    <label class="form-label">Stato di Residenza</label>
	<div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
      <select name="statodiresidenza" class="form-control dark-input" required id="lang" <i class="fa fa-asterisk"></i>>
      <option value="US">United States</option>
    <option value="CA">Canada</option>
    <option value="AF">Afghanistan</option>
    <option value="AL">Albania</option>
    <option value="DZ">Algeria</option>
    <option value="AS">American Samoa</option>
    <option value="AD">Andorra</option>
    <option value="AO">Angola</option>
    <option value="AI">Anguilla</option>
    <option value="AQ">Antarctica</option>
    <option value="AG">Antigua and Barbuda</option>
    <option value="AR">Argentina</option>
    <option value="AM">Armenia</option>
    <option value="AW">Aruba</option>
    <option value="AU">Australia</option>
    <option value="AT">Austria</option>
    <option value="AZ">Azerbaijan</option>
    <option value="BS">Bahamas</option>
    <option value="BH">Bahrain</option>
    <option value="BD">Bangladesh</option>
    <option value="BB">Barbados</option>
    <option value="BY">Belarus</option>
    <option value="BE">Belgium</option>
    <option value="BZ">Belize</option>
    <option value="BJ">Benin</option>
    <option value="BM">Bermuda</option>
    <option value="BT">Bhutan</option>
    <option value="BO">Bolivia</option>
    <option value="BA">Bosnia and Herzegovina</option>
    <option value="BW">Botswana</option>
    <option value="BV">Bouvet Island</option>
    <option value="BR">Brazil</option>
    <option value="IO">British Indian Ocean Territory</option>
    <option value="BN">Brunei Darussalam</option>
    <option value="BG">Bulgaria</option>
    <option value="BF">Burkina Faso</option>
    <option value="BI">Burundi</option>
    <option value="KH">Cambodia</option>
    <option value="CM">Cameroon</option>
    <option value="CV">Cape Verde</option>
    <option value="KY">Cayman Islands</option>
    <option value="CF">Central African Republic</option>
    <option value="TD">Chad</option>
    <option value="CL">Chile</option>
    <option value="CN">China</option>
    <option value="CX">Christmas Island</option>
    <option value="CC">Cocos (Keeling) Islands</option>
    <option value="CO">Colombia</option>
    <option value="KM">Comoros</option>
    <option value="CG">Congo</option>
    <option value="CD">Congo (Democratic Republic)</option>
    <option value="CK">Cook Islands</option>
    <option value="CR">Costa Rica</option>
    <option value="HR">Croatia</option>
    <option value="CU">Cuba</option>
    <option value="CY">Cyprus</option>
    <option value="CZ">Czech Republic</option>
    <option value="DK">Denmark</option>
    <option value="DJ">Djibouti</option>
    <option value="DM">Dominica</option>
    <option value="DO">Dominican Republic</option>
    <option value="TP">East Timor</option>
    <option value="EC">Ecuador</option>
    <option value="EG">Egypt</option>
    <option value="SV">El Salvador</option>
    <option value="GQ">Equatorial Guinea</option>
    <option value="ER">Eritrea</option>
    <option value="EE">Estonia</option>
    <option value="ET">Ethiopia</option>
    <option value="FK">Falkland Islands</option>
    <option value="FO">Faroe Islands</option>
    <option value="FJ">Fiji</option>
    <option value="FI">Finland</option>
    <option value="FR">France</option>
    <option value="FX">France (European Territory)</option>
    <option value="GF">French Guiana</option>
    <option value="TF">French Southern Territories</option>
    <option value="GA">Gabon</option>
    <option value="GM">Gambia</option>
    <option value="GE">Georgia</option>
    <option value="DE">Germany</option>
    <option value="GH">Ghana</option>
    <option value="GI">Gibraltar</option>
    <option value="GR">Greece</option>
    <option value="GL">Greenland</option>
    <option value="GD">Grenada</option>
    <option value="GP">Guadeloupe</option>
    <option value="GU">Guam</option>
    <option value="GT">Guatemala</option>
    <option value="GN">Guinea</option>
    <option value="GW">Guinea Bissau</option>
    <option value="GY">Guyana</option>
    <option value="HT">Haiti</option>
    <option value="HM">Heard and McDonald Islands</option>
    <option value="VA">Holy See (Vatican)</option>
    <option value="HN">Honduras</option>
    <option value="HK">Hong Kong</option>
    <option value="HU">Hungary</option>
    <option value="IS">Iceland</option>
    <option value="IN">India</option>
    <option value="ID">Indonesia</option>
    <option value="IR">Iran</option>
    <option value="IQ">Iraq</option>
    <option value="IE">Ireland</option>
    <option value="IL">Israel</option>
    <option value="IT"selected="selected">Italy</option>
    <option value="CI">Cote D&rsquo;Ivoire</option>
    <option value="JM">Jamaica</option>
    <option value="JP">Japan</option>
    <option value="JO">Jordan</option>
    <option value="KZ">Kazakhstan</option>
    <option value="KE">Kenya</option>
    <option value="KI">Kiribati</option>
    <option value="KW">Kuwait</option>
    <option value="KG">Kyrgyzstan</option>
    <option value="LA">Laos</option>
    <option value="LV">Latvia</option>
    <option value="LB">Lebanon</option>
    <option value="LS">Lesotho</option>
    <option value="LR">Liberia</option>
    <option value="LY">Libya</option>
    <option value="LI">Liechtenstein</option>
    <option value="LT">Lithuania</option>
    <option value="LU">Luxembourg</option>
    <option value="MO">Macau</option>
    <option value="MK">Macedonia</option>
    <option value="MG">Madagascar</option>
    <option value="MW">Malawi</option>
    <option value="MY">Malaysia</option>
    <option value="MV">Maldives</option>
    <option value="ML">Mali</option>
    <option value="MT">Malta</option>
    <option value="MH">Marshall Islands</option>
    <option value="MQ">Martinique</option>
    <option value="MR">Mauritania</option>
    <option value="MU">Mauritius</option>
    <option value="YT">Mayotte</option>
    <option value="MX">Mexico</option>
    <option value="FM">Micronesia</option>
    <option value="MD">Moldova</option>
    <option value="MC">Monaco</option>
    <option value="MN">Mongolia</option>
    <option value="ME">Montenegro</option>
    <option value="MS">Montserrat</option>
    <option value="MA">Morocco</option>
    <option value="MZ">Mozambique</option>
    <option value="MM">Myanmar</option>
    <option value="NA">Namibia</option>
    <option value="NR">Nauru</option>
    <option value="NP">Nepal</option>
    <option value="NL">Netherlands</option>
    <option value="AN">Netherlands Antilles</option>
    <option value="NC">New Caledonia</option>
    <option value="NZ">New Zealand</option>
    <option value="NI">Nicaragua</option>
    <option value="NE">Niger</option>
    <option value="NG">Nigeria</option>
    <option value="NU">Niue</option>
    <option value="NF">Norfolk Island</option>
    <option value="KP">North Korea</option>
    <option value="MP">Northern Mariana Islands</option>
    <option value="NO">Norway</option>
    <option value="OM">Oman</option>
    <option value="PK">Pakistan</option>
    <option value="PW">Palau</option>
    <option value="PS">Palestinian Territory</option>
    <option value="PA">Panama</option>
    <option value="PG">Papua New Guinea</option>
    <option value="PY">Paraguay</option>
    <option value="PE">Peru</option>
    <option value="PH">Philippines</option>
    <option value="PN">Pitcairn</option>
    <option value="PL">Poland</option>
    <option value="PF">Polynesia</option>
    <option value="PT">Portugal</option>
    <option value="PR">Puerto Rico</option>
    <option value="QA">Qatar</option>
    <option value="RE">Reunion</option>
    <option value="RO">Romania</option>
    <option value="RU">Russian Federation</option>
    <option value="RW">Rwanda</option>
    <option value="GS">S. Georgia &amp; S. Sandwich Isls.</option>
    <option value="SH">Saint Helena</option>
    <option value="KN">Saint Kitts &amp; Nevis Anguilla</option>
    <option value="LC">Saint Lucia</option>
    <option value="PM">Saint Pierre and Miquelon</option>
    <option value="VC">Saint Vincent &amp; Grenadines</option>
    <option value="WS">Samoa</option>
    <option value="SM">San Marino</option>
    <option value="ST">Sao Tome and Principe</option>
    <option value="SA">Saudi Arabia</option>
    <option value="SN">Senegal</option>
    <option value="RS">Serbia</option>
    <option value="SC">Seychelles</option>
    <option value="SL">Sierra Leone</option>
    <option value="SG">Singapore</option>
    <option value="SK">Slovakia</option>
    <option value="SI">Slovenia</option>
    <option value="SB">Solomon Islands</option>
    <option value="SO">Somalia</option>
    <option value="ZA">South Africa</option>
    <option value="KR">South Korea</option>
    <option value="ES">Spain</option>
    <option value="LK">Sri Lanka</option>
    <option value="SD">Sudan</option>
    <option value="SR">Suriname</option>
    <option value="SZ">Swaziland</option>
    <option value="SE">Sweden</option>
    <option value="CH">Switzerland</option>
    <option value="SY">Syrian Arab Republic</option>
    <option value="TW">Taiwan</option>
    <option value="TJ">Tajikistan</option>
    <option value="TZ">Tanzania</option>
    <option value="TH">Thailand</option>
    <option value="TG">Togo</option>
    <option value="TK">Tokelau</option>
    <option value="TO">Tonga</option>
    <option value="TT">Trinidad and Tobago</option>
    <option value="TN">Tunisia</option>
    <option value="TR">Turkey</option>
    <option value="TM">Turkmenistan</option>
    <option value="TC">Turks and Caicos Islands</option>
    <option value="TV">Tuvalu</option>
    <option value="UG">Uganda</option>
    <option value="UA">Ukraine</option>
    <option value="AE">United Arab Emirates</option>
    <option value="GB">United Kingdom</option>
    <option value="UY">Uruguay</option>
    <option value="UM">USA Minor Outlying Islands</option>
    <option value="UZ">Uzbekistan</option>
    <option value="VU">Vanuatu</option>
    <option value="VE">Venezuela</option>
    <option value="VN">Vietnam</option>
    <option value="VG">Virgin Islands (British)</option>
    <option value="VI">Virgin Islands (USA)</option>
    <option value="WF">Wallis and Futuna Islands</option>
    <option value="EH">Western Sahara</option>
    <option value="YE">Yemen</option>
    <option value="ZR">Zaire</option>
    <option value="ZM">Zambia</option>
    <option value="ZW">Zimbabwe</option>
    </select>
</div>
</div>
</div>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

<div class="col-lg-6">
    <div class="mb-3">
        <label class="form-label">CAP di Residenza <i class="fa fa-asterisk"></i> </label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" name="capdiresidenza" placeholder="CAP di Residenza" class="form-control dark-input" required>
        </div>
    </div>
</div>

    <div class="col-lg-12">
        <div class="mb-3">
            <label class="form-label">Informazioni Volontario</label>
            <textarea name="note" placeholder="" class="form-control dark-input"></textarea>
        </div>
    </div>
</div>
</div>

<div class="card-footer text-end">
    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i> Inserisci Volontario</button>
    <button type="reset" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Cancella Modulo</button>
</div>
</div>
</div>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>


<div class="col-lg-2">
    <div class="card mb-4 dark-profile">
    <div class="card-header"><center>Inserisci Informazioni</center></div>
    <div class="card-body">
    <label class="form-label">Data di Nascita <i class="fa fa-asterisk"></i> </label>
	<div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
    <input type="date" name="datadinascita" placeholder="Data di Nascita" class="form-control dark-input" required">
    </div>
    </div>
	</div>
	
	<div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label">Telefono </label>
  <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-phone"></i></span>
            </div>
  <input type="text" name="telefono" placeholder="Telefono" class="form-control dark-input">
	</div> 
  </div>
	</div>
	
	<div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label">Ruolo <i class="fa fa-asterisk"></i> </label>
   <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
  <select class="form-control dark-input" name="ruolo" required">
      <?php echo $optionsruolo; ?>
      </select>
	</div> 
  </div>
</div>

  <div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label">Sesso <i class="fa fa-asterisk"></i> </label>
  <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
  <select class="form-control dark-input" name="sesso" required">
      <?php echo $optionssesso; ?>
      </select>
	</div> 
  </div>
   </div>
   
    <div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label">Registrato Da <i class="fa fa-asterisk"></i> </label>
   <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
  <input type="text" id="registratoda" name="registratoda" placeholder="Registrato Da" class="form-control dark-input" required readonly >
	</div> 
  </div>
	 </div>
	 
	 <div class="card mb-4 dark-profile">
        <div class="card-body">
          <h3 class="h6">Volontario Attivo <i class="fa fa-asterisk"></i> </h3>
          <input type="checkbox" id="attivo" name="attivo" value="1" required>  
	</div>
	
	
  <!-- FINE FORM CAMPI -->
  <!------------------------------------------------------------------------>
  <!-- INIZIO JAVASCRIPT -->
  
  <script>
  // Seleziona il campo di input
  var dataOraInput = document.getElementById('registratoda');

  // Ottieni la data e l'orario correnti
  var dataOraCorrente = new Date();

  // Formatta la data e l'orario nel formato desiderato
  var formatoDataOra = dataOraCorrente.toLocaleString();

  // Inserisci la data e l'orario nel campo di input
  dataOraInput.value = formatoDataOra;
</script>

<script>
  // Funzione per chiudere l'alert con animazione fade
  function chiudiAlert() {
    var alertDiv = document.getElementById('alertprimary');
    alertDiv.style.opacity = '0';
    setTimeout(function() {
      alertDiv.style.display = 'none';
    }, 1000); // Durata dell'animazione fade in millisecondi (1 secondo = 1000 millisecondi)
  }

  // Chiude automaticamente l'alert dopo 10 secondi
  setTimeout(chiudiAlert, 10000); // 10 secondi = 10000 millisecondi
</script>


  <script>
function verificaCodiceFiscale(codiceFiscale) {
  // Verifica la lunghezza del codice fiscale
  if (codiceFiscale.length !== 16) {
    return false;
  }
  
  // Verifica il formato del codice fiscale
  var codiceFiscaleRegEx = /^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/;
  if (!codiceFiscaleRegEx.test(codiceFiscale)) {
    return false;
  }
  
  // Verifica la correttezza del codice fiscale
  var caratteriDispari = "0ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
  var caratteriPari = "1ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  var checkDigit = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  
  var s = 0;
  for (var i = 1; i <= 13; i += 2) {
    s += caratteriDispari.indexOf(codiceFiscale.charAt(i));
  }
  
  for (var i = 0; i <= 14; i += 2) {
    s += caratteriPari.indexOf(codiceFiscale.charAt(i));
  }
  
  if (checkDigit.charAt(s % 26) !== codiceFiscale.charAt(15)) {
    return false;
  }
  
  return true;
}

// Esempio di utilizzo
var codiceFiscaleInput = document.querySelector('input[name="codicefiscale"]');
var codiceFiscaleValue = codiceFiscaleInput.value;

if (verificaCodiceFiscale(codiceFiscaleValue)) {
  console.log("Il codice fiscale è corretto.");
} else {
  console.log("Il codice fiscale non è corretto.");
}
  </script>

<script>
function updateClock() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  
  const currentTime = `${now.getDate()}/${now.getMonth() + 1}/${now.getFullYear()} ${hours}:${minutes}:${seconds}`;
  document.getElementById('current-time').innerText = currentTime;
}

// Aggiorna l'orario ogni secondo
setInterval(updateClock, 1000);

// Aggiorna l'orario all'avvio della pagina
updateClock();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
 
 <!-- FINE JAVASCRIPT -->
 <!------------------------------------------------------------------------>
