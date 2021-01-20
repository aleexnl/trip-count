<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('functions.php') ?>
    <?php if (!isset($_SESSION['user'])) header("location: pages/login.php") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grupo <?= $_SESSION['travelSelected'][0] ?></title>
    <?php
    $foreign_exchange = ['AED', 'AFN', 'ALL', 'AMD', 'AOA', 'ARS', 'AUD', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'GBP', 'GEL', 'GHS', 'GMD', 'GNF', 'GTQ', 'GYD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'YER', 'ZAR', 'ZMW'];
    ?>
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
        footer {
            width: 100vw;
            padding: 0 !important;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin: 1rem;
        }

        .form-multiple-group {
            display: flex;
            flex-direction: row;
            margin: 1rem 0 1rem 0;
        }

        .form-btn-group {
            display: flex;
            flex-direction: row;
            margin: 1rem;
            justify-content: space-between;
        }

        p.destiny {
            text-align: center;
            font-size: 1.5rem;
            margin: 0;
        }

        .form-group input,
        .form-group select {
            margin: 5px 0 0 0;
            padding: 0.7rem 1.5rem;
            font-size: 1.25rem;
            border-radius: 7px;
            color: #495057;
            border: 1px solid #ced4da;
            box-shadow: 0 0 5px #18c3d859;
        }

        .form-group label {
            font-size: 1.2rem;
        }

        .text-center {
            text-align: center;
        }

        button {
            padding: 1rem 0.5rem;
            border: 0;
            background-color: #18c3f8;
            color: #ffffff;
            font-size: 1.25rem;
            border-radius: 7px;
            box-shadow: 0px 18px 40px -12px rgba(24, 195, 216, 0.35);
            cursor: pointer;
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
</head>

<body>
    <?php include_once('templates/header.html') ?>
    <main>
        <ul class="breadcrumb">
            <li><a href="home.php">Home</a></li>
            <li><a href="#"> Editar viaje <?php $_SESSION['travelSelected'][0] ?></a></li>
        </ul>
        <h1 class="text-center">Editar viaje del grupo <?= $_SESSION['travelSelected'][0] ?></h1>
        <form class="form-edit-travel" method="POST">
            <div class="form-multiple-group">
                <div class="form-group">
                    <label for="name">Nombre del viaje</label>
                    <input type="text" name="name" value="<?= $_SESSION['travelSelected'][1] ?>" required>
                </div>
                <div class="form-group">
                    <label for="coin">Moneda</label>
                    <select name="coin">
                        <?php
                        foreach ($foreign_exchange as $coin) {
                            if ($coin == $_SESSION['travelSelected'][3])
                                echo "<option selected value='$coin'>$coin</option>";
                            else
                                echo "<option value='$coin'>$coin</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Descripción</label>
                <input type="text" name="description" value="<?= $_SESSION['travelSelected'][2] ?>" required>
            </div>
            <div class="form-btn-group">
                <button class="button-primary" type="submit">Guardar cambios</button>
                <button class="button-primary" onclick="">Añadir nuevos usuarios</button>
            </div>
        </form>
    </main>
    <?php include_once('templates/footer.html') ?>
    <script>
        function validateInputTextLength(name, size) {
            let input = document.getElementsByName(name)[0];
            if (input.value.length < size) return true;
            else return false;
        }

        function isNull(name) {
            return document.getElementsByName(name)[0].value.replace(/ /g, "");
        }
    </script>
</body>

</html>