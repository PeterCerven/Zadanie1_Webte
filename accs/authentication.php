<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);



require_once '../vendor/autoload.php';
require_once '../config.php';

session_start();

if (isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] === true) {
    header('Location:../restricted.php');
    exit();
}

// Inicializacia Google API klienta
$client = new Google\Client();

// Definica konfiguracneho JSON suboru pre autentifikaciu klienta.
// Subor sa stiahne z Google Cloud Console v zalozke Credentials.
try {
    $client->setAuthConfig('../client_secret.json');
} catch (\Google\Exception $e) {
}

// Nastavenie URI, na ktoru Google server presmeruje poziadavku po uspesnej autentifikacii.
$redirect_uri = "https://site71.webte.fei.stuba.sk/Zadanie1_OH/accs/redirect.php";
$client->setRedirectUri($redirect_uri);

// Definovanie Scopes - rozsah dat, ktore pozadujeme od pouzivatela z jeho Google uctu.
$client->addScope("email");
$client->addScope("profile");

// Vytvorenie URL pre autentifikaciu na Google server - odkaz na Google prihlasenie.
$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

