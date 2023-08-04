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


?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Modifica Video - Comitato Sicurezza Stradale </title>
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
		  
		   <li class="nav-item">
            <a class="nav-link">
            <p>
         
		  
		  <li class="nav-item">
            <a class="nav-link dropdown-toggle" href="#" id="altroMenuLink6" role="button" data-toggle="collapse" data-target="#altroMenu6" aria-expanded="false" aria-controls="altroMenu6">
              Gestione Video
            </a>
            <div class="collapse" id="altroMenu6">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link" href="tabellavideo.php">Tabella Video</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="inseriscivideo.php">Aggiungi Video</a>
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

// Controllo se il modulo di aggiornamento è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupero dei dati dal modulo
    $id = $_POST['id']; // ID dell'utente da aggiornare
    $video_title = $_POST["video_title"];
    $video_url = $_POST["video_url"];
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $publish_date = $_POST['publish_date'];
   
    // Esecuzione dell'aggiornamento dei dati nella tabella
    $query = "UPDATE videos SET video_title = '$video_title', video_url = '$video_url', last_name = '$last_name', first_name = '$first_name', publish_date = '$publish_date' WHERE id = '$id'";

    $result = mysqli_query($conn, $query);
    if ($result) {
      echo '<div class="alert alert-primary" role="alert">';
      echo 'Video aggiornato con successo!';
      echo '</div>';
    } else {
      echo '<div class="alert alert-danger" role="alert">';
      echo "Errore durante l'aggiornamento dei dati: " . $conn->error;
      echo '</div>';
    }
  }
  
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
$query = "SELECT * FROM videos WHERE id = $id";
$result = mysqli_query($conn, $query);

// Controllo se la query ha prodotto risultati
if (mysqli_num_rows($result) > 0) {
    // Recupero dei dati dal risultato della query
    $row = mysqli_fetch_assoc($result);
  } else {
    echo "";
}

// Chiusura della connessione al database
mysqli_close($conn);
?>


<div class="alert alert-info" role="alert">
  <center><b> Benvenuto nella pagina dedicata alla modifica dei Video </b>
</div>


   
   <form method="post" action="" enctype="multipart/form-data">

  <div class="row">
  <div class="col-lg-9">
  <div class="card w-100 mb-3">
  <div class="card-header"><center>Modifica Video: <?php echo " " . $row["video_title"],  " "  . "<br>";;?></span></h4></center></div>
  <div class="card-body">
    <h5 class="card-title"></h5>

<div class="row mb-2">
                <div class="col">
                  
                  <input type="hidden" class="form-control" id="ID" name="id" placeholder="id" value="<?php echo "" . $row["id"], "" ?>" readonly>
                </div>
				</div>
				
				<div class="row mb-2">
               
                <div class="col">
                  <label for="cognome" class="form-label"> Cognome </label>
                  <input class="form-control" name="last_name" value="<?php echo "" . $row["last_name"], "" ?>">
                </div>
                <div class="col">
                  <label for="nome" class="form-label">Nome</label>
                  <input class="form-control" name="first_name" value="<?php echo "" . $row["first_name"], "" ?>">
                </div>
              </div>
			  
              <div class="row mb-2">
                <div class="col">
                  <label for="numero" class="form-label"> Titolo Video </label>
                  <input type="text" class="form-control" id="video_title" name="video_title" placeholder="Titolo Video" value="<?php echo "" . $row["video_title"], "" ?>">
                </div>
                
                <div class="col">
                  <label for="numero" class="form-label">Video URL</label>
                  <input type="text" class="form-control" id="video_url" name="video_url" placeholder="video_url" value="<?php echo "" . $row["video_url"], "" ?>">
                </div>
  </div>
</div>
<div class="card-footer text-end">
    <button type="submit" name="submit" class="btn btn-primary"> <i class="fa fa-paper-plane" aria-hidden="true"></i> Modifica Video </button>


    </div>
</div>
</div>


<div class="col-lg-3">
    <div class="card mb-4">
    <div class="card-header"><center>Inserisci Informazioni</center></div>
    <div class="card-body">
	
                  <label for="registratoda" class="form-label"> Data di Caricamento </label>
                  <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" placeholder="publish_date" required value="<?php echo "" . $row["publish_date"], "" ?>">
                  </div>
                  </div>










<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    </body>
</html>