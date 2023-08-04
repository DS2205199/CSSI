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
$registratoda = $_SESSION['registratoda'];

// AGGIORNA IL TIMESTAMP DELL'ULTIMO ACCESSO
$_SESSION["last_activity"] = time();
?>
<!-- FINE PHP -->

<!-- INZIO HTML -->
<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- TITOLO DELLA PAGINA -->
    <title> Tabella Riserve - Comitato Sicurezza Stradale </title>
	  
    <!-- CSS ESTERNI -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- FONT -->
    <link href="https://it.allfont.net/allfont.css?fonts=capture-it" rel="stylesheet" type="text/css" />

    <!-- ICONE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- CSS INTERNI -->
    <link href="css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet">



  </head>
  

 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">Home</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="informazioninfo.php">Inserisci Informazioni</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-bell fa-sm"></i>
     <?php
// Stabilisci la connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";

// Connessione al database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica della connessione
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

// Query per recuperare il numero di notifiche non lette
$sql = "SELECT COUNT(*) AS count FROM notifiche WHERE letta = 0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$notifiche_non_lette = $row['count'];

// Visualizza il badge solo se ci sono notifiche non lette
if ($notifiche_non_lette > 0) {
    echo '<span class="badge badge-danger">' . $notifiche_non_lette . '</span>';
}

// Chiudi la connessione al database
mysqli_close($conn);
?>
    </a>
    
    
    <?php
// Stabilisci la connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";

// Connessione al database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica della connessione
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

// Query per recuperare le notifiche
$sql = "SELECT * FROM notifiche";
$result = mysqli_query($conn, $sql);

// Verifica se ci sono notifiche
if (mysqli_num_rows($result) > 0) {
    echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">';

    // Itera sulle righe risultanti e visualizza le notifiche
    while ($row = mysqli_fetch_assoc($result)) {
        $messaggio = $row['messaggio'];
        echo '<a class="dropdown-item" href="#">' . $messaggio . '</a>';
    }

    echo '<div class="dropdown-divider"></div>';
    echo '<a class="dropdown-item" href="#">Visualizza tutte le notifiche</a>';
    echo '</div>';
} else {
    echo '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">';
    echo '<a class="dropdown-item" href="#">Nessuna notifica</a>';
    echo '</div>';
}

// Chiudi la connessione al database
mysqli_close($conn);
?>


        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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




<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <div class="sidebar-title">
          <br>
          <center><h5>CSSI</h5></center>
          <hr>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item">
            <b><center><a class="nav-link"><?php echo "Benvenuto, $cognome $nome" ?></a></center></b>
          </li>
          <li class="nav-item">
            <b><center><a class="nav-link"><?php echo "$ruolo" ?></a></center></b>
            <hr>
          </li>
          <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="gestioneAnagraficaMenu" role="button" data-toggle="collapse" data-target="#gestioneAnagraficaSubMenu" aria-expanded="false" aria-controls="gestioneAnagraficaSubMenu">
              Gestione Anagrafica
            </a>
            <div class="collapse" id="gestioneAnagraficaSubMenu">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellavolontari.php">Tabella Volontari</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="aggiungivolontario.php">Inserisci Volontario</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink" role="button" data-toggle="collapse" data-target="#altroMenu" aria-expanded="false" aria-controls="altroMenu">
              Gestione Pagamento
            </a>
            <div class="collapse" id="altroMenu">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellapagamenti.php">Tabella Pagamenti</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="aggiungipagamento.php">Aggiungi Pagamento</a>
                  </li>
              </ul>
            </div>
          </li>
                
                <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink1" role="button" data-toggle="collapse" data-target="#altroMenu1" aria-expanded="false" aria-controls="altroMenu1">
              Gestione Attività
            </a>
            <div class="collapse" id="altroMenu1">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellattivita.php">Tabella Attività</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="insertattivita.php">Inserisci Attività</a>
                </li>
              </ul>
            </div>
          </li>
		  
		   <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink2" role="button" data-toggle="collapse" data-target="#altroMenu2" aria-expanded="false" aria-controls="altroMenu2">
              Gestione Documenti
            </a>
            <div class="collapse" id="altroMenu2">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabelladocumenti.php">Tabella Documenti</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="aggiungidocumento.php">Aggiungi Documenti</a>
                </li>
              </ul>
            </div>
          </li>
		  
          <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink3" role="button" data-toggle="collapse" data-target="#altroMenu3" aria-expanded="false" aria-controls="altroMenu3">
              Gestione Estensioni Volontari
            </a>
            <div class="collapse" id="altroMenu3">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellappartenenza.php">Tabella Estensioni</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="aggiungiestensioni.php">Inserisci Estensioni</a>
                </li>
              </ul>
            </div>
          </li>

                <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink4" role="button" data-toggle="collapse" data-target="#altroMenu4" aria-expanded="false" aria-controls="altroMenu4">
              Gestione Trasferimento
            </a>
            <div class="collapse" id="altroMenu4">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellatrasferimenti.php">Tabella Trasferimento</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="aggiungitrasferimento.php">Inserisci Trasferimento</a>
                  </li>
              </ul>
            </div>
          </li>
		  
                <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink5" role="button" data-toggle="collapse" data-target="#altroMenu5" aria-expanded="false" aria-controls="altroMenu5">
              Gestione Riserve
            </a>
            <div class="collapse" id="altroMenu5">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellariserve.php">Tabella Riserve</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="aggiungiriserva.php">Inserisci Riserva</a>
                </li>
              </ul>
            </div>
          </li>
		  

        </ul>
      </div>
    </nav>
  </div>
</div>

<main role="main" class="col-md-5 ml-sm-auto col-lg-10 content">
        <!-- INIZIO FASE ALLERT HTML E PHP -->
  <div class="alert alert-info" role="alert">
  <i class="fa fa-info-circle" aria-hidden="true"></i>
  
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
    echo "Nessuna informazione disponibile.";
  }

  $conn->close();
  ?>
</div>

<div class="alert alert-info" role="alert">
  <center><b>  Da questa pagina potrai visionare lo stato delle riserve. </b>    
    </div>
</div>


<form method="GET" action="" class="mb-3">
    <div class="input-group mb-2">
<a href="aggiungiriserva.php" class="btn btn-outline-primary rounded mr-2">Aggiungi Riserva</a>

        <input type="text" name="cognome" class="form-control rounded mr-3" placeholder="Inserisci il cognome">
        <input type="text" name="nome" class="form-control rounded mr-3" placeholder="Inserisci il nome">
        <input type="text" name="inizio" class="form-control rounded mr-3" placeholder="Inizio">
		<input type="text" name="fine" class="form-control rounded mr-3" placeholder="Fine">
		
		<div class="input-group-append">
            <button type="submit" name="submit" class="btn btn-primary rounded">Cerca</button>
        </div>
    </div>
<?php
// Connessione al database (stesso codice di connessione presente nel tuo codice)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Controlla se è stato inviato il modulo di ricerca
if (isset($_GET['submit'])) {
    $cognome = $_GET['cognome'];
    $nome = $_GET['nome'];
	$inizio = $_GET['inizio'];
	$fine = $_GET['fine'];

    // Effettua la query per selezionare i volontari corrispondenti alla ricerca
    $sql = "SELECT * FROM riserve WHERE cognome LIKE '%$cognome%' AND nome LIKE '%$nome%' AND inizio LIKE '%$inizio%' AND fine LIKE '%$fine%'";
} else {
    // Se non è stata inviata la ricerca, seleziona tutti i volontari
    $sql = "SELECT * FROM riserve";
}

$result = $conn->query($sql);

// Verifica se ci sono risultati
if ($result->num_rows > 0) {

echo "<table id='tabella'>
                <table class='table table-bordered'>
                    <thead class='thead-dark'>
                        <tr class='fw-bold fs-6 text-gray-800'>
				<th><center>Stato</th>
				<th><center>Cognome</th>
				<th><center>Nome</th>
				<th><center>Inizio</th>
				<th><center>Fine</th>
				<th><center>Guarda</center></th>
				<th><center>Modifica</center></th>
				<th><center>Elimina</center></th>
        
        
					 </tr>
                    </thead>
                    <tbody>";

        // Itera sui risultati della query
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
                              echo "<td class='cell100 column2 align-middle text-center'>";
                              if ($row["attivo"] == "1") {
                              
                              echo "<a href='/include/abilita_riserva.php?id=" . $row["ID"] . "&azione=disabilita'>";
                              echo "<span class='badge bg-success'>Disabilita</button>";
                              echo "</a>";
                              } else {
                              echo "<a href='/include/abilita_riserva.php?id=" . $row["ID"] . "&azione=abilita'>";
                              echo "<span class='badge bg-success'>Abilita</button>";
                              echo "</a>";
                              
                              }

                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["cognome"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["nome"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["inizio"] . "</td>";
                            echo "<td class='cell100 column2 align-middle text-center '>" . $row["fine"] . "</td>";
            
							echo "<td class='cell100 column2 align-middle text-center'>";
							echo "<a href='guardariserva.php?id=" . $row["ID"] . "'>";
							echo "<i class='fa fa-eye'></i></a>";
							
							echo "<td class='cell100 column2 align-middle text-center'>";
							echo "<a href='modificappartenenza.php?id=" . $row["ID"] . "&nome=" . urlencode($row["nome"]) . "&cognome=" . urlencode($row["cognome"]) . "'>";
							echo "<i class='fas fa-pencil-alt'></i></a>";     
							
							echo "<td class='cell100 column2 align-middle text-center'>";
							echo "<a href='eliminappartenenza.php?id=" . $row["ID"] . "'>";
							echo "<i class='fas fa-trash-alt'></i></a>";
							
							echo "</td>";
							
							}
							} else {
							
							echo "<tr><td colspan='12'> Siamo spiacenti, ma non è stato possibile trovare alcuna appartenenza corrispondente. </td></tr>";
							}
							?>
  </tbody> </table> </div> </body> </html> </div>
  
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body> </html>







