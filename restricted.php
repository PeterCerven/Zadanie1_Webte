<?php
/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

include 'config.php';
include 'functions/showData.php';

session_start();


if (!isset($_SESSION["Loggedin"])) {
    header("Location:index.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OH restricted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

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
            <a class="navbar-brand" href="#"><h3>Domov</h3></a>
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
    <h1 class="text-center">Zadanie 1 Olympíske hry</h1>
    <div class="container-md">
        <h2>Tabuľka výhercov</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Pridaj olympionika
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlacementModal">
            Pridaj umiestnenie
        </button>
        <button type="button" class="btn btn-primary">
            <a class="text-decoration-none text-white" href="tables/history.php">História účtu</a>
        </button>
        <button type="button" class="btn btn-primary">
            <a class="text-decoration-none text-white" href="accs/logout.php">Odhlásenie</a>
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
                <th>Možnosti</th>
            </tr>
            </thead>
            <tbody>
            <?php
            showWinners($db);
            ?>
            </tbody>
        </table>
    </div>
    <div class="container-md">
        <h2>Top 10 olympionikov</h2>
        <table id="top10_data" class="table display">
            <thead>
            <tr>
                <th>Meno</th>
                <th>Priezvisko</th>
                <th>Zlaté Medaily</th>
                <th>Možnosti</th>
            </tr>
            </thead>
            <tbody>
            <?php
            showTop10($db);
            ?>
            </tbody>
        </table>
    </div>
    <!--    Add Athelete modal-->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Pridaj atleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="personForm row g-3 needs-validation" novalidate action="functions/action.php"
                      method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="InputName" class="form-label">Meno:</label>
                            <input type="text" name="name" class="form-control" id="InputName" required>
                            <div class="invalid-feedback">
                                Prosím zadajte meno dlhé 3 až 30 znakov.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="InputSurname" class="form-label">Priezvisko:</label>
                            <input type="text" name="surname" class="form-control" id="InputSurname" required>
                            <div class="invalid-feedback">
                                Prosím zadajte priezvisko dlhé 3 až 30 znakov.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="InputBrDay" class="form-label">Dátum narodenia:</label>
                            <input type="date" name="birth_day" class="form-control" id="InputBrDay" required>
                            <div class="invalid-feedback">
                                Prosím zadajte platný dátum narodenia (viac ako 10 rokov a menej ako 100 rokov).
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="InputBrPlace" class="form-label">Miesto Narodenia:</label>
                            <input type="text" name="birth_place" class="form-control" id="InputBrPlace" required>
                            <div class="invalid-feedback">
                                Prosím zadajte názov mesta dlhý 3 až 30 znakov.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="InputBrCountry" class="form-label">Krajina narodenia:</label>
                            <input type="text" name="birth_country" class="form-control" id="InputBrCountry" required>
                            <div class="invalid-feedback">
                                Prosím zadajte názov krajiny dlhý 3 až 30 znakov.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
                        <button type="submit" name="addAthlete" class="btn btn-primary">Uložíť olympionika</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--    add placement modal-->
    <div class="modal fade" id="addPlacementModal" tabindex="-1" aria-labelledby="addPlacementModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlacementModalLabel">Pridaj umiestnenie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="placementForm row g-3 needs-validation" novalidate action="functions/action.php"
                      method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="InputPersonId" class="form-label">Vyber olympionika:</label>
                            <select name="person_id" class="form-control" id="InputPersonId" required>
                                <?php showAthletes($db) ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="InputGameId" class="form-label">Vyber olympiske hry:</label>
                            <select name="game_id" class="form-control" id="InputGameId" required>
                                <?php showGames($db) ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="InputPlacing" class="form-label">Umiestnenie:</label>
                            <input type="text" name="placing" class="form-control" id="InputPlacing" required>
                            <div class="invalid-feedback">
                                Prosím zadajte platné umiestnenie.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="InputDiscipline" class="form-label">Disciplína:</label>
                            <input type="text" name="discipline" class="form-control" id="InputDiscipline" required>
                            <div class="invalid-feedback">
                                Prosím zadajte názov disciplíny v rozmedzí 3 až 50 znakov.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
                        <button type="submit" name="addPlacement" class="btn btn-primary">Uložíť umiestnenie</button>
                    </div>
                </form>
            </div>
        </div>
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
<script src="validationFunction.js"></script>
<script src="functions/placementValidation.js"></script>
<script src="personValidation.js"></script>

</body>

</html>
