<?php

require_once("./connection.php");
session_start();

function uploadFilesInServer($group_id, $new_group_expend_id)
{
    $folder_name = md5($group_id);
    $valid_ext = array("jpg", "png", "jpeg", "txt", "pdf");
    $count_files = count($_FILES['files']['name']);
    $upload_location = __DIR__ . "/media/$folder_name/";

    if (!is_dir($upload_location))
        mkdir($upload_location, 0777);

    for ($i = 0; $i < $count_files; $i++) {
        $filename = $_FILES['files']['name'][$i];

        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        if (in_array($file_extension, $valid_ext)) {
            $filename = $new_group_expend_id . "_" . basename($filename, ".$file_extension") . "_" . $i . "." . $file_extension;
            $path = $upload_location . $filename;
            if (!move_uploaded_file($_FILES['files']['tmp_name'][$i], $path)) {
                if (!isset($_SESSION['msg'])) $_SESSION['msg'] = [];
                $msg = ["error", "No se ha podido subir la imagen $filename.", "container-messages", 5];
                array_push($_SESSION['msg'], $msg);
            }
        }
    }
}

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

        $id_travel = $bd->lastInsertId();
        $new_travel = $bd->prepare("SELECT `trip_id`, `creation_date` FROM Travels WHERE trip_id = $id_travel");
        $new_travel->execute();
        $travel = $new_travel->fetch();
        $string = $travel[1] . $travel[0];
        insertQuery($bd, "UPDATE Travels SET token=md5('$string') WHERE trip_id=$id_travel;", []); // INSERT NEW TRAVEL

        insertQuery($bd, "INSERT INTO `Groups` VALUES (null, $userId, $id_travel)", []); // INSERT NEW GROUP

        $new_token = $bd->prepare("SELECT `token` FROM Travels WHERE trip_id = $id_travel");
        $new_token->execute();
        $token = $new_token->fetch();

        $_SESSION['trip_name'] = $params[0];
        $_SESSION['token'] = $token[0];
        header("location: ./invitations.php");
    } else header("location:login.php?status=session_expired");
} else if (isset($_GET['action']) && $_GET['action'] == "new-spend" && isset($_GET['id']) && isset($_SESSION['user'][0])) {
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
} else if ((isset($_GET['action']) && $_GET['action'] == "new-spend" || isset($_GET['action']) && $_GET['action'] == "new-spend-advanced") && isset($_POST['paid-by']) && isset($_POST['total-expend']) >= 1 && isset($_POST['total-expend']) <= 10000) {

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

    uploadFilesInServer($group_id, $new_group_expend_id);
    if ($_GET['action'] == "new-spend") {
        $params = [
            filter_var($paid_by, FILTER_SANITIZE_STRING),
            filter_var($price, FILTER_SANITIZE_STRING),
            random_int(0, 1),
            $new_group_expend_id
        ];
        insertQuery($bd, "INSERT INTO Personal_Expenses(`user_id`, amount, payment_status, group_expense_id) VALUES (?, ?, ?, ?);", $params); // INSERT NEW PERSONAL EXPEND
    } else if ($_GET['action'] == "new-spend-advanced" && isset($_GET['ids']) && isset($_GET['prices'])) {
        $ids = [];
        $id_positions = explode("-", $_GET['ids']);
        for ($i = 0; $i < sizeOf($id_positions); $i++)
            array_push($ids, explode(" - ", $_SESSION['newSpend']['users'][$id_positions[$i]])[0]);

        $prices = explode("-", $_GET['prices']);

        for ($i = 0; $i < sizeOf($ids); $i++) {
            $params = [
                filter_var($ids[$i], FILTER_SANITIZE_STRING),
                filter_var($prices[$i], FILTER_SANITIZE_STRING),
                random_int(0, 1),
                $new_group_expend_id
            ];
            insertQuery($bd, "INSERT INTO Personal_Expenses(`user_id`, amount, payment_status, group_expense_id) VALUES (?, ?, ?, ?);", $params); // INSERT NEW PERSONAL EXPEND
        }
    }
    if (!isset($_SESSION['msg'])) $_SESSION['msg'] = [];
    $msg = ["success", "Se ha agregado un nuevo gasto al viaje " . $_SESSION['newSpend']['tripName'] . " de $price.", "container-messages", 5];
    array_push($_SESSION['msg'], $msg);
    header("location: home.php");
} else if (isset($_GET['action']) && $_GET['action'] == "edit-travel" && isset($_GET['group']) && $_GET['group'] >= 1) {
    $id_travel = filter_var($_GET['group'], FILTER_SANITIZE_STRING);
    $travel_details = $bd->prepare("SELECT `trip_id`, `name`, `description`, `coin` FROM Travels WHERE trip_id = $id_travel");
    $travel_details->execute();
    $travel = $travel_details->fetch();

    $_SESSION['travelSelected'] = $travel;
    header("location: editTravel.php");
} else if (isset($_GET['action']) && $_GET['action'] == "save-travel") {
    $id_travel = $_SESSION['travelSelected'][0];

    $params = [];
    foreach ($_POST as $value)
        array_push($params, filter_var($value, FILTER_SANITIZE_STRING));

    insertQuery($bd, "UPDATE Travels SET `name` = ? , `coin`= ? , `description` = ?  WHERE trip_id=$id_travel;", $params);

    $_SESSION['travelSelected'][1] = $params[0];
    $_SESSION['travelSelected'][2] = $params[2];
    $_SESSION['travelSelected'][3] = $params[1];

    if (!isset($_SESSION['msg'])) $_SESSION['msg'] = [];
    $msg = ["success", "Los cambios del viaje han sido guardados correctamente.", "container-messages", 5];
    array_push($_SESSION['msg'], $msg);
    header("location: editTravel.php");
} else if (isset($_GET['action']) && $_GET['action'] == "save-travel-to-invitations") {
    $_SESSION['trip_name'] = $_SESSION['travelSelected'][1];
    header("location: invitations.php");
} else if (isset($_GET['action']) && $_GET['action'] == "balance" && isset($_GET['id']) && $_GET['id'] >= 1) {
    $_SESSION["travel_id"] = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
    header("location: balance.php");
}
