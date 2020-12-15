<?php

require_once("./connection.php");

function insertQuery($bd, $sql, $params)
{
    $query = $bd->prepare($sql); // Prepare the query.
    for ($i = 0; $i < sizeOf($params); $i++)
        $query->bindParam($i + 1, $params[$i]);
    $query->execute(); // Execute the query
}

if (isset($_POST['nameTrip'])) {
    $params = [];
    // PARAMETERS OF TRAVEL
    foreach ($_POST as $value)
        array_push($params, filter_var($value, FILTER_SANITIZE_STRING));
    insertQuery($bd, "INSERT INTO `Travels` VALUES (null, ?, ?, ?, ?, ?, now(), now());", $params); // INSERT NEW TRAVEL

    $travelId = $bd->lastInsertId();
    $userId = 1;
    insertQuery($bd, "INSERT INTO `Groups` VALUES (null, $userId, $travelId)", []); // INSERT NEW GROUP

    header("location: ./invitations.php");
}
