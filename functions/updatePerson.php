<?php

/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

require_once '../config.php';
require_once 'helpFunctions.php';

session_start();

if (!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
    header('Location: ../accs/login.php');
    exit();
}

if (isset($_GET['updateid'])) {
    $personQuery = <<<SQL
            SELECT * FROM person
            WHERE person.id = ?
            SQL;

    $stmt = $db->prepare($personQuery);
    $stmt->execute([$_GET['updateid']]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    echo '<script type="text/javascript">';
    echo 'alert("Nesprávne ID.");';
    echo 'window.location.href = "../restricted.php";';
    echo '</script>';
}

if (isset($_POST['update'])) {
    $query = <<<SQL
            UPDATE person
            SET name=:name, surname=:surname, birth_day=:birth_day, birth_place=:birth_place,
                birth_country=:birth_country, death_day=:death_day, death_place=:death_place, death_country=:death_country
            WHERE id =:id
            SQL;


    $stmt = $db->prepare($query);


    if (isset($_POST['death_day']) && trim($_POST['death_day']) === "") {
        unset($_POST['death_day']);
    }
    if (isset($_POST['death_place']) && trim($_POST['death_place']) === "") {
        unset($_POST['death_place']);
    }
    if (isset($_POST['death_country']) && trim($_POST['death_country']) === "") {
        unset($_POST['death_country']);
    }


    $stmt->bindParam(":id", $_GET['updateid'], PDO::PARAM_STR);
    $stmt->bindParam(":name", $_POST['name'], PDO::PARAM_STR);
    $stmt->bindParam(":surname", $_POST['surname'], PDO::PARAM_STR);
    $stmt->bindParam(":birth_day", $_POST['birth_day'], PDO::PARAM_STR);
    $stmt->bindParam(":birth_place", $_POST['birth_place'], PDO::PARAM_STR);
    $stmt->bindParam(":birth_country", $_POST['birth_country'], PDO::PARAM_STR);
    $stmt->bindParam(":death_day", $_POST['death_day'], PDO::PARAM_STR);
    $stmt->bindParam(":death_place", $_POST['death_place'], PDO::PARAM_STR);
    $stmt->bindParam(":death_country", $_POST['death_country'], PDO::PARAM_STR);

    $success = $stmt->execute();
    if ($success) {
        addActionToHistory($db, 'update', 'person');
        echo '<script type="text/javascript">';
        echo 'alert("Atlét bol upravený.");';
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
    <title>Update person</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
    <h1>Uprav atléta</h1>
    <form class="row g-3 needs-validation" novalidate action="#" method="post">
        <div class="mb-2">
            <label for="InputName" class="form-label">Meno:</label>
            <input type="text" name="name" class="form-control" id="InputName"
                   value='<?php echo "{$person["name"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte meno dlhé 3 až 30 znakov.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputSurname" class="form-label">Priezvisko:</label>
            <input type="text" name="surname" class="form-control" id="InputSurname"
                   value='<?php echo "{$person["surname"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte priezvisko dlhé 3 až 30 znakov.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputBrDay" class="form-label">Dátum narodenia:</label>
            <input type="date" name="birth_day" class="form-control" id="InputBrDay"
                   value='<?php echo "{$person["birth_day"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte platný dátum narodenia.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputBrPlace" class="form-label">Miesto narodenia:</label>
            <input type="text" name="birth_place" class="form-control" id="InputBrPlace"
                   value='<?php echo "{$person["birth_place"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte názov mesta dlhý 3 až 30 znakov.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputBrCountry" class="form-label">Krajina narodenia:</label>
            <input type="text" name="birth_country" class="form-control" id="InputBrCountry"
                   value='<?php echo "{$person["birth_country"]}" ?>' required>
            <div class="invalid-feedback">
                Prosím zadajte názov krajiny dlhý 3 až 30 znakov.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputDeathDay" class="form-label">Deň úmrtia:</label>
            <input type="date" name="death_day" class="form-control" id="InputDeathDay"
                   value='<?php echo "{$person["death_day"]}" ?>'>
            <div class="invalid-feedback">
                Prosím zadajte platný dátum úmrtia.
            </div>
        </div>
        <div class="mb-2">
            <label for="InputDeathPlace" class="form-label">Miesto úmrtia:</label>
            <input type="text" name="death_place" class="form-control" id="InputDeathPlace"
                   value='<?php echo "{$person["death_place"]}" ?>'>
            <div class="invalid-feedback">
                Prosím zadajte názov mesta dlhý 3 až 30 znakov
            </div>
        </div>
        <div class="mb-2">
            <label for="InputDeathCountry" class="form-label">Krajina úmrtia:</label>
            <input type="text" name="death_country" class="form-control" id="InputDeathCountry"
                   value='<?php echo "{$person["death_country"]}" ?>'>
            <div class="invalid-feedback">
                Prosím zadajte názov krajiny dlhý 3 až 30 znakov.
            </div>
        </div>
        <div class="col-6">
            <button type="submit" name="update" class='btn btn-primary'>Uprav</button>
        </div>
    </form>
</div>

<script src="../validationFunction.js"></script>
<script src="updatePersonValidation.js"></script>

</body>

</html>
