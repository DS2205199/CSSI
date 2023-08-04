<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

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

if (isset($_POST['destinazione'])) {
    $destinazione = $_POST['destinazione'];

    if (empty($destinazione)) {
        $errors[] = "Il campo 'Destinazione' è obbligatorio.";
    }
}

if (isset($_POST['datarichiesta'])) {
    $datarichiesta = $_POST['datarichiesta'];

    // Validazione della data
    if (empty($datarichiesta)) {
        $errors[] = "Il campo 'Data e Ora Inserimento' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $datarichiesta = strtotime($datarichiesta);
        if (!$datarichiesta) {
            $errors[] = "Il campo 'Data e Ora Inserimento' non è nel formato corretto.";
        }
    }

    if (isset($_POST['sede'])) {
        $sede = $_POST['sede'];
    
        if (empty($sede)) {
            $errors[] = "Il campo 'Sede' è obbligatorio.";
        }
    }
}
    // Restituisce l'array di errori
return $errors;
?>
