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

/*
include 'connection.php';


$querytravels = $bd->prepare("SELECT * FROM Travels t where t.trip_id in (select g.trip_id from `Groups` g where g.user_id= :user_id );");

$querytravels->bindParam(':user_id', $_SESSION['$user_id']);

$querytravels->execute();

echo "<main>";

echo "<table>";
echo "<tr>";
echo "<td colspan='4'>Ordenar ⏏</td>";
echo "</tr>";

foreach ($querytravels as $rowtravel) {
	echo "<tr>";
	echo "<td>".$rowtravel['destiny']."</td>";
	echo "<td>".$rowtravel['coin']."</td>";
	echo "<td>".$rowtravel['departure_date']."</td>";
	echo "<td>".$rowtravel['return_date']."</td>";
	echo "</tr>";
}
echo "</table>";

echo "<button id='add_travel'>Añadir Viaje</button>";

echo "</main>";

*/

echo "<main>";

echo "<table>";
echo "<tr>";
echo "<th colspan='2'>Ordenar por:</th><th>Fecha de Creacion ⏏</th><th>Fecha de Modificacion ⏏</th>";
echo "</tr>";

for ($i = 0; $i<10; $i++) {
	echo "<tr>";
	echo "<td> Titulo".$i."</td>";
	echo "<td> Nombre".$i."</td>";
	echo "<td> Fecha".$i."</td>";
	echo "<td> Pepe".$i."</td>";
	echo "</tr>";
}
echo "</table>";

echo "<button id='add_travel'>Añadir Viaje</button>";

echo "</main>";

include_once 'footer.php';

?>


</body>
</html>