<?php
$host = 'db'; // Let op: dit is NIET 'localhost'!
$db   = 'school_db';
$user = 'root';
$pass = 'geheim123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1 style='color: green;'>🎉 Connectie succesvol! Docker netwerkkopie werkt!</h1>";
} catch (PDOException $e) {
    echo "<h1 style='color: red;'>❌ Connectie mislukt:</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
