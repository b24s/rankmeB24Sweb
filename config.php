<?php

// Server Nr 1
$title = '[Free VIP] Best24Source => Statistics'; // Header Title
$servername = '[Free VIP] Best24Source =>';       // Server Name
$serverip = '127.0.0.0:20392';                    // Server Ip 127.0.0.0:20392
$servericon = 'img/pngegg.png';                   // Image Server 1 Logo

$host = '127.0.0.0';                              // Host IP 127.0.0.0
$benutzer = 'Username';                           // Username
$passwort = 'Passwort';                           // Passwort
$datenbank = 'Dbname';                            // Dbname

try {
  $connection = new PDO("mysql:host=$host;dbname=$datenbank", $benutzer, $passwort);
  // set the PDO error mode to exception
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
}

// If you only run one server delete everything below! Except ? >
// Server Nr 2
$title2 = 'BEST24SOURCE => |RANK|FASTDL|B24S.EU => Statistics'; // Header Title
$servername2 = 'BEST24SOURCE => |RANK|FASTDL|B24S.EU =>';       // Server Name 2
$serverip2 = '127.0.0.0:20392';                                 // Server Ip 127.0.0.0:20392
$servericon2 = 'img/icongo.png';                                // Image Server 2 Logo

$host2 = '127.0.0.0';                                           // Host IP 127.0.0.0
$benutzer2 = 'Username';                                        // Username
$passwort2 = 'Passwort';                                        // Passwort
$datenbank2 = 'Dbname';                                         // Dbname

try {
  $connection2 = new PDO("mysql:host=$host2;dbname=$datenbank2", $benutzer2, $passwort2);
  // set the PDO error mode to exception
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
}

?>
