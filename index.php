<?php
  session_start();
  require_once 'db/connect.php';

  if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == "postStatus") {
      $status = $_POST['status'];
      $member_id = $_SESSION['member_id'];
      $sqlInsert = "INSERT INTO table_status 
                    (status_content, status_member_id) VALUES 
                    ('$status', '$member_id')";
      $resultInsert = $conn->exec($sqlInsert);
      
      if ($resultInsert) {
        echo "<script>alert('โพสสำเร็จ')</script>";
        echo "<script>window.location.href='index.php'</script>";
      }
    }
  
    if ($action == "comment") {
      $comment = $_POST['comment'];
      $statusId = $_GET['statusId'];
      $memberId = $_SESSION['member_id'];
      $sqlInsertComment = "INSERT INTO table_comment 
                          (comment_content, comment_status_id, comment_member_id) VALUES 
                          ('$comment', '$statusId', '$memberId')";
      $resultInsertComment = $conn->exec($sqlInsertComment);
  
      if ($resultInsertComment) {
        echo "<script>alert('คอมเม้นสำเร็จจ้า')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  
    if ($action == 'deleteStatus') {
      $statusId = $_GET['statusId'];
      $sqlDeleteStatus = "DELETE FROM table_status WHERE status_id = '$statusId'";
      $resultDeleteStatus = $conn->exec($sqlDeleteStatus);
  
      $sqlDeleteComments = "DELETE FROM table_comment WHERE comment_status_id = '$statusId'";
      $resultDeleteComments = $conn->exec($sqlDeleteComments);
  
      if ($resultDeleteStatus && $resultDeleteComments) {
        echo "<script>alert('ลบสเตตัสสำเร็จ')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  
    if ($action == 'editStatus') {
      $statusContent = $_POST['editStatusContent'];
      $statusId = $_GET['statusId'];
  
      $sqlUpdateStatus = "UPDATE table_status 
                          SET status_content = '$statusContent'
                          WHERE status_id = '$statusId'";
      $resultUpdateStatus = $conn->exec($sqlUpdateStatus);
  
      if ($resultUpdateStatus) {
        echo "<script>alert('แก้ไขสเตตัสเรียบร้อยแล้วจ้า')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  
    if ($action == 'editComment') {
      $commentId = $_GET['commentId'];
      $commentContent = $_POST['editCommentContent'];
  
      $sqlUpdateComment = "UPDATE table_comment
                            SET comment_content = '$commentContent'
                            WHERE comment_id = '$commentId'";
      $resultUpdateComment = $conn->exec($sqlUpdateComment);
  
      if ($resultUpdateComment) {
        echo "<script>alert('แก้ไขคอมเม้นสำเร็จ')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  
    if ($action == 'deleteComment') {
      $commentId = $_GET['commentId'];
      $sqlDeleteComment = "DELETE FROM table_comment WHERE comment_id = '$commentId'";
      $resultDeleteComment = $conn->exec($sqlDeleteComment);
  
      if ($resultDeleteComment) {
        echo "<script>alert('ลบคอมเม้นสำเร็จ')</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit;
      }
    }
  }
  
  // QUERY ALL STATUS
  $sqlStatus = "SELECT * FROM table_status 
                INNER JOIN table_member
                ON table_status.status_member_id=table_member.member_id
                ORDER BY status_id DESC";
  $queryStatus = $conn->query($sqlStatus);
  $resultsStatus = $queryStatus->fetchAll(PDO::FETCH_ASSOC);

  $memberId = $_SESSION['member_id'];
  $sqlMember = "SELECT * FROM table_member WHERE member_id='$memberId'";
  $queryMember = $conn->query($sqlMember);
  $resultMember = $queryMember->fetch()
?>

<?php require_once "template/header.php"; ?>
<div class="container">
  <div class="row mt-3">
    <div class="col-3">
      <div>
        <img src="<?php echo $resultMember['member_image']; ?>" width="100%" />
      </div>
      <div>
        <ul>
          <li>
            <a href="profile.php">โปรไฟล์ของฉัน</a>
          </li>
          <li>
            <a href="editProfile.php">แก้ไขโปรไฟล์</a>
          </li>
          <li>
            <a href="logout.php">ออกจากระบบ</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-9">
      <!-- SECTION POST STATUS -->
      <div>
        <form action="?action=postStatus" method="post">
          <div class="form-floating">
            <input class="form-control" type="text" name="status" id="status" />
            <label for="status">คุณคิดอะไรอยู่ ?</label>
          </div>
          <div class="d-grid mt-1">
            <input class="btn btn-primary" type="submit" value="Post" />
          </div>
        </form>
      </div>
      <!-- SECTION FEED -->
      <div class="mt-3">
        <?php foreach($resultsStatus as $post): ?>
        <!-- DISPLAY STATUS -->
        <div style="padding: 20px;">
          <div class="card mb-4">
            <div class="card-body">
              <div class="card-title">
                <img src="<?php echo $post['member_image'] ?>" width="50" />
                <?php echo $post['status_content']; ?>
                [<?php echo $post['member_firstName']; ?>]
                <?php if(isset($_SESSION['member_id']) && $_SESSION['member_id'] == $post['status_member_id']): ?>
                <div>
                  <a href="" data-bs-toggle="modal" data-bs-target="#editStatus<?php echo $post['status_id']; ?>">แก้ไข</a>
                  <a href="?action=deleteStatus&statusId=<?php echo $post['status_id'] ?>">ลบ</a>
                </div>
                <?php endif; ?>
              </div>
              <div class="card-text">
                <?php
                  // QUERY COMMENT 
                  $sqlComment = "SELECT * FROM table_comment
                                  INNER JOIN table_member
                                  ON table_comment.comment_member_id=table_member.member_id
                                  WHERE comment_status_id = '$post[status_id]'";
                  $queryComment = $conn->query($sqlComment);
                  $resultsComment = $queryComment->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php foreach($resultsComment as $comment): ?>
                - <img src="<?php echo $comment['member_image']; ?>" width="20px" />
                <?php echo $comment['comment_content']; ?>
                [<?php echo $comment['member_firstName']; ?>]
                <?php if(isset($_SESSION['member_id']) && $_SESSION['member_id'] == $comment['comment_member_id']): ?>
                  <div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editComment<?php echo $comment['comment_id']; ?>">แก้ไข</a>
                    <a href="?action=deleteComment&commentId=<?php echo $comment['comment_id']; ?>">ลบ</a>
                  </div>
                <?php endif; ?>
                <br />
                <!-- MODAL EDIT COMMENT -->
                <div class="modal fade" id="editComment<?php echo $comment['comment_id']; ?>" tabindex="-1"
                  aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <form action="?action=editComment&commentId=<?php echo $comment['comment_id']; ?>" method="post">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">แก้ไขคอมเม้น</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="text" name="editCommentContent"
                            placeholder="<?php echo $comment['comment_content']; ?>" />
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <?php endforeach; ?>
                <?php if(isset($_SESSION['member_id'])) : ?>
                  <form action="?action=comment&statusId=<?php echo $post['status_id']; ?>" method="post">
                    <input type="text" name="comment" id="comment" placeholder="comment" /> <br />
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <br />
        <!-- MODAL EDIT STATUS -->
        <div class="modal fade" id="editStatus<?php echo $post['status_id']; ?>" tabindex="-1"
          aria-labelledby="exampleModalLabel" aria-hidden="true">
          <form action="?action=editStatus&statusId=<?php echo $post['status_id']; ?>" method="post">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">แก้ไขสเตตัส</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="text" name="editStatusContent" placeholder="<?php echo $post['status_content']; ?>" />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php require_once "template/footer.php"; ?>