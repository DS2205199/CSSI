<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['nomeattivita'])) {
    $nomeattivita = $_POST['nomeattivita'];

    if (empty($nomeattivita)) {
        $errors[] = "Il campo 'Nome Attivita' è obbligatorio.";
    }
}

if (isset($_POST['posizioneservizio'])) {
    $posizioneservizio = $_POST['posizioneservizio'];

    if (empty($posizioneservizio)) {
        $errors[] = "Il campo 'Posizione Servizio' è obbligatorio.";
    }
}

if (isset($_POST['nomereferente'])) {
    $nomereferente = $_POST['nomereferente'];

    if (empty($nomereferente)) {
        $errors[] = "Il campo 'Nome Referente' è obbligatorio.";
    }
}

if (isset($_POST['areadintervento'])) {
    $areadintervento = $_POST['areadintervento'];

    if (empty($areadintervento)) {
        $errors[] = "Il campo 'Area di Intervento' è obbligatorio.";
    }
}


if (isset($_POST['dataeoradelservizio'])) {
    $dataeoradelservizio = $_POST['dataeoradelservizio'];

    // Validazione della data
    if (empty($dataeoradelservizio)) {
        $errors[] = "Il campo 'Data e Ora' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $dataeoradelservizio = strtotime($dataeoradelservizio);
        if (!$dataeoradelservizio) {
            $errors[] = "Il campo 'Data e Ora' non è nel formato corretto.";
        }
    }

    if (isset($_POST['organizzatore'])) {
    $organizzatore = $_POST['organizzatore'];

    if (empty($organizzatore)) {
        $errors[] = "Il campo 'Organizzatore' è obbligatorio.";
    }
}

if (isset($_POST['apertoa'])) {
    $apertoa = $_POST['apertoa'];

    if (empty($apertoa)) {
        $errors[] = "Il campo 'Aperto a' è obbligatorio.";
    }
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (empty($email)) {
        $errors[] = "Il campo 'Email' è obbligatorio.";
    }
}

if (isset($_POST['informazioniattivita'])) {
    $informazioniattivita = $_POST['informazioniattivita'];

    if (empty($informazioniattivita)) {
        $errors[] = "Il campo 'Informazioni Attivita' è obbligatorio.";
    }
}

if (isset($_POST['minpartecipanti'])) {
    $minpartecipanti = $_POST['minpartecipanti'];

    if (empty($minpartecipanti)) {
        $errors[] = "Il campo 'Min Partecipanti' è obbligatorio.";
    }
}

if (isset($_POST['maxpartecipanti'])) {
    $maxpartecipanti = $_POST['maxpartecipanti'];

    if (empty($maxpartecipanti)) {
        $errors[] = "Il campo 'Max Partecipanti' è obbligatorio.";
    }
}
}
    // Restituisce l'array di errori
return $errors;
?>
