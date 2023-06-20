<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);


function showTop10NoLogin($db): void
{
    $query = <<<SQL
            SELECT p.id, p.name, p.surname, COUNT(p.id) AS 'medal'
            FROM `person` p
            JOIN placement pl ON pl.person_id = p.id
            WHERE pl.placing = 1
            GROUP BY p.id
            ORDER BY COUNT(p.id) DESC
            LIMIT 10;
        SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "
                <tr>
                    <td>
                    <button class='btn btn-primary'><a class='text-decoration-none text-white' href='tables/winner.php?id={$result["id"]}'>{$result["name"]}</a></button>
                    </td>
                    <td> {$result["surname"]}   </td>
                    <td> {$result["medal"]}      </td>
                </tr>";
    }
}

function showTop10($db): void
{
    $query = <<<SQL
            SELECT p.id, p.name, p.surname, COUNT(p.id) AS 'medal'
            FROM `person` p
            JOIN placement pl ON pl.person_id = p.id
            WHERE pl.placing = 1
            GROUP BY p.id
            ORDER BY COUNT(p.id) DESC
            LIMIT 10;
        SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "
                <tr>
                    <td>
                    <button class='btn btn-primary'><a class='text-decoration-none text-white' href='tables/winnerRestricted.php?id={$result["id"]}'>{$result["name"]}</a></button>
                    </td>
                    <td> {$result["surname"]}   </td>
                    <td> {$result["medal"]}      </td>
                    <td>
                    <button type='submit' class='btn btn-primary'><a class='text-decoration-none text-white' href='functions/updatePerson.php?updateid={$result["id"]}'>Uprav Atléta</a></button>
                    <button type='submit' class='btn btn-danger'><a class='text-decoration-none text-white' href='functions/action.php?deleteid={$result["id"]}'>Vymaž Atléta</a></button>
                </td>
                </tr>";
    }
}

function showWinners($db): void
{
    $query = <<<SQL
            SELECT pe.id, pe.name, pe.surname, g.year, g.city, g.country, g.type, pl.id AS plID, pl.discipline, pe.birth_day,
                   pe.birth_place, pe.birth_country, pe.death_day, pe.death_place, pe.death_country
            FROM person AS pe 
            JOIN placement AS pl ON pe.id = pl.person_id
            JOIN game AS g ON pl.game_id = g.id
            WHERE pl.placing = 1
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "
            <tr>
                <td> {$result["id"]}  </td>
                <td>
                    <button class='btn btn-primary'><a class='text-decoration-none text-white' href='tables/winnerRestricted.php?id={$result["id"]}'>{$result["name"]}</a></button>
                </td>
                <td> {$result["surname"]}  </td>
                <td> {$result["year"]}      </td>
                <td> {$result["city"]}      </td>
                <td> {$result["type"]}      </td>
                <td> {$result["country"]}   </td>
                <td> {$result["discipline"]}</td>
                <td>
                    <button type='submit' class='btn btn-primary'><a class='text-decoration-none text-white' href='functions/updatePlacement.php?updatePlacementID={$result["plID"]}'>Uprav</a></button>
                    <button type='submit' class='btn btn-danger'><a class='text-decoration-none text-white' href='functions/action.php?deletePlacementID={$result["plID"]}'>Vymaž</a></button>
                </td>
            </tr>";
    }
}

function showWinnersNoLogin($db): void
{
    $query = <<<SQL
            SELECT pe.id, pe.name, pe.surname, g.year, g.city, g.country, g.type, pl.discipline
            FROM person AS pe 
            JOIN placement AS pl ON pe.id = pl.person_id
            JOIN game AS g ON pl.game_id = g.id
            WHERE pl.placing = 1
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "
            <tr>
                <td> {$result["id"]}  </td>
                <td>
                <button class='btn btn-primary'><a class='text-decoration-none text-white' href='tables/winner.php?id={$result["id"]}'>{$result["name"]}</a></button>
                </td>
                <td> {$result["surname"]}  </td>
                <td> {$result["year"]}      </td>
                <td> {$result["city"]}      </td>
                <td> {$result["type"]}      </td>
                <td> {$result["country"]}   </td>
                <td> {$result["discipline"]}</td>
            </tr>";
    }
}

function showAthletes($db): void
{
    $query = <<<SQL
            SELECT * FROM person
            SQL;

    $stmt = $db->query($query);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "<option value='{$result["id"]}'>{$result["name"]} {$result["surname"]}</option>";
    }
}

function showAthletesID($db, $id): void
{
    $query = <<<SQL
            SELECT * FROM person
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if ($result["id"] === $id) {
            echo "<option value='{$result["id"]}' selected>{$result["name"]} {$result["surname"]}</option>";
        } else {
            echo "<option value='{$result["id"]}'>{$result["name"]} {$result["surname"]}</option>";
        }
    }
}

function showGames($db): void
{
    $query = <<<SQL
            SELECT * FROM game
            SQL;
    $stmt = $db->query($query);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        echo "<option value='{$result["id"]}'>{$result["type"]} {$result["country"]} {$result["year"]}</option>";
    }
}

function showGamesID($db, $id): void
{
    $query = <<<SQL
            SELECT * FROM game
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if ($result["id"] === $id) {
            echo "<option value='{$result["id"]}' selected>{$result["type"]} {$result["country"]} {$result["year"]}</option>";
        } else {
            echo "<option value='{$result["id"]}'>{$result["type"]} {$result["country"]} {$result["year"]}</option>";
        }
    }
}



