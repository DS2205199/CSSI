<?php
// Contenuto di validazione.php

$errors = []; // Array per memorizzare gli errori

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($email)) {
        $errors[] = "Il campo 'Email' è obbligatorio.";
    }
}

if (isset($_POST['dataeora'])) {
    $dataeora = mysqli_real_escape_string($conn, $_POST['dataeora']);

    // Validazione della data
    if (empty($dataeora)) {
        $errors[] = "Il campo 'Data e Ora' è obbligatorio.";
    } else {
        // Verifica che la data sia nel formato corretto
        $dataeora = strtotime($dataeora);
        if (!$dataeora) {
            $errors[] = "Il campo 'Data e Ora' non è nel formato corretto.";
        }
    }
}

if (isset($_POST['info'])) {
    $info = mysqli_real_escape_string($conn, $_POST['info']);

    if (empty($info)) {
        $errors[] = "Il campo 'Informazioni' è obbligatorio.";
    }
}


// Restituisce l'array di errori
return $errors;
?>

