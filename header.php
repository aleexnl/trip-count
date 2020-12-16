<header>
	<img src="logo_small.png">
	<?php
	if (isset($_SESSION['user'])) { echo "<p class='hello'> Hola, <span class='welcome'>".$_SESSION['user']."</span></p>"; } 
	?>
</header>
