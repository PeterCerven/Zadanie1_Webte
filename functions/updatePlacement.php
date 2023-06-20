<?php

/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

require_once '../config.php';
require_once 'helpFunctions.php';
require_once 'showData.php';

session_start();

if (!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
    header('Location: ../accs/login.php');
    exit();
}


if (isset($_GET['updatePlacementID'])) {
    $placementQuery = <<<SQL
            SELECT * FROM placement
            WHERE placement.id = ?
            SQL;

    $stmt = $db->prepare($placementQuery);
    $stmt->execute([$_GET['updatePlacementID']]);
    $placement = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    exit('No ID specified');
}

if (isset($_POST['update'])) {
    $query = <<<SQL
            UPDATE placement
            SET person_id=:person_id, game_id=:game_id, placing=:placing, discipline=:discipline
            WHERE id =:id
            SQL;


    $stmt = $db->prepare($query);

    $stmt->bindParam(":id", $_GET['updatePlacementID'], PDO::PARAM_STR);
    $stmt->bindParam(":person_id", $_POST['person_id'], PDO::PARAM_STR);
    $stmt->bindParam(":game_id", $_POST['game_id'], PDO::PARAM_STR);
    $stmt->bindParam(":placing", $_POST['placing'], PDO::PARAM_STR);
    $stmt->bindParam(":discipline", $_POST['discipline'], PDO::PARAM_STR);

    $success = $stmt->execute();
    if ($success) {
        addActionToHistory($db, 'update', 'placement');
        echo '<script type="text/javascript">';
        echo 'alert("Umiestnenie bolo upravené.");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    } else {
        die($db->errorInfo());
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update placement</title>
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
    <h1>Uprav umiestnenie</h1>
    <form action="#" method="post" class="placementForm row g-3 needs-validation" novalidate>
        <div class="mb-3">
            <label for="InputPersonId" class="form-label">Vyber olympionika:</label>
            <select name="person_id" class="form-control" id="InputPersonId" required>
                <?php showAthletesID($db, $placement["person_id"]) ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="InputGameId" class="form-label">Vyber olympíske hry:</label>
            <select name="game_id" class="form-control" id="InputGameId" required>
                <?php showGamesID($db, $placement["game_id"]) ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="InputPlacing" class="form-label">Umiestnenie:</label>
            <input type="text" name="placing" class="form-control" id="InputPlacing"
                   value='<?php echo "{$placement["placing"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte platné umiestnenie.
            </div>
        </div>
        <div class="mb-3">
            <label for="InputDiscipline" class="form-label">Disciplína:</label>
            <input type="text" name="discipline" class="form-control" id="InputDiscipline"
                   value='<?php echo "{$placement["discipline"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte názov disciplíny v rozmedzí 3 až 50 znakov.
            </div>
        </div>
        <div class="col-6">
            <button type="submit" name="update" class='btn btn-primary'>Uprav</button>
        </div>
    </form>
</div>
<script src="../validationFunction.js"></script>
<script src="placementValidation.js"></script>
</body>

</html>
