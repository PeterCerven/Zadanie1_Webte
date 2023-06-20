<?php
/**
 * @var $db PDO
 */

require_once '../config.php';

session_start();

if (!isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] !== true) {
    header('Location:login.php');
    exit();
}

$query = <<<SQL
            UPDATE logins
            SET end_time = NOW()
            WHERE id = ?
            SQL;

$stmt = $db->prepare($query);
$success = $stmt->execute([$_SESSION['login_id']]);


// Uvolni vsetky session premenne.
session_unset();

// Vymaz vsetky data zo session.
session_destroy();

// Ak nechcem zobrazovat obsah, presmeruj pouzivatela na hlavnu stranku.
// header('location:index.php');
echo '<script type="text/javascript">';
echo 'alert("Boli ste odhlásený.");';
echo 'window.location.href = "../restricted.php";';
echo '</script>';



?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logout</title>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>
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
<main>
    <a role="button" href="../index.php" class="secondary">Vráť sa na hlavnú stránku.</a>
</main>
</body>
</html>
