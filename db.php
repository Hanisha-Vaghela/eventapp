<?php
// inc/db.php
$host = 'localhost:3308';
$db   = 'eventapp';
$user = 'root';
$pass = ''; // set your DB password
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES     => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // <-- Connection variable is $pdo
} catch (\PDOException $e) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}
session_start();

// helper: require login
function ensure_logged_in(){
    if(!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>