<?php session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="home.css">
	<title>Home</title>
</head>
<body>

<?php

include_once 'header.php';


if (!isset($_SESSION['order_creation']) && !isset($_SESSION['order_update'])) {
	$_SESSION['order_creation']= "asc";
	$_SESSION['order_update']= "asc";
	$buttoncreation= "▲";
	$buttonupdate= "▲";
}

if (isset($_POST['order_update'])){
	if ($_POST['order_update'] == "asc") {
		$_SESSION['order_update']= "desc";
		$buttonupdate = "▼";
	} elseif ($_POST['order_update'] == "desc") {
		$_SESSION['order_update']= "asc";
		$buttonupdate = "▲";		
	}
}

if (isset($_POST['order_creation'])){
	if ($_POST['order_creation'] == "asc") {
		$_SESSION['order_creation']= "desc";
		$buttoncreation ="▼" ;
	} elseif ($_POST['order_creation'] == "desc") {
		$_SESSION['order_creaton']= "asc";
		$buttoncreation="▲";		
	}
}


include 'connection.php';


$querytravels = $bd->prepare("SELECT * FROM Travels t where t.trip_id in (select g.trip_id from `Groups` g where g.user_id= :user_id ) order by 6 ".$_SESSION['order_creation'].", 7 ".$_SESSION['order_update']." ;");

$querytravels->bindParam(':user_id', $_SESSION['$user_id']);

$querytravels->execute();

echo "<main>";

echo "<table>";
echo "<tr>";
echo "<form action='home.php' method='post'><th colspan='4'>Ordenar por:</th><th><button type='submit' name='order_creation' class='order_creation'>Fecha de Creacion ".$buttoncreation." </button></th><th><button type='submit' name='order_update' class='order_update'>Fecha de Modificacion ".$buttonupdate."</button></th><form>";
echo "</tr>";


foreach ($querytravels as $rowtravel) {
	echo "<tr>";
	echo "<td>".$rowtravel['destiny']."</td>";
	echo "<td>".$rowtravel['coin']."</td>";
	echo "<td>".$rowtravel['departure_date']."</td>";
	echo "<td>".$rowtravel['return_date']."</td>";
	echo "<td>".$rowtravel['creation_date']."</td>";
	echo "<td>".$rowtravel['modify_date']."</td>";
	echo "</tr>";
}

echo "</table>";

echo "<button id='add_travel'>Añadir Viaje</button>";

echo "</main>";

include_once 'footer.php';

unset($querytravels);
unset($bd);

?>


</body>
</html>