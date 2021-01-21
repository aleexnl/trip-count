<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('functions.php') ?>
    <?php if (!isset($_SESSION['user'])) header("location: pages/login.php") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance de Gastos - Grupo <?= $_SESSION['travel_id'] ?></title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }


        header,
        main,
        footer {
            width: 100vw;
            padding: 0 !important;
        }

        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 84vh;
        }

        table,
        td {
            border: 1px solid black;
        }

        table {
            margin-top: 10px;
            border-collapse: collapse;
        }

        table thead {
            background-color: #323039;
            color: #fff;
        }

        table tbody {
            background-color: #a6d2f7;
        }

        table button.order_creation,
        table button.order_update {
            background-color: transparent;
            border: none;
            outline: none;
        }

        table thead th,
        table tbody td {
            padding: 10px;
        }

        ul.breadcrumb {
            padding: 10px 16px;
            list-style: none;
            background-color: #eee;
            margin: 10px auto auto 0;
        }

        ul.breadcrumb li {
            display: inline;
            font-size: 18px;
        }

        ul.breadcrumb li+li:before {
            padding: 8px;
            color: black;
            content: "/\00a0";
        }

        ul.breadcrumb li a {
            color: #0275d8;
            text-decoration: none;
        }

        ul.breadcrumb li a:hover {
            color: #01447e;
            text-decoration: underline;
        }
    </style>
    <?php

    function calculateResult($paid, $amount)
    {
        $res = $paid - $amount;
        if ($res == 0) return "Este usuario ya ha pagado todo.";
        else return ($res < 0) ? "<span style='color: #d82525'>Este usuario le faltan por enviar: " . abs($res) . "€<span>" : " <span style='color: #62753b'>Este usuario ha enviado " . abs($res) . "€ de mas.<span>";
    }

    $table_rows = "";
    $total_spends = [];

    $groups = $bd->prepare("SELECT DISTINCT g.group_id, u.user_id, u.name FROM `Groups` g, Users u WHERE trip_id = $_SESSION[travel_id] AND g.user_id = u.user_id;");
    $groups->execute();

    $group_id = 0;
    foreach ($groups as $user) {
        $group_id = $user[0];
        $total_spends["user_$user[1]"] = [];
        $total_spends["user_$user[1]"]['name'] = $user[2];
        $total_spends["user_$user[1]"]['total_amount'] = 0;
        $total_spends["user_$user[1]"]['paid'] = 0;
    }

    $spends = $bd->prepare("SELECT g.group_expense_id, u.name, g.price FROM `Group_Expenses` g, Users u WHERE g.group_id = $group_id AND g.paid_by = u.user_id;");
    $spends->execute();

    foreach ($spends as $spend) {
        $personals = $bd->prepare("SELECT u.user_id, u.name, p.amount, p.payment_status FROM `Personal_Expenses` p, Users u WHERE p.group_expense_id = $spend[0] AND p.user_id = u.user_id;");
        $personals->execute();

        foreach ($personals as $personal) {
            $total_spends["user_$personal[0]"]['total_amount'] += $personal[2];
            if ($personal[3])
                $total_spends["user_$personal[0]"]['paid'] += $personal[2];
        }
    }

    foreach ($total_spends as $key => $value) {
        $paid_msg = calculateResult($value['paid'], $value['total_amount']);
        $table_rows .= "<tr>
        <td>$value[name]</td>
        <td>$value[total_amount]€</td>
        <td>$paid_msg</td>
        </tr>
        ";
    }
    ?>
</head>

<body>
    <?php include_once('templates/header.html') ?>
    <main>
        <ul class="breadcrumb">
            <li><a href="home.php">Home</a></li>
            <li><a href="#">Balance de Gastos - Grupo <?= $_SESSION['travel_id'] ?></a></li>
        </ul>
        <table class="table-balance">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?= $table_rows ?>
            </tbody>
        </table>
    </main>
    <?php include_once('templates/footer.html') ?>
</body>

</html>