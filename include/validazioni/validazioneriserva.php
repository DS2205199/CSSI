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

if (isset($_POST['inizio'])) {
    $inizio = $_POST['inizio'];

    // Validazione della data
    if (empty($inizio)) {
        $errors[] = "Il campo 'Data e Ora Inserimento' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $inizio = strtotime($inizio);
        if (!$inizio) {
            $errors[] = "Il campo 'Data e Ora Inserimento' non è nel formato corretto.";
        }
    }

    if (isset($_POST['fine'])) {
        $fine = $_POST['fine'];
    
        // Validazione della data
        if (empty($fine)) {
            $errors[] = "Il campo 'Data e Ora Inserimento' è obbligatorio.";
        } else {
            // Verifica che la data sia nel formato corretto
            $fine = strtotime($fine);
            if (!$fine) {
                $errors[] = "Il campo 'Data e Ora Inserimento' non è nel formato corretto.";
            }
        }
    }
}

    // Restituisce l'array di errori
return $errors;
?>
