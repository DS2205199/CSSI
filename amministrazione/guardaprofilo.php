<!-- INIZIO PHP -->
<?php

  // Avvia la sessione prima di qualsiasi output
  session_start();
  
  // Verifica se l'utente ha effettuato l'accesso
  if (!isset($_SESSION["email"])) {
  header("Location: /index.php");
  exit(); }

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
  exit(); } }


// RECUPERA IL NOME E IL COGNOME DELL'UTENTE DELLE VARIABILI DI SESSIONE
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$ruolo = $_SESSION["ruolo"];
$email = $_SESSION["email"];
$registratoda = $_SESSION['registratoda'];

// AGGIORNA IL TIMESTAMP DELL'ULTIMO ACCESSO
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
  <center> <p> Mi dispiace, ma non sei autorizzato a visualizzare il profilo. Se hai bisogno di assistenza o se per te e un errore, contatta amministratore del sistema.</p></center>
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

<!-- FINE PHP -->

<!-- INZIO HTML -->
<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- TITOLO DELLA PAGINA -->
    <title> Profilo Utente - Comitato Sicurezza Stradale </title>
	  
     <!------------------------------------------------------------------------>

    <!-- CSS ESTERNI -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<!-- Include the jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.3/jspdf.umd.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">


    <!------------------------------------------------------------------------>

    <!-- FONT -->
    <link href="https://it.allfont.net/allfont.css?fonts=capture-it" rel="stylesheet" type="text/css" />

    <!------------------------------------------------------------------------>

    <!-- ICONE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!------------------------------------------------------------------------>

    <!-- CSS INTERNI -->
    <link href="css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet"> </head>

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
  <!-- INZIO SELEZIONA ID E VISUALIZZA SUL FORM -->


				                <?PHP
				$id = $_GET["id"];
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se l'ID dell'appartenenza è stato passato in modo sicuro tramite GET
if (isset($_GET['id'])) {
    $attivitaID = $conn->real_escape_string($_GET['id']);

// Esegui la query per ottenere i dati dell'utente corrispondente dall'ID
$query = "SELECT * FROM volontari WHERE ID = ?";
$stmt = $conn->prepare($query);
    $stmt->bind_param("i", $attivitaID);
    $stmt->execute();

    $result = $stmt->get_result();


if ($result) {
        // Controlla se sono presenti righe di risultati
        if ($result->num_rows > 0) {
            // Estrai i dati dell'appartenenza
            $row = $result->fetch_assoc();

            // Visualizza i dati dell'appartenenza
            // ... altri dati del profilo dell'utente
        } else {
	  echo '<div class="alert alert-danger" role="alert">';
      echo "Siamo spiacenti, ma non è stato possibile trovare nessun utente corrispondente all'ID specificato. Si prega di verificare l'ID inserito e riprovare. Per ulteriori assistenza, siamo a disposizione.";
      echo '</div>';
  }
} else {
	  echo '<div class="alert alert-danger" role="alert">';
	  echo "Ci scusiamo per l'inconveniente, ma si è verificato un errore nella query. Si consiglia di controllare la sintassi e i parametri della query e riprovare. Se l'errore persiste, ti suggeriamo di contattare il supporto tecnico per ulteriore assistenza nella risoluzione del problema. " . mysqli_error($conn);
	  echo '</div>';
    }

    // Chiudi lo statement
    $stmt->close();
}

// Chiudi la connessione al database
$conn->close();
?>


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
$query = "SELECT * FROM volontari WHERE ID = $id";
$result = mysqli_query($conn, $query);

// Controllo se la query ha prodotto risultati
if (mysqli_num_rows($result) > 0) {
    // Recupero dei dati dal risultato della query
    $row = mysqli_fetch_assoc($result);
    $foto_profilo = $row['foto_profilo'];
    $attivo = $row['attivo'];
  } else {
    echo "Nessun dato trovato";
}

// Chiusura della connessione al database
mysqli_close($conn);
?>

  <!-- FINE SELEZIONA ID E VISUALIZZA SUL FORM -->
  <!------------------------------------------------------------------------>
  <!-- VISUALIZZA SUL FORM -->


<div class="media align-items-center py-3 mb-3">

<?php   
// Mostra l'immagine del profilo se è stata caricata
if (!empty($foto_profilo)) {
    echo '<img src="' . $foto_profilo . '" alt="Foto Profilo" width="200" height="200">';
} else {
    echo '<img src="placeholder.jpg" alt="Foto Profilo" width="200" height="200">';
}
?>
              
<div class="media-body ml-4">
<h4 class="font-weight-bold mb-0">			
<span class="text-muted font-weight-normal"><?php echo " " . $row["nome"],  " "  . $row["cognome"] . "<br>";;?></span></h4>
<span class="text-muted font-weight-normal"><?php echo "Ruolo del Volontario: <b>" . $row["ruolo"], "" . "<br></b>";?></span></h4>
<div class="text-muted mb-2"><?php echo "ID Utente:<b>" . $row["ID"], "" . "<br>";?></div>
</div>
</div>

            <div class="card mb-4 dark-profile">
              <div class="card-body">
                <table class="table user-view-table m-0">
                  <tbody> <tr>
                      
<div class="row">
    <div class="col-md-2">
	
        <center><p><b>Registrato Dal:</b> <span class="text-light"><?php echo $row["registratoda"]; ?></span></p>
		<br>
		<p><b>Ruolo:</b> <span class="text-light"><?php echo $row["ruolo"]; ?></span></p>
		<br>
		<p><b>Sesso:</b> <span class="text-light"><?php echo $row["sesso"]; ?></span></p>
		<br>
		<p><b>Telefono:</b> <span class="text-light"><?php echo $row["telefono"]; ?></span></p>	
    <hr>
<center><h4><a href="trasferimenti.php?id=<?php echo $row["ID"]; ?>">Guarda Trasferimenti</a></h4></center>


	</div>
	 <div class="col-md-10">
	

<style>
  /* Custom CSS for rounded form fields */
  .form-control {
    border-radius: 60px; /* Adjust the value as per your preference */
  }
    /* Stili per il layout di stampa */
    @media print {
      /* Nascondi elementi non necessari */
      .no-print {
        display: none;
      }
      
      /* Imposta la dimensione del foglio carta */
      @page {
        size: A4;
        margin: 1cm;
      }
    }
</style>

<div class="row">
  <div class="col">
    <div class="form-group custom-rounded-form-group">
      <center><label for="cognome">Cognome</label>
      <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
<input type="text" class="form-control" id="cognome" readonly disabled placeholder="<?php echo $row["cognome"]; ?>">
    </div>
  </div>
  
  <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Nome</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->

      <input type="text" class="form-control" id="nome" readonly disabled placeholder="<?php echo $row["nome"]; ?>">
    </div>
  </div>
</div>


  
<div class="row">
  <div class="col">
    <div class="form-group custom-rounded-form-group">
      <center><label for="cognome">Codice Fiscale</label>
      <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="codicefiscale" readonly disabled placeholder="<?php echo $row["codicefiscale"]; ?>"> 
    </div>
  </div>
  
  
       <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Nato il</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->

      <input type="text" class="form-control" id="datadinascita" readonly disabled placeholder="<?php echo $row["datadinascita"]; ?>">
    </div>
  </div>
  
         <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Email</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="email" readonly disabled placeholder="<?php echo $row["email"]; ?>">
    </div>
  </div>
    </div>
      <p class="mb-4"></p> <!-- Add some margin at the bottom to create space -->

<div class="row">

  <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Comune di Nascita:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="comunedinascita" readonly disabled placeholder="<?php echo $row["comunedinascita"]; ?>">
    </div>
  </div>
  
    <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Provincia di Nascita:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="provinciadinascita" readonly disabled placeholder="<?php echo $row["provinciadinascita"]; ?>">
    </div>
  </div>


    <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">indirizzo di Residenza:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="indirizzodiresidenza" readonly disabled placeholder="<?php echo $row["indirizzodiresidenza"]; ?>">
    </div>
  </div>

      <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Comune di Residenza:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="comunediresidenza" readonly disabled placeholder="<?php echo $row["comunediresidenza"]; ?>">
    </div>
  </div>
    </div>
  
  <div class="row">

        <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Provincia di Residenza:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="provinciadiresidenza" readonly disabled placeholder="<?php echo $row["provinciadiresidenza"]; ?>">
    </div>
  </div>
  
          <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Stato di Nascita:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="statodinascita" readonly disabled placeholder="<?php echo $row["statodinascita"]; ?>">
    </div>
  </div>
  
            <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">Stato di Residenza:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="statodiresidenza" readonly disabled placeholder="<?php echo $row["statodiresidenza"]; ?>">
    </div>
  </div>
  
              <div class="col">
    <div class="form-group">
      <center><label for="nome" class="mr-2 center">CAP di Residenza:</label>
	    <p class="mb-2"></p> <!-- Add some margin at the bottom to create space -->
      <input type="text" class="form-control" id="capdiresidenza" readonly disabled placeholder="<?php echo $row["capdiresidenza"]; ?>">
    </div>
  </div>
      </div>
	  
<!-- Pulsante per stampare i dati -->
<button class="print-button" onclick="openPrintWindow()">Stampa</button>


<script>
  function openPrintWindow() {
    // Recupera i dati dalla pagina
    const cognome = document.getElementById("cognome").placeholder;
    const nome = document.getElementById("nome").placeholder;
    const codicefiscale = document.getElementById("codicefiscale").placeholder;
    const datadinascita = document.getElementById("datadinascita").placeholder;
    const comunedinascita = document.getElementById("comunedinascita").placeholder;
	const provinciadinascita = document.getElementById("provinciadinascita").placeholder;
	const capdiresidenza = document.getElementById("capdiresidenza").placeholder;
	const indirizzodiresidenza = document.getElementById("indirizzodiresidenza").placeholder;
	const email = document.getElementById("email").placeholder;
	const comunediresidenza = document.getElementById("comunediresidenza").placeholder;
	const provinciadiresidenza = document.getElementById("provinciadiresidenza").placeholder;
	const statodinascita = document.getElementById("statodinascita").placeholder;
	const statodiresidenza = document.getElementById("statodiresidenza").placeholder;
	


    // Crea il contenuto da stampare
    const contentToPrint = `
	<style>
    /* Stili per il layout di stampa */
    @media print {
      /* Imposta la dimensione del foglio carta A4 */
      @page {
        size: A4;
        margin: 0; /* Imposta i margini a 0 per occupare l'intero foglio */
      }

      /* Nascondi elementi non necessari durante la stampa */
      .no-print {
        display: none;
      }

      /* Stili per il contenuto da stampare */
      .print-content {
        /* Imposta i margini interni per avere un padding di 20px dall'alto e dal basso e 40px dai lati */
        padding: 20px 40px;
      }
	  
    /* Definisci il nuovo font per l'elemento h3 */
    font1 {
      font-family: "roboto", sans-serif; /* Sostituisci "NomeDelTuoFont" con il nome del font desiderato */
      font-size: 15px; /* Imposta la dimensione del font desiderata */
      /* Altri stili CSS per l'elemento h3 se necessario */
    }
  }
  </style>
	       <br> 

<center><h2>Modulo di iscrizione dei Volontari Temporanei – Comitato di Comitato Sicurezza Stradale Italiana</center></h2>

<center><h2>Scheda Anagrafica del Volontario <?php echo " " . $row["nome"],  " "  . $row["cognome"] . "<br>";;?></h2></center></h2>

<center><font1>II/la sottoscritto/a Cognome e Nome: <b>${cognome} ${nome}</b> Codice Fiscale: <b>${codicefiscale}</b><p></center>
	  <center>Nato/a il: <b>${datadinascita}</b> a: <b>${comunedinascita}</b> Prov: <b>${provinciadinascita}</b>
	  C.A.P: <b>${capdiresidenza}</b> Via/P.zza: <b>${indirizzodiresidenza}</b> 
	  <p> Email: <b>${email}</b> Comune di Residenza: <b>${comunediresidenza}</b> Provincia di Residenza: <b>${provinciadiresidenza}</b><p>
	  Stato di Nascita: <b>${statodinascita }</b> Stato di Residenza: <b>${statodiresidenza}</b></font1></center><p>
	  <br>
	  <center><h1>CHIEDE</h1></center>
	  <br>
	  <center>di essere iscritto presso dei Volontari Comitato Sicurezza Stradale Italiana.</center>
	  <br>
	  <center><h1>DICHIARA</h1></center>
      <br>
	  <center>
	  <div style="position: absolute; top: 190px; left: 80px;">
       
	  
    `;

    // Apri una nuova finestra con il contenuto da stampare
    const printWindow = window.open("", "_blank");
    printWindow.document.open();
    printWindow.document.write(contentToPrint);
    printWindow.document.close();
  }
</script>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.3/jspdf.umd.min.js"></script>

 
 <!-- FINE JAVASCRIPT -->
 <!------------------------------------------------------------------------>