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


if (isset($_POST['addAthlete'])) {
    if (existsPerson($db, $_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country'])) {
        echo '<script type="text/javascript">';
        echo 'alert("Zadaný športovec už existuje");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
        exit();
    }


    $sql = <<<SQL
    INSERT INTO person (name, surname, birth_day, birth_place, birth_country) 
    VALUES (?,?,?,?,?)
    SQL;
    $stmt = $db->prepare($sql);
    $success = $stmt->execute([$_POST['name'], $_POST['surname'], $_POST['birth_day'], $_POST['birth_place'], $_POST['birth_country']]);
    if ($success) {
        addActionToHistory($db, 'add', 'person');
        echo '<script type="text/javascript">';
        echo 'alert("Atlét bol pridaný.");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    } else {
        die($db->errorInfo());
    }
}

if (isset($_POST['addPlacement'])) {
    if (existsPlacement($db, $_POST['person_id'], $_POST['game_id'], $_POST['placing'], $_POST['discipline'])) {
        echo '<script type="text/javascript">';
        echo 'alert("Zadané umiestnenie už existuje");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
        exit();
    }

    $sql = <<<SQL
    INSERT INTO placement (person_id, game_id, placing, discipline) 
    VALUES (?,?,?,?)
    SQL;

    $stmt = $db->prepare($sql);
    $success = $stmt->execute([$_POST['person_id'], $_POST['game_id'], $_POST['placing'], $_POST['discipline']]);
    if ($success) {
        addActionToHistory($db, 'add', 'placement');
        echo '<script type="text/javascript">';
        echo 'alert("Umiestnenie bolo pridané.");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    } else {
        die($db->errorInfo());
    }
}

if (isset($_GET['deleteid'])) {
    $query = <<<SQL
            DELETE FROM person
            WHERE person.id = :id
            SQL;

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['deleteid']);

    $success = $stmt->execute();
    if ($success) {
        addActionToHistory($db, 'delete', 'person');
        echo '<script type="text/javascript">';
        echo 'alert("Atlét bol zmazaný.");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    } else {
        die($db->errorInfo());
    }
}

if (isset($_GET['deletePlacementID'])) {
    $query = <<<SQL
            DELETE FROM placement
            WHERE placement.id = :id
            SQL;

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['deletePlacementID']);

    $success = $stmt->execute();
    if ($success) {
        addActionToHistory($db, 'delete', 'placement');
        echo '<script type="text/javascript">';
        echo 'alert("Umiestnenie bolo zmazané");';
        echo 'window.location.href = "../restricted.php";';
        echo '</script>';
    } else {
        die($db->errorInfo());
    }
}





