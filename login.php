<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
if(isset($_POST["submit"])){
  checkLogin($_POST["username"],$_POST["password"]);
}
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>เข้าสู่ระบบ</h1>
          <p>ยินดีต้อนรับ เข้าสู่ระบบเพื่อเริ่มการใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>ชื่อผู้ใช้งาน</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                </div>
                <div class="form-group">
                  <label>รหัสผ่าน</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div align="right">
                  <button type="submit" name="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                  <a href="register.php" class="btn btn-warning btn-lg">สมัครสมาชิก</a>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center" style="width: 400px; margin-left: 100px;">
                  <img class="img-responsive" alt="" src="images/circular_image.png" id="blah">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>