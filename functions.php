<?php

require_once("./connection.php");
session_start();

function insertQuery($bd, $sql, $params)
{
    $query = $bd->prepare($sql); // Prepare the query.
    for ($i = 0; $i < sizeOf($params); $i++)
        $query->bindParam($i + 1, $params[$i]);
    $query->execute(); // Execute the query
}

if (isset($_POST['nameTrip'])) {
    if (isset($_SESSION['user'][0])) {
        $userId = $_SESSION['user'][0];
        $_SESSION['first_load_invitation_page'] = true;
        $params = [];
        // PARAMETERS OF TRAVEL
        foreach ($_POST as $value)
            array_push($params, filter_var($value, FILTER_SANITIZE_STRING));
        insertQuery($bd, "INSERT INTO `Travels`(`name`, `description`, `coin`) VALUES (?, ?, ?);", $params); // INSERT NEW TRAVEL

        $travelId = $bd->lastInsertId();
        insertQuery($bd, "INSERT INTO `Groups` VALUES (null, $userId, $travelId)", []); // INSERT NEW GROUP

        $_SESSION['trip_name'] = $params[0];
        header("location: ./invitations.php");
    } else header("location:login.php?status=session_expired");
} else if (isset($_GET['action']) == "new-spend" && isset($_GET['id']) && isset($_SESSION['user'][0])) {
    $id_travel = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
    $travel_details = $bd->prepare("SELECT trip_id FROM Travels WHERE trip_id = $id_travel");
    $travel_details->execute();
    $id_travel = $travel_details->fetch();

    if ($id_travel) {
        $_SESSION['travelSelected'] = $id_travel[0];
        header("location: enterPayment.php");
    } else {
        $_SESSION['travelSelected'] = null;
        header("location: home.php?msg=ID del vuelo no encontrado.");
    }
    print_r($_SESSION);
}
