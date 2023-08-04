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
  <center> <p> Mi dispiace, ma non sei autorizzato a inserire Attivita. Se hai bisogno di assistenza o se per te e un errore, contatta amministratore del sistema.</p></center>
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
    <title> Aggiungi Attività - Comitato Sicurezza Stradale </title>
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
  <!-- INIZIO PHP INSERIMENTO SU TABELLA ATTIVITA -->

<?php
// Connessione al database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica della connessione
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Include il file di validazione
    $errors = require '../include/validazioni/validazioneattivita.php';

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
            
			// Recupero dei dati dal form
            $nomeattivita = isset($_POST['nomeattivita']) ? $_POST['nomeattivita'] : '';
            $posizioneservizio = isset($_POST['posizioneservizio']) ? $_POST['posizioneservizio'] : '';
            $nomereferente = isset($_POST['nomereferente']) ? $_POST['nomereferente'] : '';
            $areadintervento = isset($_POST['areadintervento']) ? $_POST['areadintervento'] : '';
            $organizzatore = isset($_POST['organizzatore']) ? $_POST['organizzatore'] : '';
            $apertoa = isset($_POST['apertoa']) ? $_POST['apertoa'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $informazioniattivita = isset($_POST['informazioniattivita']) ? $_POST['informazioniattivita'] : '';
            $dataeoradelservizio = isset($_POST['dataeoradelservizio']) ? $_POST['dataeoradelservizio'] : '';
            $minpartecipanti = isset($_POST['minpartecipanti']) ? $_POST['minpartecipanti'] : '';
            $maxpartecipanti = isset($_POST['maxpartecipanti']) ? $_POST['maxpartecipanti'] : '';

            // Prepare the INSERT statement
            $stmt = mysqli_prepare($conn, "INSERT INTO attivita (nomeattivita, posizioneservizio, nomereferente, areadintervento, organizzatore, apertoa, email, informazioniattivita, dataeoradelservizio, minpartecipanti, maxpartecipanti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                // Bind the parameters to the statement
                mysqli_stmt_bind_param($stmt, "sssssssssss", $nomeattivita, $posizioneservizio, $nomereferente, $areadintervento, $organizzatore, $apertoa, $email, $informazioniattivita, $dataeoradelservizio, $minpartecipanti, $maxpartecipanti);

                // Execute the statement
                if (mysqli_stmt_execute($stmt)) {
                    // Get the ID of the newly inserted activity
                    $attivita_id = mysqli_insert_id($conn);

                    // Prepare the notification INSERT statement
                    $stmt_notifica = mysqli_prepare($conn, "INSERT INTO notifiche (attivita_id, messaggio) VALUES (?, ?)");

                    if ($stmt_notifica) {
                        $notifica = "Nuova attività registrata: $nomeattivita";
                        mysqli_stmt_bind_param($stmt_notifica, "is", $attivita_id, $notifica);

                        if (mysqli_stmt_execute($stmt_notifica)) {
                            echo '<div class="alert alert-success" role="alert">';
                            echo 'La notifica è stata salvata correttamente.';
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo "Ci scusiamo per l'inconveniente, ma si è verificato un errore durante l'invio della notifica: " . mysqli_error($conn);
                            echo '</div>';
                        }

                        // Close the notification statement
                        mysqli_stmt_close($stmt_notifica);
                    }

                    echo '<div class="alert alert-success" role="alert">';
                    echo "I dati sono stati salvati correttamente nel database.";
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo "Siamo spiacenti, si è verificato un errore durante il salvataggio dei dati nel database: " . mysqli_error($conn);
                    echo '</div>';
                }

                // Close the statement
                // mysqli_stmt_close($stmt); <-- Rimuovi questa riga
            }

            // Chiusura dell'istruzione
            // $stmt->close(); <-- Rimuovi questa riga
        } else {
            echo '<div id="success-message" class="alert alert-danger" role="alert">';
            echo "Non hai i privilegi necessari per aggiungere Attività. Si prega di contattare amministratore per ulteriori informazioni.";
            echo '</div>';
        }
    }
}

// Chiudi la connessione al database
$conn->close();
?>



  
  <!-- FINE PHP INSERIMENTO SU TABELLA TRASFERIMENTI -->
  <!------------------------------------------------------------------------>
  <!-- MENU A TENDINA -->

<?php
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlareadintervento = "SELECT areadintervento FROM areadintervento";
$resultareadintervento = $conn->query($sqlareadintervento);

$optionsareadintervento = '';

if ($resultareadintervento->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($row = $resultareadintervento->fetch_assoc()) {
        $areadintervento = $row["areadintervento"];
        $optionsareadintervento .= "<option value='$areadintervento'>$areadintervento</option>";
    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlorganizzatore = "SELECT nomeorganizzatore FROM organizzatori";
$resultorganizzatore = $conn->query($sqlorganizzatore);

$optionsorganizzatore = '';

if ($resultorganizzatore->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($row = $resultorganizzatore->fetch_assoc()) {
        $nomeorganizzatore = $row["nomeorganizzatore"];
        $optionsorganizzatore .= "<option value='$nomeorganizzatore'>$nomeorganizzatore</option>";
    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlapertoa = "SELECT apertoa FROM aperto";
$resultapertoa = $conn->query($sqlapertoa);

$optionsapertoa = '';

if ($resultapertoa->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($row = $resultapertoa->fetch_assoc()) {
        $apertoa = $row["apertoa"];
        $optionsapertoa .= "<option value='$apertoa'>$apertoa</option>";
    }
}

// Esecuzione della query per ottenere i dati delle descrizioni dal database
$sqlapertoa = "SELECT apertoa FROM aperto";
$resultapertoa = $conn->query($sqlapertoa);

$optionsapertoa = '';

if ($resultapertoa->num_rows > 0) {
    // Ciclo sui risultati della query e costruzione delle opzioni del menu a tendina per le descrizioni
    while ($row = $resultapertoa->fetch_assoc()) {
        $apertoa = $row["apertoa"];
        $optionsapertoa .= "<option value='$apertoa'>$apertoa</option>";
    }
}
?>
  <!-- MENU A TENDINA - FINE -->  
  <!------------------------------------------------------------------------>
  <!-- INZIO FORM CAMPI -->
  
   <form method="post" action="" enctype="multipart/form-data">

  <!-- FORM DI REGISTRAZIONE ATTIVITA -->
  <div class="row">
  <div class="col-lg-10">
  <div class="card w-100 mb-3 dark-profile">
  <div class="card-header"><center>Inserisci Attività</center></div>
  <div class="card-body">
  <h5 class="card-title"><center><b>Nota importante: I campi contrassegnati con un asterisco (<i class="fa fa-asterisk"></i> ) sono obbligatori. Si prega di compilare tutti i campi richiesti correttamente.</center></h5></b>

    <input type="hidden" name="ID" id="ID">

    <div class="mb-3">
      <label class="form-label">Nome Attività</label>
       <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="text" name="nomeattivita" placeholder="Nome Attività" class="form-control dark-input" i class="fa fa-asterisk" required">
    </div>

 <div class="row">
      <div class="col-lg-6">
        <div class="mb-3">
          <label class="form-label">Posizione del Servizio</label>
          <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="text" name="posizioneservizio" placeholder="Posizione del Servizio" class="form-control dark-input" required">
        </div>
      </div>


     <div class="col-lg-6">
  <div class="mb-3">
    <label class="form-label">Nome Referente</label>
    <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span>
    
	<input type="text" name="nomereferente" class="form-control dark-input" required>
  </div>
</div>
</div>
  
  <div class="row">
      <div class="col-lg-6">
        <div class="mb-3">
          <label class="form-label">Area di Intervento</label>
          <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <select name="areadintervento" placeholder="Area di Intervento" class="form-control dark-input" required> <?php echo $optionsareadintervento ?> </select>
        </div>
      </div>
	  
	  <div class="col-lg-6">
        <div class="mb-3">
          <label class="form-label">Organizzatore</label>
          <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <select name="organizzatore" placeholder="Organizzatore" class="form-control dark-input" required>  <?php echo $optionsorganizzatore ?> </select>
        </div>
      </div>
</div>
       
	  <div class="row">
        <div class="mb-3">
          <label class="form-label"> Email </label>
          <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="email" name="email" placeholder="Email" class="form-control dark-input" required>
        </div>
      </div>

      <div class="mb-3">
      <label class="form-label">Informazioni Attività</label>
      <textarea name="informazioniattivita" placeholder="Inserisci Informazioni" class="form-control dark-input"></textarea>
    </div>
  </div>
  
  <div class="card-footer text-end">
    <button type="submit" class="btn btn-primary"> <i class="fa fa-paper-plane" aria-hidden="true"></i> Invia Attività</button>
    <button type="reset" class="btn btn-danger"> <i class="fa fa-trash" aria-hidden="true"></i> Cancella Modullo</button>

  </div>
</div>
</div>


<div class="col-lg-2">
    <div class="card mb-4 dark-profile">
	<div class="card-header"><center>Informazioni Aggiuntive</center></div>
      <div class="card-body">
       <label class="form-label">Data e ora del Servizio</label>
        <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="datetime-local" name="dataeoradelservizio" placeholder="Data e Ora del Servizio" class="form-control dark-input" required>
      </div>
    </div>
	
	<div class="card mb-4 dark-profile">
        <div class="card-body">
          <label class="form-label">Minimo Partecipanti</label>
        <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="text" name="minpartecipanti" placeholder="Minimo Partecipanti" class="form-control dark-input" required>
		</div>
      </div>
	  
	  <div class="card mb-4 dark-profile">
        <div class="card-body">
          <label class="form-label">Massimo Partecipanti</label>
           <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <input type="text" name="maxpartecipanti" placeholder="Massimo Partecipanti" class="form-control dark-input" required>
		</div>
      </div>
	  
	    
	  <div class="card mb-4 dark-profile">
        <div class="card-body">
          <label class="form-label">Aperto a</label>
          <span class="input-group-addon"> <i class="fa fa-asterisk"></i> </span> <select name="apertoa" placeholder="Aperto a" class="form-control dark-input" required> <?php echo $optionsapertoa ?> </select>
        </div>
      </div>

  <!-- FINE FORM CAMPI -->
  <!------------------------------------------------------------------------>
  <!-- INZIO JAVASCRIPT-->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  
  <!-- FINE JAVASCRIPT-->
  <!------------------------------------------------------------------------>