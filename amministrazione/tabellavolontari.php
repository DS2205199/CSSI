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
    <title>Pagina di Amministrazione - Tabella Volontari</title>
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
  
  <!-- TABELLA VOLONTARI + ELIMINAZIONE IN MULTIPLO DEI VOLONTARI PHP -->
  
<?php
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Funzione per eliminare gli utenti selezionati
function deleteSelectedEntries($conn, $selectedIds) {
    // Impedisce l'iniezione di SQL
    $ids = array_map('intval', $selectedIds);

    // Crea una stringa di ID separati da virgola
    $idList = implode(',', $ids);

    // Esegui la query di eliminazione
    $sql = "DELETE FROM volontari WHERE ID IN ($idList)";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Controlla se è stato inviato il modulo di eliminazione
if (isset($_POST['delete'])) {
    $selectedIds = $_POST['delete'];

    // Elimina gli utenti selezionati
    $deleted = deleteSelectedEntries($conn, $selectedIds);

    if ($deleted) {
        echo "<script>alert('Gli utenti selezionati sono stati eliminati con successo.');</script>";
    } else {
        echo "<script>alert('Si è verificato un errore durante l\'eliminazione degli utenti.');</script>";
    }
}

  //------------------------------------------------------------------------>
  //------------------------------------------------------------------------>
  // Controlla se è stato inviato il modulo di ricerca

if (isset($_GET['submit'])) {
    $cognome = $_GET['cognome'];
    $nome = $_GET['nome'];
    $codicefiscale = $_GET['codicefiscale'];
    $ruolo = $_GET['ruolo'];

    // Effettua la query per selezionare i volontari corrispondenti alla ricerca
    $sql = "SELECT * FROM volontari WHERE cognome LIKE '%$cognome%' AND nome LIKE '%$nome%' AND codicefiscale LIKE '%$codicefiscale%' AND ruolo LIKE '%$ruolo%'";
} else {
    // Se non è stata inviata la ricerca, seleziona tutti i volontari
    $sql = "SELECT * FROM volontari";
}

$result = $conn->query($sql);

  //!------------------------------------------------------------------------>
  //!------------------------------------------------------------------------>
  // Esegui la query per ottenere il numero totale di utenti

$sql_total_users = "SELECT COUNT(*) AS total_users FROM volontari";
$result_total_users = $conn->query($sql_total_users);
$row_total_users = $result_total_users->fetch_assoc();
$total_users = $row_total_users['total_users'];

// Chiude la sezione PHP prima di scrivere il codice HTML

?>

  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>

<div class="d-flex justify-content-between my-3">
    <div>
        <a href="aggiungivolontario.php" class="btn btn-primary rounded mr-2">Aggiungi Volontario</a>
    </div>
    <div class="d-flex">
        <form method="get" class="d-flex">
            <input type="text" class="form-control bg-dark mr-3" name="cognome" placeholder="Cognome">
            <input type="text" class="form-control bg-dark mr-3" name="nome" placeholder="Nome">
            <input type="text" class="form-control bg-dark mr-3" name="codicefiscale" placeholder="Codice Fiscale">
			<input type="text" class="form-control bg-dark mr-3" name="ruolo" placeholder="Ruolo">

            <input type="submit" name="submit" value="Cerca" class="btn btn-primary">
        </form>
        
    </div>
</div>


        
		
  <!------------------------------------------------------------------------>
  <!------------------------------------------------------------------------>
  
<div class="row">
    <div class="col-sm-12">
        <div class="bg-dark text-white p-2 rounded-top">
            <h3 class="text-center text-white mb-0">Benvenuto nella sezione dedicata alla gestione Anagrafica dei volontari - sono presenti <?php echo $total_users; ?> Volontario/i nel database</p></center></h3>
			        <form method="post">


        </div>
        <table class="table table-dark table-striped rounded-bottom">
            <thead>
                <tr>
				                 <th scope="col">
                            <input type="checkbox" id="selectAll">
                            
                    <th scope="col">ID</th>
                    <th scope="col">Codice Fiscale</th>
                    <th scope="col">Cognome</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ruolo</th>
					<th scope="col">Telefono</th>
					<th scope="col">Sesso</th>
					<th scope="col">Registratoda</th>
					<th scope="col">Online</th>
					<th colspan="5" scope="col">Azioni</th> <!-- Aggiunto colspan=3 per unire le 3 colonne Azioni -->

                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Itera sui risultati della query
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
						echo "<td class='cell100 column1 align-middle text-center'><input type='checkbox' class='delete-checkbox' name='delete[]' value='" . $row["ID"] . "'></td>";
                        echo "<td class='cell100 column1 align-middle text-center' data-order='" . $row["ID"] . "'>" . $row["ID"] . "</td>";
                        echo "<td class='cell100 column2 align-middle text-center'>" . $row["codicefiscale"] . "</td>";
                        echo "<td class='cell100 column2 align-middle text-center'>" . $row["cognome"] . "</td>";
                        echo "<td class='cell100 column2 align-middle text-center'>" . $row["nome"] . "</td>";
                        echo "<td class='cell100 column2 align-middle text-center'>" . $row["email"] . "</td>";
                        echo "<td class='cell100 column2 align-middle text-center'>" . $row["ruolo"] . "</td>";
						echo "<td class='cell100 column2 align-middle text-center'>" . $row["telefono"] . "</td>";
						echo "<td class='cell100 column2 align-middle text-center'>" . $row["sesso"] . "</td>";
						echo "<td class='cell100 column2 align-middle text-center'>" . $row["registratoda"] . "</td>";



						echo "<td class='cell100 column2 align-middle text-center'>";
            if ($row["stato"] == "Online") {
                echo "<span class='badge bg-success'>Online</span>";
            } else {
                echo "<span class='badge bg-danger'>Offline</span>";
            }
            echo "</td>";
            echo "<td class='cell100 column2 align-middle text-center'>";
            if ($row["attivo"] == "1") {
                echo "<a href='/include/abilita_utente.php?id=" . $row["ID"] . "&azione=disabilita'>";
                echo "<span class='badge bg-success'>Disabilita</button>";
                echo "</a>";
            } else {
                echo "<a href='/include/abilita_utente.php?id=" . $row["ID"] . "&azione=abilita'>";
                echo "<span class='badge bg-success'>Abilita</button>";
                echo "</a>";
            }
            echo "</td>";
            echo "<td class='cell100 column2 align-middle text-center'>";
            echo "<a href='guardaprofilo.php?id=" . $row["ID"] . "'>";
            echo "<i class='fa fa-eye'></i></a>";
            echo "</td>";
            echo "<td class='cell100 column2 align-middle text-center'>";
            echo "<a href='modificaprofilo.php?id=" . $row["ID"] . "&nome=" . urlencode($row["nome"]) . "&cognome=" . urlencode($row["cognome"]) . "'>";
            echo "<i class='fas fa-pencil-alt'></i></a>";
            echo "</td>";
            echo "<td class='cell100 column2 align-middle text-center'>";
            echo "<a href='eliminavolontario.php?id=" . $row["ID"] . "'>";
            echo "<i class='fas fa-trash-alt'></i></a>";
            echo "</td>";
            echo "</tr>";
						
                        echo "</tr>";
                    }
                } else {
                    // Nessun risultato trovato
                    echo "<tr><td colspan='6' class='text-center'>Nessuna Attività trovata</td></tr>";
                }
                ?>
            </tbody>
        </table>
		<div class="my-3">
                <button type="submit" class="btn btn-danger" name="deleteSelected" value="true">Elimina Selezionati</button>
				
               
    </div>
</div>




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
		
<script>
    function checkAll() {
        var selectAllCheckbox = document.getElementById('selectAll');
        var checkboxes = document.querySelectorAll('.delete-checkbox');

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = selectAllCheckbox.checked;
        }
    }

    // Add an event listener to the "Select All" checkbox to call checkAll() function
    var selectAllCheckbox = document.getElementById('selectAll');
    selectAllCheckbox.addEventListener('click', checkAll);
</script>
		<!-- FINE JAVASCRIPT -->
		<!------------------------------------------------------------------------>
