<?php
$errors = []; // Array per memorizzare gli errori

if (isset($_POST['codicefiscale'])) {
    $codicefiscale = $_POST['codicefiscale'];

    if (empty($codicefiscale)) {
        $errors[] = "Il campo 'Codice Fiscale' è obbligatorio.";
    }
}

if (isset($_POST['cognome'])) {
    $cognome = $_POST['cognome'];

    if (empty($cognome)) {
        $errors[] = "Il campo 'Cognome' è obbligatorio.";
    }
}

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];

    if (empty($nome)) {
        $errors[] = "Il campo 'Nome' è obbligatorio.";
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (empty($email)) {
        $errors[] = "Il campo 'Email' è obbligatorio.";
    }
}

if (isset($_POST['datadinascita'])) {
    $datadinascita = $_POST['datadinascita'];

    // Validazione della data
    if (empty($datadinascita)) {
        $errors[] = "Il campo 'Data e Ora' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $datadinascita = strtotime($datadinascita);
        if (!$datadinascita) {
            $errors[] = "Il campo 'Data e Ora' non è nel formato corretto.";
        }
    }
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];

    if (empty($password)) {
        $errors[] = "Il campo 'Password' è obbligatorio.";
    }
}

if (isset($_POST['cpassword'])) {
    $cpassword = $_POST['cpassword'];

    if (empty($cpassword)) {
        $errors[] = "Il campo 'Conferma Password' è obbligatorio.";
    }
}

if (isset($_POST['comunedinascita'])) {
    $comunedinascita = $_POST['comunedinascita'];

    if (empty($comunedinascita)) {
        $errors[] = "Il campo 'Comune di Nascita' è obbligatorio.";
    }
}

if (isset($_POST['provinciadinascita'])) {
    $provinciadinascita = $_POST['provinciadinascita'];

    if (empty($provinciadinascita)) {
        $errors[] = "Il campo 'Provincia di Nascita' è obbligatorio.";
    }
}

if (isset($_POST['statodinascita'])) {
    $statodinascita = $_POST['statodinascita'];

    if (empty($statodinascita)) {
        $errors[] = "Il campo 'Stato di Nascita' è obbligatorio.";
    }
}

if (isset($_POST['statodiresidenza'])) {
    $statodiresidenza = $_POST['statodiresidenza'];

    if (empty($statodiresidenza)) {
        $errors[] = "Il campo 'Stato di Residenza' è obbligatorio.";
    }
}

if (isset($_POST['indirizzodiresidenza'])) {
    $indirizzodiresidenza = $_POST['indirizzodiresidenza'];

    if (empty($indirizzodiresidenza)) {
        $errors[] = "Il campo 'Indirizzo di Residenza' è obbligatorio.";
    }
}

if (isset($_POST['comunediresidenza'])) {
    $comunediresidenza = $_POST['comunediresidenza'];

    if (empty($comunediresidenza)) {
        $errors[] = "Il campo 'Comune di Residenza' è obbligatorio.";
    }
}

if (isset($_POST['provinciadiresidenza'])) {
    $provinciadiresidenza = $_POST['provinciadiresidenza'];

    if (empty($provinciadiresidenza)) {
        $errors[] = "Il campo 'Provincia di Residenza' è obbligatorio.";
    }
}

if (isset($_POST['capdiresidenza'])) {
    $capdiresidenza = $_POST['capdiresidenza'];

    if (empty($capdiresidenza)) {
        $errors[] = "Il campo 'CAP di Residenza' è obbligatorio.";
    }
}

if (isset($_POST['telefono'])) {
    $telefono = $_POST['telefono'];

    if (empty($telefono)) {
        $errors[] = "Il campo 'Telefono' è obbligatorio.";
    }
}

if (isset($_POST['ruolo'])) {
    $ruolo = $_POST['ruolo'];

    if (empty($ruolo)) {
        $errors[] = "Il campo 'Ruolo' è obbligatorio.";
    }
}

if (isset($_POST['sesso'])) {
    $sesso = $_POST['sesso'];

    if (empty($sesso)) {
        $errors[] = "Il campo 'Sesso' è obbligatorio.";
    }
}

if (isset($_POST['registratoda'])) {
    $registratoda = $_POST['registratoda'];

    if (empty($registratoda)) {
        $errors[] = "Il campo 'Registrato Da' è obbligatorio.";
    }
}

// Restituisce l'array di errori
return $errors;
?>
