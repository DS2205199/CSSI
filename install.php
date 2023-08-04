<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Pagina Installazione - Gestionale </title>
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
    <link href="css/style7.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    <link href="css/sidebar.css" rel="stylesheet"> </head>

    <!------------------------------------------------------------------------>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurazioni per la connessione al database
    $hostname = 'localhost';
    $username = 'root';
    $password = '';

    // Prendi i dati inseriti dall'utente
    $database_name = $_POST['db_name'];
    $email = $_POST['email'];
    $cognome = $_POST['cognome'];
    $nome = $_POST['nome'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
	$licenza = $_POST['licenza']; // Aggiunto il campo della licenza


// Funzione per controllare la validità della licenza
function isLicenzaValid($licenza) {
    // Connettiti al database delle licenze (sostituisci con le tue credenziali)
    $licenze_host = 'localhost';
    $licenze_username = 'root';
    $licenze_password = '';
    $licenze_database = 'licenze';

    // Esegui la query per verificare se la licenza è valida
    $conn_licenze = new mysqli($licenze_host, $licenze_username, $licenze_password, $licenze_database);
    $query = "SELECT COUNT(*) as count FROM licenza_valida WHERE licenza = '$licenza'";
    $result = $conn_licenze->query($query);
    $row = $result->fetch_assoc();

    // Chiudi la connessione
    $conn_licenze->close();

    // Restituisci true se la licenza è valida, altrimenti false
    return $row['count'] > 0;
}

// Controlla la validità della licenza
if (!isLicenzaValid($licenza)) {
    echo "Licenza non valida. Inserisci una licenza valida.";
} else {

    // Funzione per aggiornare il file config.php
    function update_config($database_name) {
        // Configurazioni del file config.php
        $config_file = 'config.php';
	
        // Nuovo contenuto del file con il nome del database aggiornato
        $new_content = '<?php' . PHP_EOL;
        $new_content .= '$db_host = "localhost";' . PHP_EOL;
        $new_content .= '$db_name = "' . $database_name . '";' . PHP_EOL;
        $new_content .= '$db_user = "root";' . PHP_EOL;
        $new_content .= '$db_pass = "";' . PHP_EOL;
        $new_content .= '?>';

        // Scrivi il nuovo contenuto nel file config.php
        if (file_put_contents($config_file, $new_content) !== false) {
            echo "File di configurazione aggiornato correttamente.";
        } else {
            echo "Errore nell'aggiornamento del file di configurazione.";
        }
    }

    // Chiamata alla funzione per aggiornare il file config.php con il nome del database
    update_config($database_name);

    // ... (Il resto del tuo codice di installazione)
	
      // Cripta la password prima di salvarla nel database
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
	$chashedPassword = password_hash($cpass, PASSWORD_DEFAULT);
        
		// Connessione al database MySQL
        $conn = new mysqli($hostname, $username, $password);

        // Verifica della connessione
        if ($conn->connect_error) {
            die('Errore nella connessione al database: ' . $conn->connect_error);
        }
  
  
       // Creazione del database
    $sql = "CREATE DATABASE IF NOT EXISTS $database_name";
    if ($conn->query($sql) === TRUE) {
        echo "Database '$database_name' creato con successo o già esistente.<br>";

        // Seleziona il database appena creato
        $conn->select_db($database_name);


            // Creazione della tabella (esempio di tabella utenti)
            $sql = "CREATE TABLE IF NOT EXISTS volontari (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codicefiscale VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    cpass VARCHAR(255) NOT NULL,
    email VARCHAR(50) NOT NULL,
    datadinascita VARCHAR(50) NOT NULL,
    comunedinascita VARCHAR(50) NOT NULL,
    provinciadinascita VARCHAR(50) NOT NULL,
    statodinascita VARCHAR(50) NOT NULL,
    indirizzodiresidenza VARCHAR(50) NOT NULL,
    comunediresidenza VARCHAR(50) NOT NULL,
    provinciadiresidenza VARCHAR(50) NOT NULL,
    statodiresidenza VARCHAR(50) NOT NULL,
    capdiresidenza VARCHAR(50) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    ruolo VARCHAR(50) NOT NULL,
    sesso VARCHAR(50) NOT NULL,
    registratoda DATETIME NOT NULL,
	stato enum('Online','Offline') NOT NULL DEFAULT 'Offline',
    note VARCHAR(50) NOT NULL,
    foto_profilo VARCHAR(50) NOT NULL,
    attivo TINYINT(1) NOT NULL,
    noteaggiuntive VARCHAR(50) NOT NULL
)";
            if ($conn->query($sql) === TRUE) {
                echo "Tabella 'volontari' creata con successo o già esistente.<br>";

                // Inserimento dell'utente con i dati forniti dall'utente

$sql = "INSERT INTO volontari (cognome, nome, email, pass, cpass) VALUES ('$cognome', '$nome', '$email', '$hashedPassword', '$chashedPassword')";
                if ($conn->query($sql) === TRUE) {
                    echo "Utente inserito con successo.<br>";
                } else {
                    echo "Errore nell'inserimento dell'utente: " . $conn->error . "<br>";
                }
            } else {
                echo "Errore nella creazione della tabella: " . $conn->error . "<br>";
            }
			
			
			
			// Creazione della tabella "Videos"
        $sql = "CREATE TABLE IF NOT EXISTS videos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    video_title VARCHAR(50) NOT NULL,
    video_url VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    publish_date DATE NOT NULL,
    video_file VARCHAR(50) NOT NULL,
    video_type VARCHAR(50) NOT NULL
)";

                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'videos' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'videos': " . $conn->error . "<br>";
        }
		
		
		// Creazione della tabella "Trasferimenti"
        $sql = "CREATE TABLE IF NOT EXISTS trasferimenti (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    destinazione VARCHAR(50) NOT NULL,
    datarichiesta VARCHAR(50) NOT NULL,
    sede DATE NOT NULL,
    attivo TINYINT(1) NOT NULL
	)";

                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Trasferimenti' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Trasferimenti': " . $conn->error . "<br>";
        }
		
		// Creazione della tabella "Sesso"
        $sql = "CREATE TABLE IF NOT EXISTS sesso (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sesso VARCHAR(50) NOT NULL
	)";

                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Sesso' creata con successo o già esistente.<br>";
        
		// Inserimento delle voci nella tabella "sesso"
            $sql = "INSERT INTO sesso (sesso) VALUES ('Maschio'), ('Femmina')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Sesso'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Sesso': " . $conn->error . "<br>";
            }
        } else {
            echo "Errore nella creazione della tabella 'Sesso': " . $conn->error . "<br>";
        }
		
		// Creazione della tabella "Sedi"
        $sql = "CREATE TABLE IF NOT EXISTS sedi (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nomesede VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Sedi' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "sesso"
            $sql = "INSERT INTO sedi (nomesede) VALUES ('Sede di Aci Catena'), ('Sede di Acireale'), ('Sede di Aci Bonnacorsi'), ('Sede di San Nicolo'), ('Sede di Aci San Filippo')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Sedi'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Sedi': " . $conn->error . "<br>";
            }
        } else {
            echo "Errore nella creazione della tabella 'Sedi': " . $conn->error . "<br>";
		}
	   
	   // Creazione della tabella "Ruoli"
        $sql = "CREATE TABLE IF NOT EXISTS ruoli (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ruolo VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Ruoli' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "sesso"
            $sql = "INSERT INTO ruoli (ruolo) VALUES ('Volontario'), ('Referenti'), ('Responsabile'), ('Presidente'), ('Segretaria')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Ruoli'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Ruoli': " . $conn->error . "<br>";
            }
        } else {
            echo "Errore nella creazione della tabella 'Ruoli': " . $conn->error . "<br>";
        }
		
		// Creazione della tabella "Riserve"
        $sql = "CREATE TABLE IF NOT EXISTS riserve (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    inzio VARCHAR(50) NOT NULL,
    fine VARCHAR(50) NOT NULL,
    attivo TINYINT(1) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Riserve' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Riserve': " . $conn->error . "<br>";
        }
    
	// Creazione della tabella "Ricevute"
        $sql = "CREATE TABLE IF NOT EXISTS ricevute (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	ricevutan VARCHAR(50) NOT NULL,
    numero VARCHAR(50) NOT NULL,
    anno VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    importo VARCHAR(50) NOT NULL,
    registratoda VARCHAR(50) NOT NULL,
    datadipagamento VARCHAR(50) NOT NULL,
	oradipagamento VARCHAR(50) NOT NULL,
	descrizione VARCHAR(50) NOT NULL,
	descrizioneaggiuntive VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Ricevute' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Ricevute': " . $conn->error . "<br>";
        }
    
	// Creazione della tabella "Responsabili"
        $sql = "CREATE TABLE IF NOT EXISTS responsabili (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Responsabili' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Resposabili': " . $conn->error . "<br>";
        }
    
	// Creazione della tabella "Referenti"
        $sql = "CREATE TABLE IF NOT EXISTS referenti (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Referenti' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Referenti': " . $conn->error . "<br>";
        }
   
   // Creazione della tabella "QuotePagate"
        $sql = "CREATE TABLE IF NOT EXISTS quotepagate (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(50) NOT NULL,
    anno VARCHAR(50) NOT NULL,
	cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    importo VARCHAR(50) NOT NULL,
    registratoda VARCHAR(50) NOT NULL,
    descrizione VARCHAR(50) NOT NULL,
    descrizioneaggiuntive VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    dataeoradipagamento VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'QuotePagate' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'QuotePagate': " . $conn->error . "<br>";
        }
    
	// Creazione della tabella "Organizzatori"
        $sql = "CREATE TABLE IF NOT EXISTS organizzatori (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nomeorganizzatore VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Organizzatorìi' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "Organizzatori"
            $sql = "INSERT INTO organizzatori (nomeorganizzatore) VALUES ('CSSI')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Organizzatori'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Organizzatori': " . $conn->error . "<br>";
            }
        } else {
            echo "Errore nella creazione della tabella 'Organizzatori': " . $conn->error . "<br>";
         }
    
	// Creazione della tabella "Notifiche"
        $sql = "CREATE TABLE IF NOT EXISTS notifiche (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attivita_id VARCHAR(50) NOT NULL,
    messaggio VARCHAR(50) NOT NULL,
	created_at VARCHAR(50) NOT NULL,
    letta TINYINT(1) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Notifiche' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Notifiche': " . $conn->error . "<br>";
        }

		// Creazione della tabella "News"
        $sql = "CREATE TABLE IF NOT EXISTS news (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
	nomenews VARCHAR(50) NOT NULL,
    publish_date VARCHAR(50) NOT NULL,
	descrizione VARCHAR(50) NOT NULL

	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'News' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'News': " . $conn->error . "<br>";
        }
   
   // Creazione della tabella "Informazioni"
        $sql = "CREATE TABLE IF NOT EXISTS informazioni (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
    dataeora VARCHAR(50) NOT NULL,
	info VARCHAR(50) NOT NULL,
	link VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Informazioni' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Informazioni': " . $conn->error . "<br>";
        }
   
   // Creazione della tabella "Documenti"
        $sql = "CREATE TABLE IF NOT EXISTS documenti (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomedocumento VARCHAR(50) NOT NULL,
    linkdocumento VARCHAR(50) NOT NULL,
	scaricadocumento VARCHAR(50) NOT NULL,
    registratoda VARCHAR(50) NOT NULL,
	dataeoradinserimento VARCHAR(50) NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Documenti' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Documenti': " . $conn->error . "<br>";
        }
    
	// Creazione della tabella "descrizione"
        $sql = "CREATE TABLE IF NOT EXISTS descrizione (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            descrizione VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Descrizione' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "Organizzatori"
            $sql = "INSERT INTO descrizione (descrizione) VALUES ('Pagamento con Bonifico'), ('Pagamento in Contanti'), ('Pagamento con Carta Prepagata / Credito'), ('Pagamento con Paypal') ";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Descrizione'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Descrizione': " . $conn->error . "<br>";
            }
       
	    // Creazione della tabella "Attività"
        $sql = "CREATE TABLE IF NOT EXISTS attivita (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomeattivita VARCHAR(50) NOT NULL,
    posizioneservizio VARCHAR(50) NOT NULL,
	nomereferente VARCHAR(50) NOT NULL,
    areadintervento VARCHAR(50) NOT NULL,
	organizzatore VARCHAR(50) NOT NULL,
	apertoa VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL,
	informazioniattivita VARCHAR(50) NOT NULL,
	dataeoradelservizio VARCHAR(50) NOT NULL,
	minpartecipanti VARCHAR(50) NOT NULL,
	maxpartecipanti VARCHAR(50) NOT NULL

	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Attivita' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Attivita': " . $conn->error . "<br>";
         }
        } else {
            echo "Errore nella creazione della tabella 'Attivita': " . $conn->error . "<br>";
        }

// Creazione della tabella "Area di Intervento"
        $sql = "CREATE TABLE IF NOT EXISTS areadintervento (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            areadintervento VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Area di Intervento' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "Organizzatori"
            $sql = "INSERT INTO areadintervento (areadintervento) VALUES ('Sicurezza'), ('Emergenza') ";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Area di Intervento'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Area di Intervento': " . $conn->error . "<br>";
            }
       
	   // Creazione della tabella "Appartenenza"
        $sql = "CREATE TABLE IF NOT EXISTS appartenenze (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cognome VARCHAR(50) NOT NULL,
    nome VARCHAR(50) NOT NULL,
	inizio VARCHAR(50) NOT NULL,
    fine VARCHAR(50) NOT NULL,
	tipo VARCHAR(50) NOT NULL,
	sede VARCHAR(50) NOT NULL,
    attivo TINYINT(1) NOT NULL

	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Appartenenza' creata con successo o già esistente.<br>";
        } else {
            echo "Errore nella creazione della tabella 'Appartenenza': " . $conn->error . "<br>";
        }
        } else {
		 echo "Errore nella creazione della tabella 'Appartenenza': " . $conn->error . "<br>";
		 }
		 
		 // Creazione della tabella "Aperto A"
        $sql = "CREATE TABLE IF NOT EXISTS aperto (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            apertoa VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Aperto' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "Organizzatori"
            $sql = "INSERT INTO aperto (apertoa) VALUES ('Tutti i Volontari - CSSI')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Aperto'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Aperto': " . $conn->error . "<br>";
            }
			} else {
			   echo "Errore nell'inserimento delle voci nella tabella 'Aperto': " . $conn->error . "<br>";
			}
			
			// Creazione della tabella "Anni"
        $sql = "CREATE TABLE IF NOT EXISTS anni (
            ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            anno VARCHAR(50) NOT NULL
        )";
                if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Anni' creata con successo o già esistente.<br>";
        // Inserimento delle voci nella tabella "Organizzatori"
            $sql = "INSERT INTO anni (anno) VALUES ('2023/24')";
            if ($conn->query($sql) === TRUE) {
                echo "Voci inserite con successo nella tabella 'Anni'.<br>";
            } else {
                echo "Errore nell'inserimento delle voci nella tabella 'Anni': " . $conn->error . "<br>";
            }
			} else {
				echo "Errore nell'inserimento delle voci nella tabella 'Anni': " . $conn->error . "<br>";
			}
			
			// Creazione della tabella "Accessi"
        $sql = "CREATE TABLE IF NOT EXISTS accessi (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
	nome VARCHAR(50) NOT NULL,
    accesso_negato TINYINT(1) NOT NULL,
	nomePagina VARCHAR(50) NOT NULL,
	Indirizzo_ip VARCHAR(50) NOT NULL,
	timestamp DATETIME NOT NULL
	)";

                               if ($conn->query($sql) === TRUE) {
            echo "Tabella 'Attivita' creata con successo o già esistente.<br>";
        
		
		} else {
            echo "Errore nella creazione della tabella 'Attivita': " . $conn->error . "<br>";
        }
	}
	// Redirect a una nuova pagina dopo qualche secondo
header("refresh:0;url=index.php");
exit;

    // Chiudi la connessione al database
    $conn->close();
	}
}
?>


<form method="post" onsubmit="showLoadingAnimation()">

<!-- Aggiungi questo div per l'animazione di caricamento -->
<div id="loadingAnimation" style="display: none; text-align: center;">
  <i class="fa fa-spinner fa-spin fa-3x"></i>
  <p>Caricamento in corso...</p>
</div>

<br><br><br><br><br><br><br><br><br><br>

<div class="container mt-3">
  <form>
    <div class="row jumbotron box8">
      <div class="col-sm-12 mx-t3 mb-4">
        <h2 class="text-center text-info">Register</h2>
      </div>
      <div class="col-sm-6 form-group">
        <label for="name-f">Cognome</label>
        <input type="text" class="form-control" name="cognome" id="name-f" placeholder="Inserisci il tuo Cognome." required>
      </div>
      <div class="col-sm-6 form-group">
        <label for="name-l">Nome</label>
        <input type="text" class="form-control" name="nome" id="name-l" placeholder="Inserisci il tuo Nome." required>
      </div>
			
<form role="form">
  <div class="form-group">
    <label for="nomedatabase">Nome Database</label>
    <input type="text" name="db_name" placeholder="Jason Doe" required class="form-control">
  </div>

  <div class="col-sm-12 form-group">
        <label for="name-l">Email</label>
        <input type="text" class="form-control" name="email" id="name-l" placeholder="Inserisci il tuo Nome." required>
      </div>

  <div class="col-sm-6 form-group">
        <label for="name-f">Password</label>
        <input type="password" class="form-control" name="pass" id="name-f" placeholder="Inserisci la Tua Password." required>
      </div>
      <div class="col-sm-6 form-group">
        <label for="name-l">Conferma Password</label>
        <input type="password" class="form-control" name="cpass" id="name-l" placeholder="Inserisci il tuo Nome." required>
      </div>
  
  <div class="form-group">
    <label for="Licenza">Numero / Codice Licenza:</label>
    <input type="text" name="licenza" placeholder="Codice Licenza" class="form-control" required>
</div>
  
  <hr>
  
              <button type="submit" class="subscribe btn btn-primary btn-block rounded-pill shadow-sm"> Installa  </button>
            </form>
          </div>
          <!-- End -->

          <!-- Paypal info -->
          <div id="nav-tab-paypal" class="tab-pane fade">
            <p>Paypal is easiest way to pay online</p>
            <p>
              <button type="button" class="btn btn-primary rounded-pill"><i class="fa fa-paypal mr-2"></i> Log into my Paypal</button>
            </p>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
          </div>
          <!-- End -->

          <!-- bank transfer info -->
          <div id="nav-tab-bank" class="tab-pane fade">
            <h6>Bank account details</h6>
            <dl>
              <dt>Bank</dt>
              <dd> THE WORLD BANK</dd>
            </dl>
            <dl>
              <dt>Account number</dt>
              <dd>7775877975</dd>
            </dl>
            <dl>
              <dt>IBAN</dt>
              <dd>CZ7775877975656</dd>
            </dl>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
          </div>
          <!-- End -->
        </div>
        <!-- End -->

      </div>
    </div>
  </div>
</div>


       
<!-- Aggiungi il codice JavaScript -->
<script>
  function showLoadingAnimation() {
    document.getElementById("loadingAnimation").style.display = "block";
  }

  function hideLoadingAnimation() {
    document.getElementById("loadingAnimation").style.display = "none";
  }
</script>
