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
            place-content: center;
        }

        form.new-trip>div>label {
            font-size: 23px;
        }

        form.new-trip>div>label[for="nameTrip"] {
            width: 42%;
        }

        form.new-trip>div>label[for="descriptionTrip"] {
            width: 27%;
        }

        form.new-trip>div>input[type="text"] {
            width: -webkit-fill-available;
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
    </style>
    <?php require("./foreignExchange.php"); ?>
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
    <form class="new-trip" action="#" method="post">
        <div class="name">
            <label for="nameTrip">Nombre del viaje: </label>
            <input type="text" name="nameTrip">
        </div>
        <div class="description">
            <label for="descriptionTrip">Descripción: </label>
            <input type="text" name="descriptionTrip">
        </div>
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

        btnRedo.onclick = (e) => {
            e.preventDefault();
            document.getElementsByName("nameTrip")[0].value = "";
            document.getElementsByName("descriptionTrip")[0].value = "";

        }
    </script>
</body>

</html>