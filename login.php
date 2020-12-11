<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="login.css">
</head>
<?php
include_once(__DIR__ . "/connection.php");
$msgType; // Variable para controlar que tipo de error se le dará al usuario
$msg = "La solicitud no es correcta, porfavor reenvia el formulario. En el caso de persistir este error, consulta con un administrador."; // Mensaje a mostrar
if (isset($_POST["userMail"], $_POST["userPass"])) { // Check that the server recived a post signal.
    if ($_POST["userMail"] && $_POST["userPass"]) { // Check that both variables are not empty
        $email = filter_var($_POST["userMail"], FILTER_SANITIZE_EMAIL); // Sanitize email input
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the variable contains an email.
            $password = hash("sha256", filter_var($_POST["userPass"], FILTER_SANITIZE_STRING)); // Sanitize string anmd encrypt in SHA256.
            $query = $bd -> prepare("SELECT * FROM users WHERE email = ? AND password = ?"); // Prepare the query.
            $query->bindParam(1, $email); // Bind parameters.
            $query->bindParam(2, $password);
            $query->execute(); // Execute the query
            if ($query->rowCount() > 0) { // Chef if the query returned something.
                $msgType = "success";
                $msg = "Inicio de sesión correcto, redireccionando...";
            } else { // If no user was found witth that credentials.
                $msgType = "error";
                $msg = "<b>ERROR:</b> El usuario o la contraseña no existe.";
            }
        } else { // If the mail is not valid.
            $msgType = "error";
            $msg = "<b>ERROR:</b> El email no es valido. Porfavor introduce una dirección de correo valida, como user@gmail.com.";
        }
    } else { // If any field was empty, throw error.
        $msgType = "error";
        $msg = "<b>ERROR:</b> No se ha proporcionado información en todos los campos, por favor, reenvia el formulario con los datos rellenados.";
    }
}
?>

<body>
    <div class="content">
        <div class="centered-form">
            <h1>INICIAR SESIÓN</h1>
            <?php
            if (isset($msgType)) { // Check if the communication is needed
                switch ($msgType) {
                    case 'error': ?>
                        <div class="message error-message">
                            <?= $msg ?>
                        </div>
                        <?php break;
                    case 'success': ?>
                        <div class="message success-message">
                            <?= $msg ?>
                        </div>
                        <?php break;
                    default: ?>
                        <div class="message  error-message">
                            <?= $msg ?>
                        </div>
                        <?php break;
                }
            }
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="userMail">Correo electrónico</label>
                    <input type="email" name="userMail" id="userMail" placeholder="user@mail.com" required>
                </div>
                <div class="form-group">
                    <label for="userPass">Contraseña</label>
                    <input type="password" name="userPass" id="userPass" required>
                </div>
                <div class="form-group form-checkbox">
                    <input type="checkbox" name="rememberUser" id="rememberUser">
                    &nbsp;
                    <label for="rememberUser">Guardar mi información</label>
                </div>
                <div class="form-group">
                    <button class="button-primary" type="submit">Iniciar sesión</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>