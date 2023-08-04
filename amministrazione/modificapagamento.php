<?php
// Avvia la sessione prima di qualsiasi output
session_start();
    // Verifica se l'utente ha effettuato l'accesso
    if (!isset($_SESSION["email"])) {
       
        header("Location: /index.php");
        exit();
    }

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

// Aggiorna il timestamp dell'ultimo accesso
$_SESSION["last_activity"] = time();

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
  <center> <p> Mi dispiace, ma non sei autorizzato a modificare il Pagamento. Se hai bisogno di assistenza o se per te e un errore, contatta amministratore del sistema.</p></center>
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
$db = new PDO('mysql:host=localhost;dbname=cssi', 'root', '');

// Inserisci il nome della pagina nella tabella degli accessi
$indirizzo_ip = $_SERVER['REMOTE_ADDR']; // Assumendo che tu voglia ottenere l'indirizzo IP del client
$accesso_negato = false; // Assumendo che tu voglia impostare il valore predefinito di accesso_negato come false

$stmt = $db->prepare('INSERT INTO accessi (email, cognome, nome, timestamp, NomePagina, indirizzo_ip, accesso_negato) VALUES (?, ?, ?, NOW(), ?, ?, ?)');
$stmt->execute(array($email, $cognome, $nome, $pageName, $indirizzo_ip, $accesso_negato));
?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Modifica Pagamento - Comitato Sicurezza Stradale </title>
    
	 <!------------------------------------------------------------------------>

    <!-- CSS ESTERNI -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!------------------------------------------------------------------------>

    <!-- FONT -->
    <link href="https://it.allfont.net/allfont.css?fonts=capture-it" rel="stylesheet" type="text/css" />

    <!------------------------------------------------------------------------>

    <!-- ICONE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!------------------------------------------------------------------------>

    <!-- CSS INTERNI -->
	    <link href="css/sidebar.css" rel="stylesheet"> </head>

    <link href="css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">

    <!------------------------------------------------------------------------>


  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span> </button>
  <div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav">
      
  <li class="nav-item active">
  <a class="nav-link" href="dashboard.php">Home</a>
  </li>
      
  <li class="nav-item active">
  <a class="nav-link" href="informazioninfo.php">Inserisci Informazioni</a>
  </li> </ul>
    
  <ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <i class="fas fa-bell fa-sm"></i>
  
  <!------------------------------------------------------------------------>
  <!-- PHP INZIO LETTURA NOTIFICHE -->
  
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "cssi";

  // Crea la connessione
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Verifica la connessione
  if ($conn->connect_error) {
  die("Connessione al database fallita: " . $conn->connect_error);
  } 

  // Query per recuperare il numero di notifiche non lette
  $sql = "SELECT COUNT(*) AS count FROM notifiche WHERE letta = 0";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $notifiche_non_lette = $row['count'];

  // Visualizza il badge solo se ci sono notifiche non lette
  if ($notifiche_non_lette > 0) {
  echo '<span class="badge badge-danger">' . $notifiche_non_lette . '</span>'; }

  // Chiudi la connessione al database
  mysqli_close($conn); ?> </a>
  
  <!-- PHP FINE LETTURA NOTIFICHE -->
  <!------------------------------------------------------------------------>
  <!-- PHP INZIO MOSTRA NOTIFICHE -->
  
  <?php
  // Connessione al database
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Verifica della connessione
  if (!$conn) {
  die("Connessione al database fallita: " . mysqli_connect_error()); }

  // Query per recuperare le notifiche
  $sql = "SELECT * FROM notifiche";
  $result = mysqli_query($conn, $sql);

  // Verifica se ci sono notifiche
  if (mysqli_num_rows($result) > 0) {
  echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">';

  // Itera sulle righe risultanti e visualizza le notifiche
  while ($row = mysqli_fetch_assoc($result)) {
  $messaggio = $row['messaggio'];
  
  echo '<a class="dropdown-item" href="#">' . $messaggio . '</a>'; }
  echo '<div class="dropdown-divider"></div>';
  echo '<a class="dropdown-item" href="#">Visualizza tutte le notifiche</a>';
  echo '</div>';
  } else {
  echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">';
  echo '<a class="dropdown-item" href="#">Nessuna notifica</a>';
  echo '</div>'; }

  // Chiudi la connessione al database
  mysqli_close($conn); ?> </a> </li>
  
  <!-- PHP FINE MOSTRA NOTIFICHE -->  
  <!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU PROFILO -->

  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <?php echo "Benvenuto $ruolo, $cognome, $nome"; ?> </a>
  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
  <a class="dropdown-item" href="profile.php">Profilo</a>
  <div class="dropdown-divider"></div>
  <a class="dropdown-item" href="logout.php">Esci</a>
  </div> </li> </ul> </div> </nav>
  
  <!-- HTML FINE MENU PROFILO -->
  <!------------------------------------------------------------------------>
  <!-- INZIO HTML - MOSTRA NOME COGNOME E RUOLO -->

  <div class="container-fluid">
  <div class="row">
  <nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <div class="sidebar-sticky">
  <div class="sidebar-title"> <br>
          
  <center><h5>CSSI</h5></center> 
  <hr> </div>
       
  <ul class="nav flex-column">
  <li class="nav-item">
  <b><center><a class="nav-link"><?php echo "Benvenuto, $cognome $nome" ?></a></center></b> 
  </li>
          
  <li class="nav-item">
  <b><center><a class="nav-link"><?php echo "$ruolo" ?></a></center></b>
  <hr> </li>
  
  <!-- FINE HTML - MOSTRA NOME COGNOME E RUOLO -->
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
  <a class="nav-link" href="aggiunginews.php">Inserisci una nuova News</a>
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
<!-- HTML INIZIO ALERT INFORMAZIONI -->

  <main role="main" class="col-md-5 ml-sm-auto col-lg-10 content">
  <div class="alert alert-info" role="alert">
  <i class="fa fa-info-circle" aria-hidden="true"></i>
    
  <!-- HTML FINE ALERT INFORMAZIONI -->
  <!------------------------------------------------------------------------>
  <!-- INZIO PHP ALERT INFORMAZIONI -->

  <?php
  $conn = new mysqli($servername, $username, $password, $dbname);

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
  <!-- INIZIO PHP MODIFICA SU TABELLA PAGAMENTO -->



<?php
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Controllo se il modulo di aggiornamento è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	 // Include il file di validazione
    $errors = require '../include/validazioni/validazionepagamento.php';

    // Verifica se sono presenti errori
    if (count($errors) > 0) {
        // Visualizza gli errori
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $error;
            echo '</div>';
        }
    } else {
        // Tutti i campi sono validi, procedi con l'inserimento nel database
        // Controlla il ruolo dell'utente loggato
        if ($ruolo === 'Presidente' || $ruolo === 'Segretaria') {
			
    // Recupero dei dati dal modulo
      $ID = isset($_POST['ID']) ? mysqli_real_escape_string($conn, $_POST['ID']) : '';
  $numero = isset($_POST['numero']) ? mysqli_real_escape_string($conn, $_POST['numero']) : '';
  $cognome = isset($_POST['cognome']) ? mysqli_real_escape_string($conn, $_POST['cognome']) : '';
  $nome = isset($_POST['nome']) ? mysqli_real_escape_string($conn, $_POST['nome']) : '';
  $anno = isset($_POST['anno']) ? mysqli_real_escape_string($conn, $_POST['anno']) : '';
  $importo = isset($_POST['importo']) ? mysqli_real_escape_string($conn, $_POST['importo']) : '';
  $registratoda = isset($_POST['registratoda']) ? mysqli_real_escape_string($conn, $_POST['registratoda']) : '';  
  $dataeoradipagamento = isset($_POST['dataeoradipagamento']) ? mysqli_real_escape_string($conn, $_POST['dataeoradipagamento']) : '';
  $descrizione = isset($_POST['descrizione']) ? mysqli_real_escape_string($conn, $_POST['descrizione']) : '';
  $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
  $descrizioneaggiuntive = isset($_POST['descrizioneaggiuntive']) ? mysqli_real_escape_string($conn, $_POST['descrizioneaggiuntive']) : '';
    
// Esecuzione dell'aggiornamento dei dati nella tabella
$query = "UPDATE quotepagate SET numero = ?, anno = ?, cognome = ?, nome = ?, importo = ?, registratoda = ?, dataeoradipagamento = ?, descrizione = ?, email = ?, descrizioneaggiuntive = ? WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iissssssssi", $numero, $anno, $cognome, $nome, $importo, $registratoda, $dataeoradipagamento, $descrizione, $email, $descrizioneaggiuntive, $ID);

$result = $stmt->execute();
if ($result) {
 echo '<div id="success-message" class="alert alert-primary" role="alert">';
 echo 'Il pagamento è stato aggiornato con successo!';
     echo '</div>';
   echo '<script>
   setTimeout(function() {
       var successMessage = document.getElementById("success-message");
       successMessage.remove();
   }, 10000);
 </script>';
   } else {
   echo '<div class="alert alert-danger" role="alert" id="error-message">';
   echo "Mi scuso per l'inconveniente riscontrato durante l'aggiornamento dei dati: " . $stmt->error;
   echo '</div>';
   }
 
// Chiusura dell'istruzione
$stmt->close();
} else {
echo '<div id="success-message" class="alert alert-danger" role="alert">';
echo "Non hai i privilegi necessari per Modificare i Pagamenti. Si prega di contattare amministratore per ulteriori informazioni.";
echo '</div>';
}
}
}
// Chiudi la connessione al database
$conn->close();
?>

  <!-- FINE UPDATE SU TABELLA PAGAMENTO -->
  <!------------------------------------------------------------------------>
  <!-- MENU A TENDINA -->

<?php
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
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

$conn->close();
?>

  <!-- MENU A TENDINA -->
  <!------------------------------------------------------------------------>

<?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Recupero dei dati dalla tabella
$id = $_GET['id']; // Supponiamo che l'ID venga passato come parametro nella query string

// Utilizzo di un prepared statement per proteggere dalle SQL injection
$query = "SELECT * FROM quotepagate WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

// Controllo se la query ha prodotto risultati
if ($result->num_rows > 0) {
    // Recupero dei dati dal risultato della query
    $row = $result->fetch_assoc();
} else {
    echo "Nessun dato trovato";
}

// Chiusura del prepared statement e della connessione al database
$stmt->close();
$conn->close();
?>

  <!------------------------------------------------------------------------>
  <!-- INIZIO FORM  -->   
   <form method="post" action="" enctype="multipart/form-data">

   <div class="row">
  <div class="col-lg-10">
  <div class="card w-100 mb-3 dark-profile">
  <div class="card-header"><center>Modifica Pagamento</center></div>
  <div class="card-body">
      <h5 class="card-title"><center><b>Nota importante: I campi contrassegnati con un asterisco (<i class="fa fa-asterisk"></i> ) sono obbligatori. Si prega di compilare tutti i campi richiesti correttamente.</center></h5></b>

           
		   <div class="mb-3">
              <label for="paymentMethod" class="form-label">Metodo di Pagamento: </label>
              <div class="form-check form-check-inline">
              <input class="form-check-input dark-profile" type="radio" name="paymentMethod" id="contantiRadio" value="contanti">
              <label class="form-check-label dark-profile" for="contantiRadio">Contanti</label>
</div>
          </div>

          
            <!-- Mostra solo se viene selezionato "Carta di Credito" come metodo di pagamento -->
            <div id="creditCardDetails" style="display: none;">
              <div class="mb-3">
                <label for="cardNumber" class="form-label">Numero della Carta di Credito</label>
                <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Numero della Carta di Credito">
              </div>
              <div class="mb-3">
                <label for="expirationDate" class="form-label">Data di Scadenza</label>
                <input type="text" class="form-control" id="expirationDate" name="expirationDate" placeholder="MM/AA">
              </div>
              <div class="mb-3">
                <label for="expirationDate" class="form-label">CVV Code</label>
                <input type="text" class="form-control" id="expirationDate" name="expirationDate" placeholder="CVV CODE">
              </div>
            </div>



            <!-- Mostra solo se viene selezionato "Contanti" come metodo di pagamento -->
            <div id="contanti" style="display: none;">
              <div class="row mb-2">
                <div class="col">
				
                <input type="hidden" class="form-control" id="ID" name="ID" placeholder="ID" value="<?php echo "" . $row["ID"], "" ?>" readonly>
                </div>
				</div>
               
			   <div class="row mb-2">
			   <div class="col">
                  <label for="cognome" class="form-label">Cognome</label>
                  <input type="text" class="form-control dark-input" name="cognome" required readonly value="<?php echo "" . $row["cognome"], "" ?>">
       
                </div>
               
			   <div class="col">
                  <label for="nome" class="form-label">Nome</label>
                   <input type="text" class="form-control dark-input" name="nome" required readonly value="<?php echo "" . $row["nome"], "" ?>">
                </div>
             

                <div class="col">
                  <label for="registratoda" class="form-label">Registrato Da</label>
                  <input type="text" class="form-control dark-input" id="registratoda" name="registratoda" placeholder="Registrato Da" value="<?php echo $_SESSION['cognome'] . ' ' . $_SESSION['nome']; ?>" readonly>
                </div>
              
                <div class="col">
                  <label for="descrizione" class="form-label">Descrizione</label>
                  <select class="form-control dark-input" name="descrizione" required">
            <?php echo $optionsDescrizioni; ?>
        </select>
              </div>
              </div>
             
                 <div class="row mb-2">
				<div class="col">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control dark-input" id="email" name="email" placeholder="Email" value="<?php echo "" . $row["email"], "" ?>" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}">
                </div>
				
                <div class="col">
                  <label for="importo" class="form-label">Importo</label>
                  <input type="text" class="form-control dark-input" id="importo" name="importo" placeholder="Importo" value="<?php echo "" . $row["importo"], "" ?>" required pattern="[0-9]+">
                </div>
				</div>
				
				<div class="row mb-2">
				 <div class="col">
                  <label for="descrizioneaggiuntive" class="form-label">Descrizione Aggiuntive</label>
                  <textarea class="form-control dark-input" id="descrizioneaggiuntive" name="descrizioneaggiuntive" placeholder="Descrizione Aggiuntive" value="<?php echo "" . $row["descrizioneaggiuntive"], "" ?>"> </textarea>
                </div>
              </div>
            </div>
</div>

<div class="card-footer text-end">
    <button type="submit" class="btn btn-primary"> <i class="fa fa-paper-plane" aria-hidden="true"></i> Aggiorna Pagamento</button>

  </div>
</div>
</div>


<div class="col-lg-2">
  <div class="card mb-4 dark-profile">
  <div class="card-header"><center> Informazioni Aggiuntive </center></div>
  <div class="card-body">
  <label class="form-label">Anno <i class="fa fa-asterisk"></i> </label> <select class="form-control dark-input" name="anno" required"> <?php echo $options; ?> </select>
  </div> </div>
	
	<div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label"> Numero <i class="fa fa-asterisk"></i> </label>
  <input type="text" name="numero" placeholder="Numero" class="form-control dark-input" required value="<?php echo "" . $row["numero"], "" ?>">
  </div> </div>

  <div class="card mb-4 dark-profile">
  <div class="card-body">
  <label class="form-label"> Data e Ora di Pagamento <i class="fa fa-asterisk"></i> </label>
  <input type="datetime-local" id="dataeoradipagamento" name="dataeoradipagamento" placeholder="Data e Ora di Pagamento" class="form-control dark-input" required readonly>
	</div> </div> </div>


<script>
  // Aggiungi un listener per l'evento di cambio della selezione del metodo di pagamento
  var paymentMethod = document.getElementsByName("paymentMethod");
  var creditCardDetails = document.getElementById("creditCardDetails");
  var contanti = document.getElementById("contanti");
  var bonifico = document.getElementById("bonifico");

  for (var i = 0; i < paymentMethod.length; i++) {
    paymentMethod[i].addEventListener("change", function() {
      if (this.value === "carta_credito") {
        creditCardDetails.style.display = "block";
        contanti.style.display = "none";
        bonifico.style.display = "none";
      } else if (this.value === "contanti") {
        creditCardDetails.style.display = "none";
        contanti.style.display = "block";
        bonifico.style.display = "none";
      } else if (this.value === "bonifico") {
        creditCardDetails.style.display = "none";
        contanti.style.display = "none";
        bonifico.style.display = "block";
      }
    });
  }
</script>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    </body>
</html>