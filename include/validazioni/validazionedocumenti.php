<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['nomedocumento'])) {
    $nomedocumento = filter_var($_POST['nomedocumento'], FILTER_SANITIZE_STRING);

    if (empty($nomedocumento)) {
        $errors[] = "Il campo 'Nome Documento' è obbligatorio.";
    }
}

if (isset($_POST['linkdocumento'])) {
    $linkdocumento = filter_var($_POST['linkdocumento'], FILTER_SANITIZE_URL);

    if (empty($linkdocumento)) {
        $errors[] = "Il campo 'Link Documento' è obbligatorio.";
    } elseif (!filter_var($linkdocumento, FILTER_VALIDATE_URL)) {
        $errors[] = "Il campo 'Link Documento' non contiene un URL valido.";
    }
}

// Restituisce l'array di errori
return $errors;
?>
