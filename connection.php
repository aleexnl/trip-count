<?php
try {
    $bd = new PDO('mysql:host=localhost;dbname=trip_count', "trip_count", "");
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
