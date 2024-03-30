<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=jesa', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    header("location:erreur.html");
    die();
}
?>