<?php
  session_start();
  if(isset($_SESSION['member_id'])) {
    header('location: index.php');
    exit;
  }
  require_once 'db/connect.php';

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $emailSql = "SELECT member_id, member_email, member_password
                  FROM table_member
                  WHERE member_email = :email";
    $bind = $conn->prepare($emailSql);
    $bind->bindValue(':email', $email, PDO::PARAM_STR);
    $bind->execute();
    $emailResult = $bind->fetch();

    if (!$emailResult) {
      echo "<script>alert('ชื่อผู้ใช้ไม่ถูกต้อง')</script>";
      echo "<script>window.history.back()</script>";
      exit;
    }

    $hashPassword = $emailResult['member_password'];
    if (!password_verify($password, $hashPassword)) {
      echo "<script>alert('รหัสผ่านไม่ถูกต้อง')</script>";
      echo "<script>window.history.back()</script>";
      exit;
    }

    $_SESSION['member_id'] = $emailResult['member_id'];
    $_SESSION['member_email'] = $emailResult['member_email'];
    header("location: index.php");
  }
?>
<style>
html, body {
  height: 100%;
}
body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}
.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-floating:focus-within {
  z-index:2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
<?php require_once "template/header.php"; ?>
  <div class="container text-center">
    <div class="form-signin bg-white p-4">
      <form action="" method="post">
        <h1 class="h3 mb-3 fw-normal">
          Login page.
        </h1>

        <div class="form-floating">
          <input class="form-control" type="email" name="email" id="email" placeholder="email" />
          <label for="email">Email</label>
        </div>

        <div class="form-floating">
          <input class="form-control" type="password" name="password" id="password" placeholder="password" />
          <label for="password">Password</label>
        </div>

        <input class="w-100 btn btn-lg btn-primary" type="submit" value="Login" />
      </form>
    </div>
  </div>
<?php require_once "template/footer.php"; ?>