<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('functions.php') ?>
    <?php if (!isset($_SESSION['user'])) header("location: pages/login.php") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar viaje <?= $_SESSION['travelSelected'][0] ?></title>
    <script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php
    $foreign_exchange = ['AED', 'AFN', 'ALL', 'AMD', 'AOA', 'ARS', 'AUD', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'GBP', 'GEL', 'GHS', 'GMD', 'GNF', 'GTQ', 'GYD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SLL', 'SOS', 'SRD', 'SSP', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'YER', 'ZAR', 'ZMW'];
    ?>

</head>

<body id="edittravel">
    <?php include_once('templates/header.html') ?>
    <main>
        <ul class="breadcrumb">
            <li><a href="home.php">Home</a></li>
            <li><a href="#"> Editar viaje <?= $_SESSION['travelSelected'][0] ?></a></li>
        </ul>
        <div class="container-messages">
        </div>
        <h1 class="text-center">Editar viaje del Grupo <?= $_SESSION['travelSelected'][0] ?></h1>
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
                <input type="text" name="description" value="<?= $_SESSION['travelSelected'][2] ?>">
            </div>
            <div class="form-btn-group">
                <button class="button-primary">Guardar cambios <i class="fas fa-save"></i></button>
                <button class="btn-add-users button-primary">Añadir nuevos usuarios <i class="fas fa-user-edit"></i></button>
            </div>
        </form>
    </main>
    <?php include_once('templates/footer.html') ?>
    <script>
        let btnAddUsers = document.getElementsByClassName("btn-add-users")[0];
        let formEditTravel = document.getElementsByClassName("form-edit-travel")[0];

        function generateMessages(type, text, parentName, seconds) {
            let parent = document.getElementsByClassName(parentName)[0];
            let msg = document.createElement("div");
            if (type == "info") msg.className = "msg-info";
            else if (type == "success") msg.className = "msg-success";
            else if (type == "error") msg.className = "msg-error";
            else if (type == "warning") msg.className = "msg-warning";
            msg.appendChild(document.createTextNode(text));
            parent.prepend(msg);
            setTimeout(() => {
                parent.firstElementChild.style.opacity = "1";
            }, 1);
            countdown(parent, seconds);
        }

        function countdown(parent, seconds) {
            setTimeout(() => {
                parent.lastElementChild.style.opacity = "0";
                setTimeout(() => {
                    parent.removeChild(parent.lastElementChild);
                }, 400);
            }, seconds * 1000);
        }

        btnAddUsers.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            window.location = "functions.php?action=save-travel-to-invitations";
        }

        formEditTravel.onsubmit = (e) => {
            e.preventDefault();
            formEditTravel.action = "functions.php?action=save-travel";
            formEditTravel.submit();
        }
    </script>
    <script>
        <?php
        if (isset($_SESSION['msg'])) {
            foreach ($_SESSION['msg'] as $msg)
                echo "generateMessages('$msg[0]', '$msg[1]', '$msg[2]', $msg[3]);\n";
            $_SESSION['msg'] = [];
        }
        ?>
    </script>
</body>

</html>