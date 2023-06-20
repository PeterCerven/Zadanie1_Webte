<?php
require_once __DIR__ . '/../vendor/autoload.php';
/**
 * @var $db PDO
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

require_once '../config.php';
require_once '../functions/helpFunctions.php';

session_start();

if (isset($_SESSION["Loggedin"]) && $_SESSION["Loggedin"] === true) {
    header("location:../restricted.php");
    exit;
}

if (isset($_POST["register"])) {
    $ErrMSG = "";


    $ErrMSG = validateEmail($_POST['email'], $db);
    $ErrMSG .= validateLogin($_POST['login'], $db);

    unset($_POST["register"]);


    if (empty($ErrMSG)) {
        $sql = "INSERT INTO accounts (fullname, login, email, password, 2fa_code) VALUES (:fullname, :login, :email, :password, :2fa_code)";

        $fullname = $_POST['name'] . ' ' . $_POST['surname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $codeURL = $g2fa->getQRCodeGoogleUrl('Olympic Games', $user_secret);

// Bind parametrov do SQL
        $stmt = $db->prepare($sql);

        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":2fa_code", $user_secret, PDO::PARAM_STR);


        unset($_POST);
        if ($stmt->execute()) {
            $qrcode = $codeURL;
            echo '<script type="text/javascript">';
            echo 'alert("Boli ste úspešne zaregistrovaný.");';
            echo '</script>';
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Ups. Nieco sa pokazilo v databaze");';
            echo '</script>';
        }

        unset($stmt);
    }
}


?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register s 2FA - Register</title>
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
        <h1>Registrácia</h1>
        <form class="row g-3 needs-validation" novalidate id="registerForm"
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
              method="post">
            <div class="mb-4">
                <label for="InputName" class="form-label">Meno:</label>
                <input type="text" name="name" class="form-control" id="InputName"
                       required>
                <div class="invalid-feedback">
                    Prosím zadajte meno dlhé 3 až 30 znakov.
                </div>
            </div>
            <div class="mb-4">
                <label for="InputSurname" class="form-label">Priezvisko:</label>
                <input type="text" name="surname" class="form-control" id="InputSurname"
                       placeholder='surname'
                       required>
                <div class="invalid-feedback">
                    Prosím zadajte priezvisko dlhé 3 až 30 znakov.
                </div>
            </div>
            <div class="mb-4">
                <label for="InputEmail" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" id="InputEmail"
                       placeholder='example@gmail.com' required>
                <div class="invalid-feedback">
                    Prosím zadajte platnú emailovú adresu.
                </div>
            </div>
            <div class="mb-4">
                <label for="InputLogin" class="form-label">Login:</label>
                <input type="text" name="login" class="form-control" id="InputLogin" placeholder='login'
                       required>
                <div class="invalid-feedback">
                    Prosím zadajte platný login dlhý 5 až 20 znakov.
                </div>
            </div>
            <div class="mb-4">
                <label for="InputPassword" class="form-label">Heslo:</label>
                <input type="password" name="password" class="form-control" id="InputPassword" required>
                <div class="invalid-feedback">
                    Prosím zadajte platné heslo dlhé 8 až 20 znakov. Heslo musí obsahovať aspoň jedno číslo, jedno veľké písmeno a jedno malé písmeno a jeden špeciálny znak.
                </div>
            </div>
            <div class="col-6">
                <button type="submit" name="register" class="btn btn-primary">Registruj sa</button>
            </div>
        </form>
    </div>


    <?php
    if (!empty($ErrMSG)) {
        // Tu vypis chybne vyplnene polia formulara.
        echo "<script type='text/javascript'>";
        echo "alert('{$ErrMSG}');";
        echo "</script>";
    }
    if (isset($qrcode)) {
        // Pokial bol vygenerovany QR kod po uspesnej registracii, zobraz ho.
        $_SESSION['qrcode'] = $qrcode;
        header("Location:qrcode.php");
    }
    ?>
<script src="../validationFunction.js"></script>
<script src="registerValidation.js"></script>
</body>
</html>

