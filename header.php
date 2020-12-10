<header>
	<form action="#">
		<button>Trip Count</button>
	</form>
	<?php
	if (isset($_SESSION['user'])) { echo "<p>".$_SESSION['user']."</p>"; } 
	?>
</header>
