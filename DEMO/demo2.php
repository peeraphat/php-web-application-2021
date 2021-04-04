<?php 
// Method Request
// - GET 
// localhost/my-app/demo2.php?pageCount=3&limit=20&currentPage=2
// - POST
// ตัวแปรจะถูกส่งด้วย body ของ protocal HTTP

// Ex GET method.
  // $name = $_GET['name'];
  // $lastName = $_GET['lastName'];

  // echo "my name is $name and my last name is $lastName";

// Ex POST method.
  // $name = $_POST['firstName'];
  // $lastName = $_POST['lastName'];

  // echo "my name is $name and my lastname is $lastName";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="profile.php" method="post" >
    <input type="text" name="firstName" id="firstName" />
    <input type="text" name="lastName" id="lastName" />
    <input type="submit" value="Send" />
  </form>
</body>
</html>