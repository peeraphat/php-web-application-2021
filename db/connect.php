<?php
  $host = 'localhost';
  $dbUser = 'root';
  $dbPassword = '';
  $dbName = 'social';

  try {
    // logic scope
    // connect database .
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
  } catch (PDOException $e) {
    // handle error scope
    echo "Connection failed: " . $e->getMessage();
  }

?>