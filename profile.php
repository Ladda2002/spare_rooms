<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

$currentUser = getCurrentUser($_SESSION["id"]);
if(isset($_POST["submit"])){
  editProfile($_POST["id"],$_POST["username"],$_POST["password"],$_POST["address"],$_POST["email"],$_POST["phone"],$_POST["gender"],$_POST["status"],$_FILES["profile_img"]["name"]);
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
          <h1>ข้อมูลส่วนตัว</h1>
          <p>ข้อมูลผู้ใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="id" value="<?php echo $currentUser["id"];?>">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ชื่อ-นามสกุล</label>
                      <input type="text" class="form-control" id="username" name="username" value="<?php echo $currentUser["username"];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รหัสผ่าน</label>
                      <input type="password" class="form-control" id="password" name="password" value="<?php echo $currentUser["password"];?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>ที่อยู่</label>
                      <textarea class="form-control" rows="3" name="address"><?php echo $currentUser["address"];?></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>อีเมล</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?php echo $currentUser["email"];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>โทรศัพท์</label>
                      <input type="password" class="form-control" id="phone" name="phone" value="<?php echo $currentUser["phone"];?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>เพศ &nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="radio" name="gender" id="gender" value="1" <?php if($currentUser["gender"]==1 || $currentUser["gender"]==""){ ?>checked<?php } ?>> ชาย &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="radio" name="gender" id="gender" value="2" <?php if($currentUser["gender"]==2 ){ ?>checked<?php } ?>> หญิง
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>สถานะ &nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="radio" name="status" id="status" value="1" <?php if($currentUser["status"]==1 || $currentUser["status"]==""){ ?>checked<?php } ?>> โสด &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="radio" name="status" id="status" value="2" <?php if($currentUser["status"]==2 ){ ?>checked<?php } ?>> มีแฟน
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>รูปประจำตัว</label>
                      <input type="file" class="form-control" name="profile_img" id="imgInp" >
                    </div>
                  </div>
                </div>
                <div align="right">
                  <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <?php if($currentUser["profile_img"] == ""){ ?>
                    <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                  <?php }else{ ?>
                    <img class="img-responsive" alt="" src="images/users/<?php echo $currentUser["profile_img"];?>" id="blah">
                  <?php } ?>
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
  <script type="text/javascript">
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#imgInp").change(function() {
      readURL(this);
    });
  </script>
</body>

</html>