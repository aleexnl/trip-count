<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../style.css">
</head>
<?php
session_start();
include_once(__DIR__ . '/../connection.php');
$error_messages = []; // Create an error variable to store errors.
$has_errors = false;

if (isset($_POST['userMail'], $_POST['userPass'])) { // Check that the server recived a post signal.
    if (empty($_POST['userPass'])) { // Check that both variables are not empty
        $has_errors = true;
        array_push($error_messages, '<b>ERROR:</b> No se ha proporcionado el campo de contraseña.');
    }
    if (empty($_POST['userMail'])) {
        $has_errors = true;
        array_push($error_messages, '<b>ERROR:</b> No se ha proporcionado el campo de correo electrónico.');
    } else {
        $email = filter_var($_POST['userMail'], FILTER_SANITIZE_EMAIL); // Sanitize email input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the variable contains an email.
            $has_errors = true;
            array_push($error_messages, '<b>ERROR:</b> El email no es valido. Porfavor introduce una dirección de correo valida, como user@gmail.com.');
        }
    }
    if (!$has_errors) {
        $password = hash('sha256', filter_var($_POST['userPass'], FILTER_SANITIZE_STRING)); // Sanitize string anmd encrypt in SHA256.
        $query = $bd->prepare("SELECT * FROM Users WHERE email = ? AND `password` = ?"); // Prepare the query.
        $query->bindParam(1, $email); // Bind parameters.
        $query->bindParam(2, $password);
        $query->execute(); // Execute the query
        if ($query->rowCount() > 0) { // Chef if the query returned something.
            $row = $query->fetch();
            $msg = 'Inicio de sesión correcto, redireccionando...';
            $_SESSION['user'] = [$row['user_id'], $row['name'], $row['email']];
            header("refresh:1;url=home.php");
        } else { // If no user was found with that credentials.
            $has_errors = true;
            array_push($error_messages, "<b>ERROR:</b> El usuario o la contraseña no existe.");
        }
    }
    unset($_POST['userMail'], $_POST['userPass']);
}
?>

<body>

    <div class="content">
        <div class="centered-form">
            <img src="../images/logo_small.png" alt="Trivide logo">
            <h1>INICIAR SESIÓN</h1>
            <?php
            if ($has_errors) { // If user had errors during log in
                echo '<div class=\'message error-message\'>';
                foreach ($error_messages as $key => $error) {
                    echo $error . "</br>";
                }
                echo '</div>';
            } else if (isset($_SESSION["user"])) { // If user is logged in
                echo '<div class=\'message success-message\'>';
                echo '<p>Inicio de sesión correcto, redirigiendo a la página principal...</p>';
                echo '</div>';
                header("refresh:1;url=home.php");
            } else {
                echo '<div class=\'message\'>';
                echo '<p></p>';
                echo '</div>';
            }
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="userMail">Correo electrónico</label>
                    <input type="email" name="userMail" id="userMail" placeholder="user@mail.com">
                </div>
                <div class="form-group">
                    <label for="userPass">Contraseña</label>
                    <input type="password" name="userPass" id="userPass">
                </div>
                <div class="form-group">
                    <button class="button-primary" type="submit" accesskey="i"><u>I</u>niciar sesión</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once 'footer.php' ?>
</body>

</html>