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
$ID = $_SESSION["nome"];
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

?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Il Tuo Profilo - Comitato Sicurezza Stradale</title>
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

<div class="row gutters-sm">
            <div class="col-md-3 mb-3">
              <div class="card" style="margin: 25px">
                <div class="card-body">
                <center><h1><b>Il Tuo Profilo</b></h1></center>
                <br>
                  <div class="d-flex flex-column align-items-center text-center">

                  <?php
// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cssi";
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica la connessione al database
if (!$conn) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

// Recupera il percorso dell'immagine dall'ID dell'utente
$query = "SELECT foto_profilo FROM volontari WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $foto_profilo = $row['foto_profilo'];

    // Mostra l'immagine
    echo '<img src="' . $foto_profilo . '" alt="Immagine utente" width="200" height="200"> ';
} else {
    echo "L'utente o l'immagine non esistono.";
}

// Chiudi la connessione al database
mysqli_close($conn);
?>
                                        
                    <div class="mt-3">
                      <h4><b><?php echo "$nome $cognome!"; ?> </h4></b>
                      <br>
                      <p class="text-secondary mb-1"><?php echo "Ruolo del Volontario: <b>$ruolo</b>"; ?> </p>
                      <p class="text-muted font-size-sm"><?php echo "Registrato Dal: <b>$registratoda</b>"; ?> </p>
                     <br>                     
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mt-3" style="margin: 25px">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <label> Codice Fiscale </label>
                    <span class="text-secondary"><?php echo " <b>$codicefiscale</b>"; ?> </p></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <label> Cognome e Nome </label>
                    <span class="text-secondary"><?php echo "<b>$nome $cognome</b>"; ?> </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <label> Data di Nascita </label>
                    <span class="text-secondary"><?php echo "<b>$datadinascita</b>"; ?> </span>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <label> Ruolo </label>
                    <span class="text-secondary"><?php echo "<b>$ruolo</b>"; ?> </span>
                  </li>
                </ul>
              </div>
            </div>

            <div class="col-md-8">
              <div class="card mb-3" style="margin: 25px">
              <div class="card-header"><center><b>Dati Anagrafici</b></center>
              <br>
             <center><p><b>Le informazioni di base non possono essere modificate direttamente per motivi di sicurezza. Queste sono qui elencate: <p></div></b></center>
                <div class="card-body">
               
                <br>

                <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Codice Fiscale</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$codicefiscale</b>"; ?>
                    </div>
                  </div>
                  
                  <br>
                  
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Cognome e Nome</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$nome $cognome</b>"; ?>
                    </div>
                  </div>
                 
                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Data di Nascita </h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$datadinascita!</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Ruolo</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$ruolo</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Registrato dal</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$registratoda</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Note</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$note</b>"; ?>
                    </div>
                  </div>
                  
                  <hr>
                  <center><p> <b> Le informazioni qui di seguito possono essere modificate direttamente. Queste sono qui elencate: </p></center></b>
                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$email</b>"; ?>
                    </div>
                  </div>
                  
                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Comune di Nascita</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$comunedinascita</b>"; ?>
                    </div>
                  </div>
                 
                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Provincia di Nascita</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$provinciadinascita</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Stato di Nascita</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$statodinascita</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Indirizzo di Residenza</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$indirizzodiresidenza</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Comune di Residenza</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$comunediresidenza</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Provincia di Residenza</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$provinciadiresidenza</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Stato di Residenza</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$statodiresidenza</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Cap di Residenza</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$capdiresidenza</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Telefono</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$telefono</b>"; ?>
                    </div>
                  </div>

                  <br>

                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Sesso</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                    <?php echo "<b>$sesso</b>"; ?>
                    </div>
                  </div>

                  <hr>

                  <div class="row">
                    <div class="col-sm-12">
                    <a href="editprofile.php" class="btn btn-primary">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Modifica Profilo</a>

                    </div>
                  </div>
                </div>
              </div>

    </main>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    </body>
</html>

