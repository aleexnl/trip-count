<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Destino</title>
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
            background-color: #ccbcbc;
        }

        header {
            background-color: #323039;
            display: flex;
            flex-direction: row;
            padding: 0 20px;
        }

        header>a.link {
            margin-right: 25px;
            font-size: 1.4em;
            text-decoration: none;
            color: #fff;
            text-shadow: 0 0 2px black;
        }

        header>a.link:hover,
        header>a.link.active {
            color: #00daff;
        }
    </style>
</head>

<body>
    <header>
        <a class="link" href="#home">
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
</body>

</html>