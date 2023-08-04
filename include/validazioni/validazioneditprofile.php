<?php
$errors = []; // Array per memorizzare gli errori

if (isset($_POST['cognome'])) {
    $cognome = $_POST['cognome'];

    if (empty($cognome)) {
        $errors[] = "Il campo 'Cognome' è obbligatorio.";
    } else {
        $cognome = mysqli_real_escape_string($conn, $cognome);
    }
}

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];

    if (empty($nome)) {
        $errors[] = "Il campo 'Nome' è obbligatorio.";
    } else {
        $nome = mysqli_real_escape_string($conn, $nome);
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (empty($email)) {
        $errors[] = "Il campo 'Email' è obbligatorio.";
    } else {
        $email = mysqli_real_escape_string($conn, $email);
    }
}

if (isset($_POST['comunedinascita'])) {
    $comunedinascita = $_POST['comunedinascita'];

    if (empty($comunedinascita)) {
        $errors[] = "Il campo 'Comune di Nascita' è obbligatorio.";
    } else {
        $comunedinascita = mysqli_real_escape_string($conn, $comunedinascita);
    }
}

if (isset($_POST['provinciadinascita'])) {
    $provinciadinascita = $_POST['provinciadinascita'];

    if (empty($provinciadinascita)) {
        $errors[] = "Il campo 'Provincia di Nascita' è obbligatorio.";
    } else {
        $provinciadinascita = mysqli_real_escape_string($conn, $provinciadinascita);
    }
}

if (isset($_POST['statodinascita'])) {
    $statodinascita = $_POST['statodinascita'];

    if (empty($statodinascita)) {
        $errors[] = "Il campo 'Stato di Nascita' è obbligatorio.";
    } else {
        $statodinascita = mysqli_real_escape_string($conn, $statodinascita);
    }
}

if (isset($_POST['statodiresidenza'])) {
    $statodiresidenza = $_POST['statodiresidenza'];

    if (empty($statodiresidenza)) {
        $errors[] = "Il campo 'Stato di Residenza' è obbligatorio.";
    } else {
        $statodiresidenza = mysqli_real_escape_string($conn, $statodiresidenza);
    }
}

if (isset($_POST['indirizzodiresidenza'])) {
    $indirizzodiresidenza = $_POST['indirizzodiresidenza'];

    if (empty($indirizzodiresidenza)) {
        $errors[] = "Il campo 'Indirizzo di Residenza' è obbligatorio.";
    } else {
        $indirizzodiresidenza = mysqli_real_escape_string($conn, $indirizzodiresidenza);
    }
}

if (isset($_POST['comunediresidenza'])) {
    $comunediresidenza = $_POST['comunediresidenza'];

    if (empty($comunediresidenza)) {
        $errors[] = "Il campo 'Comune di Residenza' è obbligatorio.";
    } else {
        $comunediresidenza = mysqli_real_escape_string($conn, $comunediresidenza);
    }
}

if (isset($_POST['provinciadiresidenza'])) {
    $provinciadiresidenza = $_POST['provinciadiresidenza'];

    if (empty($provinciadiresidenza)) {
        $errors[] = "Il campo 'Provincia di Residenza' è obbligatorio.";
    } else {
        $provinciadiresidenza = mysqli_real_escape_string($conn, $provinciadiresidenza);
    }
}

if (isset($_POST['capdiresidenza'])) {
    $capdiresidenza = $_POST['capdiresidenza'];

    if (empty($capdiresidenza)) {
        $errors[] = "Il campo 'CAP di Residenza' è obbligatorio.";
    } else {
        $capdiresidenza = mysqli_real_escape_string($conn, $capdiresidenza);
    }
}

if (isset($_POST['telefono'])) {
    $telefono = $_POST['telefono'];

    if (empty($telefono)) {
        $errors[] = "Il campo 'Telefono' è obbligatorio.";
    } else {
        $telefono = mysqli_real_escape_string($conn, $telefono);
    }
}

if (isset($_POST['ruolo'])) {
    $ruolo = $_POST['ruolo'];

    if (empty($ruolo)) {
        $errors[] = "Il campo 'Ruolo' è obbligatorio.";
    } else {
        $ruolo = mysqli_real_escape_string($conn, $ruolo);
    }
}

if (isset($_POST['sesso'])) {
    $sesso = $_POST['sesso'];

    if (empty($sesso)) {
        $errors[] = "Il campo 'Sesso' è obbligatorio.";
    } else {
        $sesso = mysqli_real_escape_string($conn, $sesso);
    }
}
// Restituisce l'array di errori
return $errors;
?>
