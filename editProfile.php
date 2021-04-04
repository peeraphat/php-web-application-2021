<?php
  session_start();
  require_once "db/connect.php";
  $memberId = $_SESSION['member_id'];

  if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == "editMember") {
      $password = $_POST['password'];
      $confirmPassword = $_POST['confirmPassword'];
      $firstName = $_POST['name'];
      $lastName = $_POST['lastName'];
      $gender = $_POST['gender'];
      $file = $_FILES['file'];

      if ($password != $confirmPassword) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน')</script>";
        echo "<script>window.history.back()</script>";
        exit;
      }

      $extensionArr = array("jpg", "jpeg", "png");
      $targetFile = basename($file["name"]);
      $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
      $isExtValid = in_array($imageFileType, $extensionArr);
      
      if (!$isExtValid) {
        echo "<script>alert('ไฟล์รูปภาพไม่ถูกต้อง')</script>";
        echo "<script>window.history.back()</script>";
        exit;
      }
      
      $imageSize = getimagesize($file['tmp_name']);
      $imageWidth = $imageSize[0];
      $imageHeight = $imageSize[1];

      if ($imageWidth > 500 || $imageHeight > 500) {
        echo "<script>alert('รูปภาพใหญ่เกิน 500 px')</script>";
        echo "<script>window.history.back()</script>";
        exit;
      }
      
      $connent = file_get_contents($file['tmp_name']);
      $imageBase64 = base64_encode($connent);
      $image = "data:image/$imageFileType;base64,$imageBase64";
      
      $hashPassword = password_hash($password, PASSWORD_DEFAULT);
      
      $sqlEditMember = "UPDATE table_member SET
                        member_password = '$hashPassword',
                        member_firstName = '$firstName',
                        member_lastName = '$lastName',
                        member_gender = '$gender',
                        member_image = '$image'
                        WHERE member_id = '$memberId'";
      $resultEditMember = $conn->exec($sqlEditMember);
      if ($resultEditMember) {
        echo "<script>alert('แก้ไขเรียบร้อย')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  }

  $maleCheck = "";
  $femaleCheck = "";
  $sqlMember = "SELECT * FROM table_member WHERE member_id = '$memberId'";
  $queryMember = $conn->query($sqlMember);
  $member = $queryMember->fetch();

  if ($member['member_gender'] == 'm') {
    $maleCheck = "checked";
  } else {
    $femaleCheck = "checked";
  }
?>
<?php require_once "template/header.php"; ?>
<div>
  <h1>This is edit page.</h1>
  <form action="?action=editMember" method="post" enctype="multipart/form-data">
    Email: <input type="email" name="email" value="<?php echo $member['member_email'] ?>" disabled/>
    <br />
    Password: <input type="password" name="password" value=""/>
    <br />
    Confirm Password: <input type="password" name="confirmPassword" value=""/>
    <br />
    Name: <input type="text" name="name" value="<?php echo $member['member_firstName']; ?>"/>
    <br />
    Last name: <input type="text" name="lastName" value="<?php echo $member['member_lastName']; ?>"/>
    <br />
    Male: <input type="radio" name="gender" value="m" <?php echo $maleCheck; ?>/>
    <br />
    Female: <input type="radio" name="gender" value="f" <?php echo $femaleCheck; ?> />
    <br />
    Image: <input type="file" name="file" />
    <br />
    <input type="submit" />
  </form>
</div>
<?php require_once "template/footer.php"; ?>