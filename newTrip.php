<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Viaje</title>
    <script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
    <style>
        * {
            font-family: Ubuntu, "sans-serif";
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #fff;
        }

        header {
            background-color: #323039;
            display: flex;
            flex-direction: row;
            padding: 0 20px;
            box-shadow: 0 0 9px 2px black;
        }

        header>a.link {
            margin-right: 25px;
            font-size: 1.4em;
            text-decoration: none;
            color: #fff;
            text-shadow: 0 0 2px black;
            transition: 0.3s;
        }

        header>a.link:hover,
        header>a.link.active {
            color: #00daff;
            transition: 0.3s;
        }

        p.title {
            text-align: center;
            font-size: 4em;
            font-weight: bold;
            margin: 2% 0 0 0;
        }

        div.container-messages {
            width: 100%;
            display: flex;
            flex-flow: column wrap;
            justify-content: center;
            white-space: pre-line;
        }

        div.container-messages div {
            width: 65%;
            margin: 5px auto;
            border-radius: 5px;
            text-align: center;
            padding: 10px 0;
        }

        form.new-trip {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            width: 35%;
            padding: 10px;
            margin: 3% auto 0 auto;
        }

        form.new-trip>div {
            width: 100%;
            display: flex;
            margin-bottom: 25px;
        }

        form.new-trip>div.coin {
            width: 100%;
            display: flex;
            margin-bottom: 0;
            margin-top: 20px;
            place-content: center;
        }

        form.new-trip>div>label {
            font-size: 23px;
        }

        form.new-trip>label {
            font-size: 20px;
        }

        form.new-trip>div>label[for="nameTrip"] {
            width: 42%;
        }

        form.new-trip>div>label[for="descriptionTrip"] {
            width: 27%;
        }

        form.new-trip>div>input[type="text"] {
            width: -webkit-fill-available;
            width: -moz-available;
            background-color: #fff;
            border: 0;
            border-bottom: 2px solid #000;
            font-size: 19px;
            padding: 1px 5px 2px 5px;
        }

        select[name="coinTrip"] {
            margin-left: 15px;
            border: 0;
            font-size: 17px;
            background-color: #fff;
            border-bottom: 2px solid #000;
        }

        form.new-trip>div>input[type="text"]:focus,
        form.new-trip>div>input[type="text"]:hover,
        select[name="coinTrip"]:focus,
        select[name="coinTrip"]:hover {
            background-color: #0000001f;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            outline: none;
        }

        form.new-trip>p.author {
            width: 100%;
            font-size: 17px;
            text-align: center;
            margin-top: 25px;
        }

        form.new-trip>div.box-btn {
            width: 80%;
            margin-top: 20px;
            display: flex;
            justify-content: space-evenly;
        }

        form.new-trip>div.box-btn>button {
            font-size: 1.2em;
            background-color: #18c3f8;
            box-shadow: 0 0 4px #18c3f8;
            border: 2px solid #18c3f8;
            padding: 10px;
            border-radius: 5px;
            color: #f3f3f3;
            cursor: pointer;
        }

        form.new-trip>div.box-btn>button:focus {
            outline: none;
            border: 2px solid #549ab3;
        }

        .fa-v-align {
            vertical-align: text-bottom;
        }

        .msg-info {
            color: #fff;
            background-color: #2196F3;
            border: 1px solid #58748a;
            box-shadow: 0 0 5px #2196F3;
        }

        .msg-success {
            color: #fff;
            background-color: #4CAF50;
            border: 1px solid #39883c;
            box-shadow: 0 0 5px #4CAF50;
        }

        .msg-warning {
            color: #fff;
            background-color: #ff9800;
            border: 1px solid #ad7c33;
            box-shadow: 0 0 5px #ff9800;
        }

        .msg-error {
            color: #fff;
            background-color: #f44336;
            border: 1px solid #a0342c;
            box-shadow: 0 0 5px #f44336;
        }
    </style>
    <?php 
        // comprobar que la session no ha expirado
        require("./foreignExchange.php");
    ?>
</head>

<body>
    <header>
        <a class="link" href="./">
            <p><i class="fas fa-home"></i> Home</p>
        </a>
        <a class="link" href="#login">
            <p><i class="fas fa-lock-open"></i> Login</p>
        </a>
        <a class="link" href="#cerrar-sesion">
            <p><i class="fas fa-lock"></i> Cerrar Sesión</p>
        </a>
        <a class="link" href="#registrarse">
            <p><i class="fas fa-file-signature"></i> Registrarse</p>
        </a>
        <a class="link" href="#username">
            <p><i class="fas fa-user"></i> Carlos</p>
        </a>
    </header>
    <p class="title"><i class="fas fa-globe-americas"></i> Nuevo Viaje <i class="fas fa-globe-europe"></i></p>
    <div class="container-messages"></div>
    <form class="new-trip" action="functions.php" method="post">
        <div class="name">
            <label for="nameTrip">Nombre del viaje: </label>
            <input type="text" name="nameTrip">
        </div>
        <div class="description">
            <label for="descriptionTrip">Descripción: </label>
            <input type="text" name="descriptionTrip">
        </div>
        <label for="departureDate">Salida:&#160;</label>
        <input type="datetime-local" name="departureDate" placeholder="DD/MM/YYYY HH:MM">
        <label for="returnDate">&#160;&#160;&#160;Regreso:&#160;</label>
        <input type="datetime-local" name="returnDate" placeholder="DD/MM/YYYY HH:MM">
        <div class="coin">
            <label for="coinTrip">Moneda que se utilizará: </label>
            <select name="coinTrip">
                <?php
                foreach ($foreignExchange as $coin) {
                    if ($coin == "EUR")
                        echo "<option selected value='$coin'>$coin</option>";
                    else
                        echo "<option value='$coin'>$coin</option>";
                }
                ?>
            </select>
        </div>
        <p class="author">Viaje creado por <strong>Juan Carlos Salinas</strong></p>
        <div class="box-btn">
            <button class="redo">Restablecer <i class="fas fa-redo-alt fa-v-align"></i></button>
            <button class="create-trip">Crear Viaje <i class="fas fa-plane fa-v-align"></i></button>
        </div>
    </form>
    <script>
        let btnRedo = document.getElementsByClassName("redo")[0];
        let createTrip = document.getElementsByClassName("new-trip")[0];
        let departureDate = document.getElementsByName("departureDate")[0];
        let returnDate = document.getElementsByName("returnDate")[0];


        function generateMessages(type, text, parentName, seconds) {
            let parent = document.getElementsByClassName(parentName)[0];
            let msg = document.createElement("div");
            if (type == "info") msg.className = "msg-info";
            else if (type == "success") msg.className = "msg-success";
            else if (type == "error") msg.className = "msg-error";
            else if (type == "warning") msg.className = "msg-warning";
            msg.appendChild(document.createTextNode(text));
            parent.prepend(msg);
            countdown(parent, seconds);
        }

        function countdown(parent, seconds) {
            setTimeout(() => {
                parent.removeChild(parent.lastElementChild);
            }, seconds * 1000);
        }

        function validateInputTextLength(name, size) {
            let input = document.getElementsByName(name)[0];
            if (input.value.length < size) return true;
            else return false;
        }

        function isNull(name) {
            return document.getElementsByName(name)[0].value.replace(/ /g, "");
        }

        function validateInputDate(input) {
            return Date.parse(input.value);
        }

        // IF date1 IS GREATER THAN date2, RETURN TRUE
        function compareDates(date1, date2) {
            return date1 > date2;
        }

        btnRedo.onclick = (e) => {
            e.preventDefault();
            generateMessages("warning", "WARNING: Los datos del viaje se han restablecido.", "container-messages");
            document.getElementsByName("nameTrip")[0].value = "";
            document.getElementsByName("descriptionTrip")[0].value = "";
            document.getElementsByName("departureDate")[0].value = "";
            document.getElementsByName("returnDate")[0].value = "";
            document.getElementsByName("coinTrip")[0].selectedIndex = "39";
        }

        createTrip.onsubmit = (e) => {
            e.preventDefault();
            let textError = "";

            // CHECK INPUT NAME
            if (!isNull("nameTrip")) textError += "ERROR: El nombre está vacio.\n\r";
            else {
                if (!validateInputTextLength("nameTrip", 50)) textError += "ERROR: El nombre tiene una logitud superior a 50 caracteres.\n";
            }

            // CHECK INPUT DESCRIPTION
            if (!isNull("descriptionTrip")) textError += "ERROR: La descripción está vacia.\n";
            else {
                if (!validateInputTextLength("descriptionTrip", 255)) textError += "ERROR: La descripción tiene una logitud superior a 255 caracteres.\n";
            }

            // CHECK INPUT DEPARTURE DATE
            if (!validateInputDate(departureDate)) textError += "ERROR: Fecha de salida no valida.\n";
            else {
                if (!compareDates(new Date(departureDate.value).getTime(), new Date().getTime())) textError += "ERROR: La fecha de salida es mas pequeña que la fecha actual.\n";
            }

            // CHECK INPUT RETURN DATE
            if (!validateInputDate(returnDate)) textError += "ERROR: Fecha de regreso no valida.\n";
            else {
                if (!compareDates(new Date(returnDate.value).getTime(), new Date(departureDate.value).getTime())) textError += "ERROR: La fecha de regreso es mas pequeña que la fecha de salida.\n";
            }

            if (textError != "")
                generateMessages("error", textError, "container-messages", 7);
            else e.currentTarget.submit();
        }
    </script>
</body>

</html>