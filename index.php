<?php
/**
 * @var $db PDO
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'functions/showData.php';

session_start();

if (isset($_SESSION['Loggedin']) && $_SESSION['Loggedin'] === true) {
    header('Location:restricted.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="restricted.php"><h3>Domov</h3></a>
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
<h1 class="text-center">Zadanie 1</h1>
<div class="container">
    <div class="container-md">
        <h2>Olympionici</h2>
        <button type="button" class="btn btn-primary">
            <a href="accs/register.php" class="text-decoration-none text-white">Registrácia</a>
        </button>
        <button type="button" class="btn btn-primary">
            <a href="accs/login.php" class="text-decoration-none text-white">Prihlásenie</a>
        </button>
        <button type="button" class="btn btn-primary google">
            <a href="accs/authentication.php" class="text-decoration-none text-white">Google Prihlásenie</a>
        </button>
        <table id="winners_data" class="table display table-striped table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Meno</th>
                <th>Priezvisko</th>
                <th>Rok</th>
                <th>Mesto</th>
                <th>Typ</th>
                <th>Krajina</th>
                <th>Disciplína</th>
            </tr>
            </thead>
            <tbody>
            <?php
            showWinnersNoLogin($db);
            ?>
            </tbody>
        </table>
    </div>
    <div class="container-md">
        <h2>TOP 10</h2>
        <table id="top10_data" class="table display">
            <thead>
            <tr>
                <th>Meno</th>
                <th>Priezvisko</th>
                <th>Zlaté Medaily</th>
            </tr>
            </thead>
            <tbody>
            <?php
            showTop10NoLogin($db);
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#winners_data').DataTable({
            columnDefs: [{
                targets: [5],
                orderData: [5, 3],
            },],
            responsive: true
        });
        $('#top10_data').DataTable({
            responsive: true,
            paging: false,
            info: false
        });


    });
</script>
</body>

</html>
