<header>
	<a href="./home.php"><img src="images/logo_small.png"></a>
	<p class='hello'> Hola, <span class='welcome'><?= isset($_SESSION['user'][0]) ? $_SESSION['user'][1] : header("location:login.php"); ?></span></p>
</header>
