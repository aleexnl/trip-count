<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitaciones</title>
    <script src="https://kit.fontawesome.com/b17b075250.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <?php
    session_start();
    include_once(__DIR__ . '/connection.php');
    $tripName = isset($_SESSION['trip_name']) ? $_SESSION['trip_name'] : '' ;
    $error_messages = []; // Create an error variable to store errors.
    $has_errors = false;

    function sendMail($mail, $subject, $content, $headers)
    {
        return mail($mail, $subject, $content, $headers);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check server request is a POST
        if (isset($_POST['email-1'])) { // If there is at least one email iterate through them
            foreach ($_POST as $key => $value) {
                $email = filter_var($value, FILTER_SANITIZE_EMAIL); // Sanitize email input
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the variable contains an email.
                    $has_errors = true;
                    array_push($error_messages, "<b>ERROR:</b> El email $email no es valido. Porfavor introduce una dirección de correo valida, como user@gmail.com.");
                } else {
                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                    $query = $bd->prepare("SELECT email FROM Users WHERE email = ?"); // Prepare the query.
                    $query->bindParam(1, $email); // Bind parameters.
                    $query->execute(); // Execute the query
                    if ($query->rowCount() > 0) { // Chef if the query returned something.
                        $content = file_get_contents(__DIR__ . '/templates/invitation.html');
                        $result = sendMail($email, "¡Te han invitado a un nuevo viaje!", $content, implode("\r\n", $headers));
                    } else {
                        $content = file_get_contents(__DIR__ . '/templates/new_user_invitation.html');
                        $content = str_replace("%TOKEN_HASH%", $_SESSION['token'], $content);
                        $result = sendMail($email, "Te han invitado a un nuevo viaje", $content, implode("\r\n", $headers));
                    }
                    if (!$result) {
                        $has_errors = true;
                        array_push($error_messages, "<b>ERROR:</b> El email $email no se ha enviado correctamente.");
                    }
                }
            }
        }
    }

    if ($_SESSION['first_load_invitation_page']) {
        $_SESSION['first_load_invitation_page'] = false;
        echo "<script>window.onload = () => { generateMessages('success', 'SUCCESS: Se ha agregado el nuevo viaje: $tripName.', 'container-messages', 4); }</script>";
    }
    ?>
<<<<<<< HEAD
</head>

<body id="invitacions">
=======
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
            margin-top: 20px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            width: 80%;
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

        main {
            min-height: 82vh;
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
>>>>>>> dev
    <?php require_once('templates/header.html'); ?>
    <main>
        <ul class="breadcrumb">
            <li><a href="home.php">Home</a></li>
            <li><a href="#"> Invitaciones <?= $tripName ?></a></li>
        </ul>
        <p class="title">Invitaciones</p>
        <p class="destiny">Introduce los correos de tus compañer@s con los que vas a viajar.<br> <i class="fas fa-plane"></i><?= $tripName ?><i class="fas fa-plane"></i></p>
        <div class="container-messages">
            <?php
            if ($has_errors) // If user had errors during log in
                foreach ($error_messages as $key => $error)
                    echo $error . "</br>";
            ?>
        </div>
        <form class="invitations" action="./invitations.php" method="post">
            <div class="box-mail">
                <label for="email-1">Correo 1:</label>
                <input type="email" class="mails" name="email-1" placeholder="user@mail.com">
            </div>
            <div class="box-btn">
                <button class="remove-mail button-primary" type="button">Eliminar correo <i class="fas fa-minus-square fa-v-align"></i></button>
                <button class="add-mail button-primary" type="button">Añadir correo <i class="fas fa-plus-square fa-v-align"></i></button>
                <button class="send button-primary" type="submit" accesskey="e"><u>E</u>nviar Invitaciones <i class="fas fa-paper-plane fa-v-align"></i></button>
            </div>
        </form>
    </main>
    <?php require_once('templates/footer.html'); ?>
    <script>
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

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        function validateInvitationsMails() {
            let isValid = false;
            let emails = document.getElementsByClassName("mails");
            for (var i = 0; i < emails.length; i++) {
                if (emails[i].value.replace(" ", "") == "") {
                    isValid = false;
                    generateMessages("error", `ERROR: No se ha introducido ningún dato en el correo ${i+1}.`, "container-messages", 4)
                } else {
                    if (validateEmail(emails[i].value)) {
                        isValid = true;
                        // generateMessages("success", `SUCCESS: El correo '${emails[i].value}' se ha introducido correctamente.`, "container-messages", 4);
                    } else {
                        isValid = false;
                        generateMessages("error", `ERROR: El correo '${emails[i].value}' no se ha introducido correctamente.`, "container-messages", 4);
                    }
                }
            }
            return isValid;
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

        function removeLastMail() {
            let mails = document.getElementsByClassName("mails");
            if (mails.length > 1) {
                let element = mails[mails.length - 1].parentElement;
                let parent = element.parentElement;
                parent.removeChild(element);
                generateMessages("info", "INFO: Se ha eliminado el último correo.", "container-messages", 4);
            } else generateMessages("info", "INFO: No se pueden borrar todos los correos.", "container-messages", 4);
        }

        document.getElementsByClassName("invitations")[0].onsubmit = (e) => {
            e.preventDefault();
            validateInvitationsMails() ? e.currentTarget.submit() : null;
        }

        document.getElementsByClassName("add-mail")[0].onclick = (e) => {
            e.preventDefault();
            generateMessages("info", "INFO: Se ha agregado un nuevo correo.", "container-messages", 4);
            createInputMail();
        }

        document.getElementsByClassName("remove-mail")[0].onclick = (e) => {
            e.preventDefault();
            removeLastMail();
        }
    </script>
</body>

</html>