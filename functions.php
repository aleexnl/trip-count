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
} else if ((isset($_GET['action']) == "new-spend" || isset($_GET['action']) == "new-spend-advanced") && isset($_POST['paid-by']) && isset($_POST['total-expend']) >= 1 && isset($_POST['total-expend']) <= 10000) {
    $group_id = $_SESSION['newSpend']['groupId'];
    $paid_by = $_POST['paid-by'];
    $price = $_POST['total-expend'];
    
    $params = [
        filter_var($paid_by, FILTER_SANITIZE_STRING),
        filter_var($price, FILTER_SANITIZE_STRING),
        $group_id
    ];
    insertQuery($bd, "INSERT INTO Group_Expenses(paid_by, price, group_id) VALUES (?, ?, ?);", $params); // INSERT NEW GROUP EXPEND

    $new_group_expend_id = $bd->lastInsertId();
    $params = [
        filter_var($paid_by, FILTER_SANITIZE_STRING),
        filter_var($price, FILTER_SANITIZE_STRING),
        random_int(0, 1),
        $new_group_expend_id
    ];
    insertQuery($bd, "INSERT INTO Personal_Expenses(`user_id`, amount, payment_status, group_expense_id) VALUES (?, ?, ?, ?);", $params); // INSERT NEW PERSONAL EXPEND
    
    if (!isset($_SESSION['msg'])) $_SESSION['msg'] = [];
    $msg = ["success", "Se ha agregado un nuevo gasto al viaje ".$_SESSION['newSpend']['tripName']." de $price.", "container-messages", 5];
    array_push($_SESSION['msg'], $msg);
    header("location: home.php");
}
