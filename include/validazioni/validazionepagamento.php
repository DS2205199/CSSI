<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['numero'])) {
    $numero = $_POST['numero'];

    if (empty($numero)) {
        $errors[] = "Il campo 'Numero' è obbligatorio.";
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

if (isset($_POST['anno'])) {
    $anno = $_POST['anno'];

    if (empty($anno)) {
        $errors[] = "Il campo 'Anno' è obbligatorio.";
    }
}

if (isset($_POST['importo'])) {
    $importo = $_POST['importo'];

    if (empty($importo)) {
        $errors[] = "Il campo 'Importo' è obbligatorio.";
    }
}

    if (isset($_POST['descrizione'])) {
        $descrizione = $_POST['descrizione'];
    
        if (empty($descrizione)) {
            $errors[] = "Il campo 'Descrizione' è obbligatorio.";
        }
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    
        if (empty($email)) {
            $errors[] = "Il campo 'Email' è obbligatorio.";
        }
    }
    // Restituisce l'array di errori
return $errors;
?>