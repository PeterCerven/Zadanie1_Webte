<?php

function addActionToHistory(PDO $db, $action, $table_name): void
{
    session_start();
    if (isset($_SESSION['google_id'])) {
        $sql = <<<SQL
            INSERT INTO actions (action, table_name, google_id)
            VALUES (?,?,?)
            SQL;
        $stmt = $db->prepare($sql);
        $stmt->execute([$action,$table_name, $_SESSION['google_id']]);
    } elseif (isset($_SESSION["account_id"])) {
        $sql = <<<SQL
            INSERT INTO actions (action, table_name, account_id)
            VALUES (?,?,?)
            SQL;
        $stmt = $db->prepare($sql);
        $stmt->execute([$action,$table_name, $_SESSION["account_id"]]);
    }
}


function existsPerson($db, $name, $surname, $birthDay, $birthPlace, $birthCountry) : bool {
    $sql = <<<SQL
    SELECT * FROM person
    WHERE name=:name AND surname=:surname AND birth_day=:birthDay AND birth_place=:birthPlace AND birth_country=:birthCountry;
    SQL;

    $stmt = $db->prepare($sql);

    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":surname", $surname, PDO::PARAM_STR);
    $stmt->bindParam(":birthDay", $birthDay, PDO::PARAM_STR);
    $stmt->bindParam(":birthPlace", $birthPlace, PDO::PARAM_STR);
    $stmt->bindParam(":birthCountry", $birthCountry, PDO::PARAM_STR);


    $stmt->execute();
    return $stmt->rowCount() == 1;
}

function existsPlacement($db, $person_id, $game_id, $placing, $discipline) : bool {
    $sql = <<<SQL
    SELECT * FROM placement
    WHERE person_id=:person_id AND game_id=:game_id AND placing=:placing AND discipline=:discipline
    SQL;

    $stmt = $db->prepare($sql);

    $stmt->bindParam(":person_id", $person_id, PDO::PARAM_INT);
    $stmt->bindParam(":game_id", $game_id, PDO::PARAM_INT);
    $stmt->bindParam(":placing", $placing, PDO::PARAM_INT);
    $stmt->bindParam(":discipline", $discipline, PDO::PARAM_INT);


    $stmt->execute();
    return $stmt->rowCount() == 1;
}

function validateEmail($email, $db) : String
{
    $sql = <<<SQL
    SELECT * FROM accounts
    WHERE email=?;
    SQL;

    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->rowCount() == 1) {
        return "Tento email je už použitý.";
        
    }
    return "";
}

function validateLogin($login, $db) : String
{
    $sql = <<<SQL
    SELECT * FROM accounts
    WHERE login=?;
    SQL;

    $stmt = $db->prepare($sql);
    $stmt->execute([$login]);

    if ($stmt->rowCount() == 1) {
       return "Toto prihlasovacie meno je už použité.";

    }
    return "";
}

