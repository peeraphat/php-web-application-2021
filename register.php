<?php
  require_once 'db/connect.php';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $file = $_FILES['file'];

    $sqlCount = "SELECT member_id FROM table_member WHERE member_email = '$email'";
    $queryCount = $conn->query($sqlCount);
    $resultCount = $queryCount->fetch();
    if ($resultCount) {
      echo "<script>alert('ชื่อผู้ใช้ถูกใช้แล้ว')</script>";
      echo "<script>window.history.back();</script>";
      exit;
    }
    
    if ($password != $confirmPassword) {
      echo "<script>alert('รหัสผ่านไม่ตรงกัน')</script>";
      echo "<script>window.history.back();</script>";
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
      echo "<script>alert('กรุณาอัพโหลดขนาดความกว้่างไม่เกิน 500 px.')</script>";
      echo "<script>window.history.back()</script>";
      exit;
    }

    $conntent = file_get_contents($file['tmp_name']);
    $imageBase64 = base64_encode($conntent);
    $image = "data:image/$imageFileType;base64,$imageBase64";

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertSql = "INSERT INTO table_member
                  (member_email, member_password, member_firstName, member_lastName, member_gender, member_image)
                  VALUES
                  ('$email', '$hashPassword', '$name', '$lastName', '$gender', '$image')";
    $resultInsert = $conn->exec($insertSql);
    
    if ($resultInsert) {
      echo "<script>alert('ลงทะเบียนสำเร็จ')</script>";
    }
  }

?>
<?php require_once "template/header.php"; ?>
<div class="container">
  <main class="bg-white p-4 mt-3">
    <div class="py-5 text-center">
      <h2>Register Page.</h2>
    </div>

    <div style="width: 600px; height: 600px; margin: auto;">
      <form action="" method="post" enctype="multipart/form-data">
        <div class="row g-3">

          <div class="col-12">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" id="email" required/>
          </div>

          <div class="col-12">
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password" id="password" required/>
          </div>

          <div class="col-12">
            <label for="confirmPassword">Confirm Password</label>
            <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" required/>
          </div>

          <div class="col-12">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" required/>
          </div>

          <div class="col-12">
            <label for="lastName">Last name</label>
            <input class="form-control" type="text" name="lastName" id="lastName" required/>
          </div>

          <div class="mt-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="male" value="m" checked/>
              <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="feMale" value="f" checked/>
              <label class="form-check-label" for="male">Female</label>
            </div>
          </div>
          <div class="mb-3 mt-3">
            <label for="file" class="form-label">Upload Profile</label>
            <input class="form-control" type="file" name="file" id="file" required/>
          </div>

          <hr class="my-4">
          
          <input class="w-100 btn btn-primary btn-lg" type="submit" value="Register" />
        </div>
      </form>
    </div>
  </main>
</div>
<?php require_once "template/footer.php"; ?>