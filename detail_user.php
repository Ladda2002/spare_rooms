<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

$currentUser = getCurrentUser($_GET["id"]);
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>ข้อมูลผู้ใช้งาน</h1>
          <p>รายละเอียดผู้ใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ชื่อ-นามสกุล</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentUser["username"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ที่อยู่</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentUser["address"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>อีเมล</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentUser["email"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>โทรศัพท์</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentUser["phone"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>เพศ</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $gender_map[$currentUser["gender"]];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>สถานะ</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $status_map[$currentUser["status"]];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>สิทธิ์การใช้งาน</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $role_map[$currentUser["role"]];?></label>
                  </div>
                </div>
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
        </div>
      </div>
    </div>

  </main>
  <?php
  require_once("footer.php");
  ?>

</body>

</html>