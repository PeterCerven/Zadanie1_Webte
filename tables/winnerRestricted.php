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

try {
    if (isset($_GET['id'])) {

        $id = $_GET['id'];
    } else {
        header('Location:../accs/login.php');
    }


    $query = <<<SQL
      SELECT pe.id, pe.name, pe.surname, g.year, g.city, g.country, g.type, pl.id AS plID, pl.discipline
      FROM person AS pe 
      JOIN placement AS pl
      ON pe.id = pl.person_id
      JOIN game AS g
      ON pl.game_id = g.id
      WHERE pe.id = :id;
    SQL;

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winner restricted</title>
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
    <h2>Atlét</h2>
    <?php echo "<button type='submit' class='btn btn-primary'><a class='text-decoration-none text-white' href='../functions/updatePerson.php?updateid={$_GET["id"]}'>Uprav Atléta</a></button>" ?>
    <?php echo "<button type='submit' class='btn btn-danger'><a class='text-decoration-none text-white' href='../functions/action.php?deleteid={$_GET["id"]}'>Vymaž Atléta</a></button>" ?>
    <h3>Umiestnenia</h3>
    <table class="table display">
        <thead>
        <tr>
            <th>Meno</th>
            <th>Priezvisko</th>
            <th>Rok</th>
            <th>Mesto</th>
            <th>Krajina</th>
            <th>Typ</th>
            <th>Disciplína</th>
            <th>Možnosti</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($results as $result) {
            echo "<tr>
                     <td>{$result["name"]}</td>
                     <td>{$result["surname"]}</td>
                     <td>{$result["year"]}</td>
                     <td>{$result["city"]}</td>
                     <td>{$result["country"]}</td>
                     <td>{$result["type"]}</td>
                     <td>{$result["discipline"]}</td>
                     <td>
                        <button type='submit' class='btn btn-primary'><a class='text-decoration-none text-white' href='../functions/updatePlacement.php?updatePlacementID={$result["plID"]}'>Uprav</a></button>
                        <button type='submit' class='btn btn-danger'><a class='text-decoration-none text-white' href='../functions/action.php?deletePlacementID={$result["plID"]}'> Vymaž</a></button>
                     </td>
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