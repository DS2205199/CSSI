<?php
require_once 'config.php';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: ". $conn->connect_error);
}

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati dal form
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    // Verifica se l'utente esiste nel database
    $sql = "SELECT * FROM volontari WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // L'utente esiste nel database, verifica la password
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row["pass"])) {
            // La password è corretta, crea la sessione
            
            // Registra l'accesso
            $accessoID = uniqid() . "-" . date("YmdHis");
            $email = $row["email"]; // Recupera l'email dell'utente
$cognome = $row["cognome"]; // Recupera il cognome dell'utente
$nome = $row["nome"]; // Recupera il nome dell'utente
$timestamp = date("Y-m-d H:i:s"); // Ottiene la data e l'orario corrente nel formato desiderato
$indirizzoIP = $_SERVER['REMOTE_ADDR'];

$accessoSql = "INSERT INTO accessi (email, cognome, nome, timestamp, indirizzo_ip) VALUES ('$email', '$cognome', '$nome', '$timestamp', '$indirizzoIP')";
$conn->query($accessoSql);




            if (!$row["attivo"]) {
                // L'utente non è attivo, mostra un messaggio di errore o reindirizza
                echo "Accesso negato: Volontario non attivo / Non hai pagato la Quota Annuale per cui sei stato buttato fuori";
                exit();
            } else {
                session_start();
                $_SESSION["ID"] = $row["ID"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["cognome"] = $row["cognome"];
                $_SESSION["ruolo"] = $row["ruolo"];
                $_SESSION["nome"] = $row["nome"];
                $_SESSION["registratoda"] = $row["registratoda"];
                $_SESSION["codicefiscale"] = $row["codicefiscale"];
                $_SESSION["datadinascita"] = $row["datadinascita"];
                $_SESSION["comunedinascita"] = $row["comunedinascita"];
                $_SESSION["provinciadinascita"] = $row["provinciadinascita"];
                $_SESSION["statodinascita"] = $row["statodinascita"];
                $_SESSION["indirizzodiresidenza"] = $row["indirizzodiresidenza"];
                $_SESSION["comunediresidenza"] = $row["comunediresidenza"];
                $_SESSION["provinciadiresidenza"] = $row["provinciadiresidenza"];
                $_SESSION["statodiresidenza"] = $row["statodiresidenza"];
                $_SESSION["capdiresidenza"] = $row["capdiresidenza"];
                $_SESSION["telefono"] = $row["telefono"];
                $_SESSION["ruolo"] = $row["ruolo"];
                $_SESSION["sesso"] = $row["sesso"];
                $_SESSION["registratoda"] = $row["registratoda"];
                $_SESSION["note"] = $row["note"];
                $_SESSION["attivo"] = $row["attivo"];

                // Aggiorna lo stato a "online" nel database
                $sqlUpdateStatus = "UPDATE volontari SET stato = 'online' WHERE ID=" . $row["ID"];
                $conn->query($sqlUpdateStatus);

                // Verifica il ruolo dell'utente
            if ($row["ruolo"] === 'Volontario') {
                // Reindirizza il volontario a una pagina diversa per i volontari
                header("Location: ../utente/dashboard.php");
                exit();
            } else {
                // Reindirizza l'utente amministratore alla pagina di amministrazione
                header("Location: ../amministrazione/dashboard.php");
                exit();
            }
        }
    } else {
        // La password è errata, mostra un messaggio di errore
        echo "Password errata";
    }
} else {
    // L'utente non esiste nel database, mostra un messaggio di errore
    echo "Utente non trovato";
}
}
$conn->close();
?>


<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Paper Stack</title>
<link rel="stylesheet" type="text/css" href="CSS/style.css" />
</head>
<body>

<br>
<center><h1>Pagina di Accesso</h1></center>

<a href="">
    <center><img src="src/images/logo.png" alt="Logo" height="200"></center>
</a>

<br>
<div class="container">
	<section id="content">
    <form method="post" action="">
			<h1>Sign In</h1>
            <?php
        // Verifica se il form è stato inviato e la password è errata
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
            echo "<b><p style='color: red; text-align: center;'>Password errata</p></b>";
        }
        ?>
        <br>
            Inserisci la tua email e la password che hai fornito alla registrazione.
			<div>
            <br>
				<input type="text" placeholder="Email" required="" name="email" id="username" />
			</div>
			<div>
				<input type="password" placeholder="Password" required="" name="pass" id="password" />
			</div>
			<div>
				<input type="submit" name="login" value="Accedi">
				<a href="#">Hai perso la password?</a>
			</div>
		</form><!-- form -->
	</section><!-- content -->
</div><!-- container -->
<center>Non si tratta del tuo computer? Utilizza una finestra di navigazione privata per accedere. Scopri di più</center>

    
<a href="https://play.google.com/store/apps/details?id=com.example.app">
    <center><img src="https://play.google.com/intl/en_us/badges/images/generic/en-play-badge.png" alt="Get it on Google Play" height="60"></center>
</a>

</body>

<center>©2023 Il Progetto CSSI<p>
    <br>
<span style="color: red;">Comitato Sicurezza Stradale Italiana</span>
</html>