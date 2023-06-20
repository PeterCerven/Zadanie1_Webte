<?php
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

require_once '../config.php';

session_start();

if (isset($_SESSION["Loggedin"]) && $_SESSION["Loggedin"] === true) {
    header("location:../restricted.php");
    exit;
}

function insertLogin(PDO $db): int
{
    $sql = <<<SQL
    INSERT INTO logins (type, account_id) 
    VALUES (?,?)
    SQL;
    $stmt = $db->prepare($sql);
    $stmt->execute(["2FA", $_SESSION["account_id"]]);
    return $db->lastInsertId();
}

if (isset($_POST["login2fa"])) {
    $sql = "SELECT id, fullname, email, login, password, created_at, 2fa_code FROM accounts WHERE login = :login OR email = :login";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);
    $stmt->bindParam(":email", $_POST["email"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                // Heslo je spravne.
                $g2fa = new PHPGangsta_GoogleAuthenticator();
                if ($g2fa->verifyCode($row["2fa_code"], $_POST['2fa'], 2)) {
                    // Heslo aj kod su spravne, pouzivatel autentifikovany.

                    // Uloz data pouzivatela do session.
                    $_SESSION["Loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["created_at"] = $row['created_at'];
                    var_dump($row);
                    $_SESSION["account_id"] = $row['id'];

                    $_SESSION["login_id"] = insertLogin($db);

                    // Presmeruj pouzivatela na zabezpecenu stranku.
                    header("location:../restricted.php");
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Neplatny kod 2FA.");';
                    echo 'window.location.href = "../restricted.php";';
                    echo '</script>';
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'alert("Nespravne meno alebo heslo.");';
                echo 'window.location.href = "../restricted.php";';
                echo '</script>';
            }
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Nespravne meno alebo heslo.");';
            echo 'window.location.href = "../restricted.php";';
            echo '</script>';
        }
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Ups. Nieco sa pokazilo s databazou!");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    }

    unset($stmt);
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
    <h2>Prihlásenie</h2>
    <form class="row g-3 needs-validation" novalidate action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
          method="post">
        <div class="mb-3">
            <label for="InputLogin" class="form-label">Login:</label>
            <input type="text" name="login" class="form-control" id="InputLogin" placeholder='login'
                   required>
        </div>
        <div class="mb-3">
            <label for="InputPassword" class="form-label">Heslo:</label>
            <input type="password" name="password" class="form-control" id="InputPassword" required>
        </div>
        <div class="mb-3">
            <label for="Input2FA" class="form-label">2FA kód:</label>
            <input type="text" name="2fa" value="" id="Input2FA" required>
            <div class="invalid-feedback">
                Kód 2FA je povinných 6 číslic.
            </div>
        </div>
        <div class="mb-3">
            <button type="submit" name="login2fa" class="btn btn-primary">Prihlás sa</button>
        </div>
    </form>
</div>

<script src="../validationFunction.js"></script>
<script src="2faValidation.js"></script>
</body>
</html>

