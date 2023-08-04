<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['nomenews'])) {
    $nomenews = $_POST['nomenews'];

    if (empty($nomenews)) {
        $errors[] = "Il campo 'Nome News' è obbligatorio.";
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

if (isset($_POST['publish_date'])) {
    $publish_date = $_POST['publish_date'];

    // Validazione della data
    if (empty($publish_date)) {
        $errors[] = "Il campo 'Data e Ora Inserimento' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $publish_date = strtotime($publish_date);
        if (!$publish_date) {
            $errors[] = "Il campo 'Data e Ora Inserimento' non è nel formato corretto.";
        }
    }

    if (isset($_POST['descrizione'])) {
        $descrizione = $_POST['descrizione'];
    
        if (empty($descrizione)) {
            $errors[] = "Il campo 'Descrizione' è obbligatorio.";
        }
    }
}
    // Restituisce l'array di errori
return $errors;
?>
