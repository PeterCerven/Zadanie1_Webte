<?php
/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);
require_once '../config.php';

session_start();

if (!isset($_SESSION['Loggedin']) || $_SESSION['Loggedin'] !== true) {
    header('Location: ../accs/login.php');
    exit();
}


$historyQuery = <<<SQL
      SELECT *
      FROM logins
      WHERE account_id = :id OR google_id = :google_id;
    SQL;

$stmtH = $db->prepare($historyQuery);
$stmtH->bindParam(':id', $_SESSION['account_id']);
$stmtH->bindParam(':google_id', $_SESSION['google_id']);
$stmtH->execute();
$resultsH = $stmtH->fetchAll(PDO::FETCH_ASSOC);


$actionsQuery = <<<SQL
      SELECT *
      FROM actions
      WHERE account_id = :id OR google_id = :google_id;
    SQL;

$stmtA = $db->prepare($actionsQuery);
$stmtA->bindParam(':id', $_SESSION['account_id']);
$stmtA->bindParam(':google_id', $_SESSION['google_id']);
$stmtA->execute();
$resultsA = $stmtA->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>
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
    <h2>Prihlásenia</h2>
    <table class="table display">
        <thead>
        <tr>
            <th>Id</th>
            <th>Typ</th>
            <th>Začiatok prihlásenia</th>
            <th>Koniec prihásenia</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($resultsH as $result) {
            echo "<tr>
                     <td>{$result["id"]}</td>
                     <td>{$result["type"]}</td>
                     <td>{$result["start_time"]}</td>
                     <td>{$result["end_time"]}</td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
    <h2>Aktivity</h2>
    <table class="table display">
        <thead>
        <tr>
            <th>Id</th>
            <th>Akcia</th>
            <th>Názov tabuľky</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($resultsA as $result) {
            echo "<tr>
                     <td>{$result["id"]}</td>
                     <td>{$result["action"]}</td>
                     <td>{$result["table_name"]}</td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('table.display').DataTable({
            responsive: true,
            paging: false,
            info: false
        });
    });
</script>
</body>

</html>