<?php 
  session_start();
  // เป็นการเรียกใช้ session แล้วก็นำไปกำหนดให้ตัวแปรใหม่

  $name = $_SESSION['firstName'];
  $lastName = $_SESSION['lastName'];

  echo "my name $name , my lastname $lastName";
?>