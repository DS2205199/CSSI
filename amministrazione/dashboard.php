<?php
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
// RECUPERA IL NOME E IL COGNOME DELL'UTENTE DELLE VARIABILI DI SESSIONE
$codicefiscale = $_SESSION["codicefiscale"];
$ID = $_SESSION["ID"];
$nome = $_SESSION["nome"];
$cognome = $_SESSION["cognome"];
$ruolo = $_SESSION["ruolo"];
$email = $_SESSION["email"];
$registratoda = $_SESSION['registratoda'];

// AGGIORNA IL TIMESTAMP DELL'ULTIMO ACCESSO
$_SESSION["last_activity"] = time();

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
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
  <center> <p> Mi dispiace, ma non sei autorizzato a entrare nella Dashboard. Se hai bisogno di assistenza o se per te e un errore, contatta amministratore del sistema.</p></center>
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
    <title>Pagina di Amministrazione - Dashboard</title>
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

    <link href="css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet"></head>

    <!------------------------------------------------------------------------>
	<style>
	.rounded {
  border-radius: 10px; /* Regola il valore per ottenere l'arrotondamento desiderato */
}
</style>

<!------------------------------------------------------------------------>
  <!-- HTML INZIO MENU PROFILO -->
  <!-- HTML FINE MENU PROFILO -->
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
  <!-- CARD PHP INFORMAZIONI -->
  
 

<div class="row">
  <div class="col-sm-2 mb-2 mb-sm-0">
    <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg.jpg');">
      <div class="card-body">
        <h5 class="card-title"><center>Volontari Registrati</center></h5>

        <?php
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as total FROM volontari";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $totalUsers = $row["total"];
          echo "<center><p><i class='fas fa-users'></i> $totalUsers</p></center>";
        } else {
          echo "<center>Nessun volontario nel database.</center>";
        }

		
	        $conn->close();
      ?>
      </center></b>
      </div>
</div>
	  
	
	<div class="mt-3">

	</div>
      </div>
	  
	 <!------------------------------------------------------------------------>
	  
	 <div class="col-sm-2 mb-2 mb-sm-0">
     <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-2.jpg');">
	 <div class="card-body">
     <center><h5 class="card-title">Totale responsabili Registrati</h5>
	  
	  <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as total1 FROM responsabili";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $totalUsers1 = $row["total1"];
          echo "<center><p><i class='fas fa-users'></i> $totalUsers1</p></center>";
        } else {
          echo "Nessun Responsabile nel database.";
        }

        $conn->close();
      ?>
      
	  </center></b> </div></div>

     <div class="mt-3"> </div> </div>


	 <!------------------------------------------------------------------------>

	 <div class="col-sm-2">
     <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
     <div class="card-body">
     <center><h5 class="card-title">Totale Referenti Registrati</h5>
	  
	  <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as total2 FROM referenti";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $totalUsers2 = $row["total2"];
          echo "<center><p><i class='fas fa-users'></i> $totalUsers2</p></center>";
        } else {
          echo "Nessun Referente nel database.";
        }

        $conn->close();
      ?>
      </center></b>
      </div>
</div>
      </div>

	 <!------------------------------------------------------------------------>

	 <div class="col-sm-2">
     <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
     <div class="card-body">
     <center><h5 class="card-title"> Totale Attivita Inserite </h5>
	 
	 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as servizieffettuati FROM attivita";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $servizieffettuati = $row["servizieffettuati"];
          echo "<center><p><i class='fas fa-users'></i> $servizieffettuati</p></center>";
        } else {
          echo "Nessun utente nel database.";
        }

        $conn->close();
      ?>
      </center></b>
      </div>
</div>
      </div>
	  
	  <!------------------------------------------------------------------------>
	  
	 <div class="col-sm-2">
     <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
     <div class="card-body">
     <center><h5 class="card-title"> Totale Documenti Inseriti </h5>
	 
	 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as totaledocumenti FROM documenti";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $totaledocumenti = $row["totaledocumenti"];
          echo "<center><p><i class='fas fa-users'></i> $totaledocumenti</p></center>";
        } else {
          echo "Nessun Documento nel database.";
        }

        $conn->close();
      ?>
      </center></b>
      </div>
</div>
      </div>
	  
	  	<!------------------------------------------------------------------------>

	 <div class="col-sm-2">
     <div class="card text-white bg-secondary mb-3" style="max-width: 17rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
     <div class="card-body">
     <center><h5 class="card-title"> Totale Video Inseriti </h5>
	 
	 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Verifica connessione
        if ($conn->connect_error) {
          die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per ottenere il numero di utenti totali
        $sql = "SELECT COUNT(*) as videoinseriti FROM videos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $videoinseriti = $row["videoinseriti"];
          echo "<center><p><i class='fas fa-users'></i> $videoinseriti</p></center>";
        } else {
          echo "Nessun Video nel database.";
        }

        $conn->close();
      ?>
      </center></b>
      </div>
</div>
      </div>
	  
	  <!------------------------------------------------------------------------>
	  <!------------------------------------------------------------------------>
	 
    <div class="col-sm-3">
      <div class="card text-white bg-secondary mb-3" style="max-width: 30rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
        <div class="card-body">
          <center><h5 class="card-title">Gestione Road-Map</h5>
          <p class="card-text">Il sistema gestionale Road-Map per tenere sotto controllo le tue uscite con i mezzi propri.</p>
          <br>
          <button class="btn btn-danger btn-sm">Accedi a Road-Map</button>
        </div>
      </div>
    </div>
	
    <div class="col-sm-3">
      <div class="card text-white bg-secondary mb-3" style="max-width: 30rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
        <div class="card-body">
          <center><h5 class="card-title">Gestione Red-Cloud</h5>
          <p class="card-text">Il sistema gestionale Red-Cloud per tenere sotto controllo i tuoi Documenti.</p>
          <br>
          <button class="btn btn-dark btn-sm">Accedi a Red-Cloud</button>
        </div>
      </div>
    </div>

	  <div class="col-sm-3">
      <div class="card text-white bg-secondary mb-3" style="max-width: 30rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
        <div class="card-body">
          <center><h5 class="card-title">Gestionale Emergenze CSSI</h5>
          <p class="card-text">Il sistema gestionale Emergenze per tenere sotto controllo tutte le emergenze.</p>
          <br>
          <button class="btn btn-primary btn-sm">Accedi a Emergenze CSSI</button>
		  </div>
      </div>
    </div>
	
	<div class="col-sm-3">
      <div class="card text-white bg-secondary mb-3" style="max-width: 30rem; background-image: url('http://robindelaporte.fr/codepen/visa-bg-3.jpg');">
        <div class="card-body">
          <center><h5 class="card-title">Gestionale Check-List CSSI</h5>
          <p class="card-text">Il sistema gestionale Check-List per tenere sotto controllo tutte le Check-List.</p>
          <br>
          <button class="btn btn-secondary btn-sm">Accedi a Gestionale Check-List CSSI</button>
		 </div>
      </div>
    </div>
  </div>
</div>


	  <!------------------------------------------------------------------------>
	  <!------------------------------------------------------------------------>
	  
	  <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM volontari LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM volontari ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);
?>
	
 <div class="row">
    <div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0">Tabella dei Volontari</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Codice Fiscale</th>
            <th scope="col">Cognome</th>
            <th scope="col">Nome</th>
            <th scope="col">Email</th>
            <th scope="col">Ruolo</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if ($result->num_rows > 0) {
                // Itera sui risultati della query
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='cell100 column1 align-middle text-center' data-order='" . $row["ID"] . "'>" . $row["ID"] . "</td>";
                    echo "<td class='cell100 column2 align-middle text-center'>" . $row["codicefiscale"] . "</td>";
                    echo "<td class='cell100 column2 align-middle text-center'>" . $row["cognome"] . "</td>";
                    echo "<td class='cell100 column2 align-middle text-center'>" . $row["nome"] . "</td>";
                    echo "<td class='cell100 column2 align-middle text-center'>" . $row["email"] . "</td>";
                    echo "<td class='cell100 column2 align-middle text-center'>" . $row["ruolo"] . "</td>";
                    echo "</tr>";
                }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='6' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabellavolontari.php' class='text-white'>Guarda la Tabella Completa dei Volontari</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessuna Attività trovata</td></tr>";
                }
                ?>
					 
      </table>
    </div>
    
	  	<!------------------------------------------------------------------------>
		 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM referenti LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM referenti ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);

?>

		<div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0">Tabella dei Referenti</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
			<th scope="col">ID</th>
            <th scope="col">Cognome</th>
            <th scope="col">Nome</th>
          </tr>
			<?php
                    if ($result->num_rows > 0) {
                        // Itera sui risultati della query
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='cell100 column1 align-middle text-center' data-order='" . $row["ID"] . "'>" . $row["ID"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["cognome"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["nome"] . "</td>";
                           
                            
                            echo "</tr>";
                          }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='3' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabellareferenti.php' class='text-white'>Guarda la Tabella Completa dei Referenti</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessun Referente trovato</td></tr>";
                }
                ?>
					  
    </table>
      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
	
		  	<!------------------------------------------------------------------------>

 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM responsabili LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM responsabili ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);

?>
 <div class="row">
    <div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0">Tabella dei Responsabile</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
			<th scope="col">ID</th>
            <th scope="col">Cognome</th>
            <th scope="col">Nome</th>
          </tr>
			<?php
                    if ($result->num_rows > 0) {
                        // Itera sui risultati della query
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='cell100 column1 align-middle text-center' data-order='" . $row["ID"] . "'>" . $row["ID"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["cognome"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["nome"] . "</td>";
                           
                            
                            echo "</tr>";
                        }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='6' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabellaresponsabili.php' class='text-white'>Guarda la Tabella Completa dei Responsabili</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessuna Responsabile trovato</td></tr>";
                }
                ?>
					  
    </table>
		 </div>
		
		 
			<!------------------------------------------------------------------------>

 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM attivita LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM attivita ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);

?>
    <div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0 w-100">Tabella delle Attività</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
            <th scope="col">Nome Attività</th>
            <th scope="col">Posizione del Servizio</th>
			<th scope="col">Area di Intervento</th>

          </tr>
			<?php
                    if ($result->num_rows > 0) {
                        // Itera sui risultati della query
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["nomeattivita"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["posizioneservizio"] . "</td>";
						   echo "<td class='cell100 column2 align-middle text-center '>" . $row["areadintervento"] . "</td>";
                            
                            echo "</tr>";
                          }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='3' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabellattivita.php' class='text-white'>Guarda la Tabella Completa delle Attività</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessuna Attività trovata</td></tr>";
                }
                ?>
					  
    </table>
	</div>
	
	<!------------------------------------------------------------------------>

 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM quotepagate LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM quotepagate ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);

?>
    <div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0 w-100">Tabella delle Quote Pagate</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
            <th scope="col">Numero</th>
            <th scope="col">Cognome</th>
			<th scope="col">Nome</th>
			<th scope="col">Data e Ora di Pagamento</th>
			<th scope="col">Importo</th>

          </tr>
			<?php
                    if ($result->num_rows > 0) {
                        // Itera sui risultati della query
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["numero"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["cognome"] . "</td>";
						    echo "<td class='cell100 column2 align-middle text-center '>" . $row["nome"] . "</td>";
							echo "<td class='cell100 column2 align-middle text-center '>" . $row["dataeoradipagamento"] . "</td>";
							echo "<td class='cell100 column2 align-middle text-center '>" . $row["importo"] . "</td>";


                            echo "</tr>";
                          }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='6' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabellapagamenti.php' class='text-white'>Guarda la Tabella Completa dei Pagamenti</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessuna Attività trovata</td></tr>";
                }
                ?>
					  
    </table>
	</div>
	<!------------------------------------------------------------------------>

 <?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Imposta il limite di righe da visualizzare
$limit = 5;

// Query per selezionare i dati dei volontari
$sql = "SELECT * FROM documenti LIMIT $limit";
$result = $conn->query($sql);

// Query per selezionare gli ultimi 5 utenti registrati
$sql = "SELECT * FROM documenti ORDER BY ID DESC LIMIT 5";
$result = $conn->query($sql);

?>
    <div class="col-sm-6">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center mb-0 w-100">Tabella dei Documenti</h3>
        </div>
        <table class="table table-dark table-striped rounded-bottom">
        <thead>
          <tr>
            <th scope="col">Nome Documento</th>
            <th scope="col">Registrato Da</th>
			<th scope="col">Data e Ora di Inserimento</th>
			

          </tr>
			<?php
                    if ($result->num_rows > 0) {
                        // Itera sui risultati della query
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["nomedocumento"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["registratoda"] . "</td>";
						    echo "<td class='cell100 column2 align-middle text-center '>" . $row["dataeoradinserimento"] . "</td>";
						


                            echo "</tr>";
                          }

                    // Aggiungiamo il secondo header come ultima riga del corpo della tabella
                    echo "<tr>";
                    echo "<td colspan='6' class='bg-dark text-white p-2 rounded-bottom'><h5 class='text-center mb-0 w-100'><a href='tabelladocumenti.php' class='text-white'>Guarda la Tabella Completa dei Documenti</a></h5></td>";
                    echo "</tr>";
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='3'>Nessun Documento trovato</td></tr>";
                }
                ?>
					  
    </table>
	
		<!------------------------------------------------------------------------>
		<!-- INIZIO JAVASCRIPT -->
		
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

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
		
		<!-- FINE JAVASCRIPT -->
		<!------------------------------------------------------------------------>

	

  
  