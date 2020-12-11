<?php
try {
    $db = $_SERVER['MYSQL_TRIP_COUNT_DBNAME'];
    $user = $_SERVER['MYSQL_TRIP_COUNT_USER'];
    $pass = $_SERVER['MYSQL_TRIP_COUNT_PASS'];
    $bd = new PDO("mysql:host=localhost;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
