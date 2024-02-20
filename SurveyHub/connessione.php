<?php
$user = 'root';
$password = '';
$db = 'surveyhub';
$host = 'localhost';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link,
   $host,
   $user,
   $password,
   $db,
   $port
);

if (!$success) {
    die("Errore di connessione al database: " . mysqli_connect_error());
} else {
    // echo "Connessione al database stabilita con successo!";
}


?>