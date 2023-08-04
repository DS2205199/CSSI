<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['videoTitle'])) {
    $videoTitle = $_POST['videoTitle'];

    if (empty($videoTitle)) {
        $errors[] = "Il campo 'Il Titolo del Video' è obbligatorio.";
    }
}

if (isset($_POST['videoType'])) {
    $videoType = $_POST['videoType'];

    if (empty($videoType)) {
        $errors[] = "Il campo 'Il Tipo del Video' è obbligatorio.";
    }
}

if (isset($_POST['videoUrl'])) {
    $videoUrl = filter_var($_POST['videoUrl'], FILTER_SANITIZE_URL);

    if (empty($videoUrl)) {
        $errors[] = "Il campo 'URL Video' è obbligatorio.";
    } elseif (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
        $errors[] = "Il campo 'URL VIDEO' non contiene un URL valido.";
    }
}

if (isset($_POST['last_name'])) {
    $last_name = $_POST['last_name'];

    if (empty($last_name)) {
        $errors[] = "Il campo 'Cognome' è obbligatorio.";
    }
}

if (isset($_POST['first_name'])) {
    $first_name = $_POST['first_name'];

    if (empty($first_name)) {
        $errors[] = "Il campo 'Nome' è obbligatorio.";
    }
}

if (isset($_POST['publish_date'])) {
$publish_date = isset($_POST['publish_date']) ? $_POST['publish_date'] : "";

    // Validazione della data
    if (empty($publish_date)) {
        $errors[] = "Il campo 'Data e Ora di inserimento' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $publish_date = strtotime($publish_date);
        if (!$publish_date) {
            $errors[] = "Il campo 'Data e Ora di inserimento' non è nel formato corretto.";
        }
    }
}
    // Restituisce l'array di errori
return $errors;
?>
