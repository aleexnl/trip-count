<header>
	<a href="./home.php"><img src="images/logo_small.png"></a>
	<div class="menu">
		<a class="link" href="./home.php">
			<p><i class="fas fa-home"></i> Home</p>
		</a>
		<!-- <a class="link" href="#login">
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
		</a> -->
	</div>
	<p class='hello'> Hola, <span class='welcome'><?= isset($_SESSION['user'][0]) ? $_SESSION['user'][1] : header("location:login.php"); ?></span></p>
</header>
