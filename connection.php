<?php
try {
    $bd = new PDO('mysql:host=localhost;dbname=trip-count', "trip-count", "");
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
