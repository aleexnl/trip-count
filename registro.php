<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="registro.css">
</head>
<?php
session_start();
include_once('./connection.php');
$error_messages = []; // Create an error variable to store errors.
$has_errors = false;

if (isset($_POST['userMail'], $_POST['userPass'], $_POST['userName'], $_POST['userPass2'])) { // Check that the server recived a post signal.

	if (empty($_POST['userPass']) && empty($_POST['userPass2'])) { // Check that both variables are not empty
	    if (empty($_POST['userPass'])) { // Check if the first password is empty
        	$has_errors = true;
        	array_push($error_messages, '<b>ERROR:</b> No se ha proporcionado el campo de contraseña.');
    	}

    	if (empty($_POST['userPass2'])) { // Check if the second password is empty
        	$has_errors = true;
        	array_push($error_messages, '<b>ERROR:</b> No se ha confirmado la contraseña.');
    	}
	} else {
		if ($_POST['userPass'] != $_POST['userPass2']) { // Check if the two passwords are the same
			$has_errors = true;
			array_push($error_messages, '<b>ERROR:</b> Las contraseñas no coinciden.');
		}
	}

    if (empty($_POST['userName'])) { // Check if the username is empty
        $has_errors = true;
        array_push($error_messages, '<b>ERROR:</b> No se un ha proporcionado un nombre de usuario.');
    } else {
        if (preg_match("/^[A-z]+( ?[A-z]+)*$/", $_POST['userName']) == FALSE) { // Check if the username respects the rules mentioned below
        	$has_errors = true;
        	array_push($error_messages, '<b>ERROR:</b> El nombre de usuario solo puede contener numeros, letras y no puede contener espacios que no esten directamente seguidos de letras.');        
        }
    }

    if (empty($_POST['userMail'])) { // Check if the email is empty
        $has_errors = true;
        array_push($error_messages, '<b>ERROR:</b> No se ha proporcionado el campo de correo electrónico.');
    } else {
        $email = filter_var($_POST['userMail'], FILTER_SANITIZE_EMAIL); // Sanitize email input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the variable contains an email.
            $has_errors = true;
            array_push($error_messages, '<b>ERROR:</b> El email no es valido. Porfavor introduce una dirección de correo valida, como user@gmail.com.');
        }
    }

    if (!$has_errors) { // See if it doesn't have errors
    	$prequery = $bd -> prepare("SELECT * FROM Users WHERE email = ? ");
    	$prequery->bindParam(1, $email);
    	$prequery->execute();
        if ($prequery->rowCount() == 0) { // Chef if the query returned something.
        	$password = hash('sha256', filter_var($_POST['userPass'], FILTER_SANITIZE_STRING)); // Sanitize string anmd encrypt in SHA256.
        	$query = $bd -> prepare("INSERT INTO Users (name, password, email) VALUES (?, ?, ?)"); // Prepare the query.
        	$query->bindParam(1, $_POST['userName']); // Bind parameters.
        	$query->bindParam(3, $email);
        	$query->bindParam(2, $password);
        	$query->execute(); // Execute the query
            $msg = 'Nuevo usuario creado correctamente, redireccionando...';
            header( "refresh:1;url=login.php" );
        } else { // If there is a user registered with the same email.
            $has_errors = true;
            array_push($error_messages, "<b>ERROR:</b> Ya hay un usuario registrado con este nombre.");
        }
    }
    unset($_POST['userMail'], $_POST['userPass'], $_POST['userPass2']);
}
?>

<body>
    
    <div class="content">
        <div class="centered-form">
            <h1>FORMULARIO DE REGISTRO</h1>
            <?php
            if ($has_errors) { // If user had errors during log in
                echo '<div class=\'message error-message\'>';
                foreach ($error_messages as $key => $error) {
                    echo $error . "</br>";
                }
                echo '</div>';
            } else if  (isset($_SESSION["user"])){ // If user is logged in
                echo '<div class=\'message success-message\'>';
                echo '<p>Iniciando session, redirigiendo a la página principal...</p>';
                echo '</div>';
                header( "refresh:1;url=home.php" );
            } else {
                echo '<div class=\'message\'>';
                echo '<p></p>';
                echo '</div>';
            }
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="userMail">Correo electrónico</label>
                    <input type="email" name="userMail" id="userMail" placeholder="user@mail.com" >
                </div>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="userName" id="userName">
                </div>
                <div class="form-group">
                    <label for="userPass">Contraseña</label>
                    <input type="password" name="userPass" id="userPass">
                </div>
                <div class="form-group">
                    <label for="userPass2">Confirmar contraseña</label>
                    <input type="password" name="userPass2" id="userPass2">
                </div>
                <div class="form-group">
		    <button class="button-primary" type="submit" accesskey="r"><u>R</u>EGISTRAR</button>
                </div>
            </form>
        </div>
    </div>

<?php include_once 'footer.php' ?>
</body>

</html>
