<?php
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);


session_start();

if (isset($_SESSION["qrcode"]) && !isset($_SESSION["Loggedin"])) {
    $qrcode = $_SESSION["qrcode"];
} else {
    header("location:../index.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles.css">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../restricted.php"><h3>Domov</h3></a>
            <div class="navbar-nav ms-auto">
            <span class="navbar-text"> <?php
                if (isset($_SESSION['fullname']) && isset($_SESSION['Loggedin'])) {
                    echo '<h3>Vitaj ' . $_SESSION['fullname'] . ' </h3>';
                } else {
                    echo '<h3>Nie ste prihlásený</h3>';
                }
                ?></span>
            </div>
        </div>
    </nav>
</header>
<body>
<div class="container-md">
    <h2>QR Kód</h2>
    <?php
    echo '<div class="d-flex justify-content-center">';
    echo '<h4>Naskenujte QR kod do aplikacie Authenticator pre 2FA: <br><img src="' . $qrcode . '" alt="qr kod pre aplikaciu authenticator"></h4>';
    echo '</div>';
    ?>
    <button type="button" class="btn btn-primary">
        <a href="login.php" class="text-decoration-none text-white">Prihlásenie</a>
    </button>
</div>


</body>
</html>

