<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitaciones</title>
    <script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
    <?php
    // AQUÍ SE SUPONE QUE HAGO UNA CONSULTA A LA DB Y OBTENGO LA INFO :)
    $tripName = "Madrid";
    ?>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto&display=swap");

        .button-primary {
            padding: 1rem 0.5rem;
            border: 0;
            background-color: #18c3f8;
            color: #ffffff;
            font-size: 1.25rem;
            border-radius: 7px;
            box-shadow: 0px 18px 40px -12px rgba(24, 195, 216, 0.35);
            cursor: pointer;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            background-color: #fff;
            font-family: "Roboto", sans-serif;
            text-rendering: auto;
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

        p.title {
            text-align: center;
            font-size: 4em;
            font-weight: bold;
            margin: 2% 0 0 0;
        }

        p.destiny {
            text-align: center;
            font-size: 2.5em;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        div.container-messages {
            width: 100%;
            display: flex;
            flex-flow: column wrap;
            justify-content: center;
        }

        div.container-messages div {
            width: 65%;
            margin: 5px auto;
            border-radius: 5px;
            text-align: center;
            padding: 10px 0;
        }

        form.invitations {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            width: 80%;
            padding: 10px;
            margin: 0 auto;
        }

        form.invitations>div.box-mail {
            width: 60%;
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 10px;
        }

        form.invitations>div.box-mail>label {
            font-size: 2em;
        }

        form.invitations>div.box-mail>input[type="email"] {
            z-index: 2;
            width: 75%;
            font-size: 1em;
            background-image: url(images/envelope.png);
            padding-left: 35px;
            background-position: 6px;
            background-size: 23px;
            background-repeat: no-repeat;
            border-radius: 7px;
            border: 2px solid #464646cc;
            box-shadow: 0 0 3px black;
        }

        form.invitations>div.box-mail>input[type="email"]:hover {
            border: 2px solid #000;
        }

        form.invitations>div.box-mail>input[type="email"]:focus {
            outline: none;
            border: 2px solid #000;
        }

        form.invitations>div.box-btn {
            width: 50%;
            margin-top: 20px;
            display: flex;
            justify-content: space-evenly;
        }

        form.invitations>div.box-btn>button:focus {
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

        /*.anim2 {
            animation: 1s animationAddLabelMail both;
        }

        .anim {
            animation: 1.5s animationAddInputMail both;
        }

        @keyframes animationAddLabelMail {
            0% {
                opacity: 0;
                transform: skewY(-12deg) translateX(150%);
            }

            100% {
                opacity: 1;
                transform: skewY(0deg) translateX(0%);
            }
        }

        @keyframes animationAddInputMail {
            0% {
                z-index: 1;
                transform: skewY(0deg) translateY(-120%);
            }

            50% {
                z-index: 1;
                transform: skewY(-2.5deg) translateY(-45%);
            }

            100% {
                z-index: 2;
                transform: skewY(0deg) translateY(0%);
            }
        }*/
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
    <p class="title">Invitaciones</p>
    <p class="destiny">Introduce los correos de tus amigos con los que vas a viajar a <i class="fas fa-plane"></i><?= $tripName ?><i class="fas fa-plane"></i>.</p>
    <div class="container-messages"></div>
    <form class="invitations" action="#" method="post">
        <div class="box-mail">
            <label for="email-1">Correo 1:</label>
            <input type="email" class="mails" name="email-1" placeholder="user@mail.com">
        </div>
        <div class="box-btn">
            <button class="add-mail button-primary">Añadir otro correo <i class="fas fa-plus-square fa-v-align"></i></button>
            <button class="send button-primary" type="submit">Enviar Invitaciones <i class="fas fa-paper-plane fa-v-align"></i></button>
        </div>
    </form>
    <script>
        function generateMessages(type, text, parentName) {
            let parent = document.getElementsByClassName(parentName)[0];
            let msg = document.createElement("div");
            if (type == "info") msg.className = "msg-info";
            else if (type == "success") msg.className = "msg-success";
            else if (type == "error") msg.className = "msg-error";
            else if (type == "warning") msg.className = "msg-warning";
            msg.appendChild(document.createTextNode(text));
            parent.prepend(msg);
            countdown(parent);
        }

        function countdown(parent) {
            setTimeout(() => {
                parent.removeChild(parent.lastElementChild);
            }, 4000);
        }

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        function validate() {
            let emails = document.getElementsByClassName("mails");
            for (var i = 0; i < emails.length; i++) {
                if (emails[i].value.replace(" ", "") == "")
                    generateMessages("error", `ERROR: No se ha introducido ningún dato en el correo ${i+1}.`, "container-messages");
                else {
                    if (validateEmail(emails[i].value))
                        generateMessages("success", `SUCCESS: El correo '${emails[i].value}' se ha introducido correctamente.`, "container-messages");
                    else
                        generateMessages("error", `ERROR: El correo '${emails[i].value}' no se ha introducido correctamente.`, "container-messages");
                }
            }
        }

        function createInputMail() {
            let nextIndexMail = document.getElementsByClassName("mails").length + 1;
            let sibling = document.getElementsByClassName("box-btn")[0];
            let divBoxMail = document.createElement("div");
            divBoxMail.className = "box-mail";

            let label = document.createElement("label");
            label.innerText = "Correo " + nextIndexMail + ":";

            let input = document.createElement("input");
            input.type = "email";
            input.className = "mails";
            input.name = "email-" + nextIndexMail;
            input.placeholder = "user@mail.com";

            divBoxMail.appendChild(label);
            divBoxMail.appendChild(input);

            sibling.parentNode.insertBefore(divBoxMail, sibling);
        }

        document.getElementsByClassName("send")[0].onclick = (e) => {
            e.preventDefault();
            validate();
        }

        document.getElementsByClassName("add-mail")[0].onclick = (e) => {
            e.preventDefault();
            generateMessages("info", "INFO: Se ha agregado un nuevo correo.", "container-messages");
            createInputMail();
        }
    </script>
</body>

</html>