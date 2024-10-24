<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentUser = getCurrentUser($_SESSION["id"]);
$currentRoom = getCurrentRoom($_GET["id"]);
$allRoomGallery = getAllRoomGallery($_GET["id"]);

if(isset($_POST["submit"])){
  saveRequest($_POST["rooms_id"],$_POST["users_id"],$_POST["req_name"],$_POST["req_phone"],$_POST["req_email"]);
}
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">


    <div class="row">
      <div class="col-xs-12 col-md-6">
        <img class="img-responsive" alt="" src="images/room/<?php echo $currentRoom["room_image"];?>">
      </div>
      <div class="col-xs-12 col-md-6">
        <h1>ห้อง <?php echo $currentRoom["room_name"];?></h1>
        <h3>ข้อมูลห้องพัก </h3>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>เจ้าของห้อง</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["username"];?></label>
            </div>
          </div>
        </div>      
        <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>หอพัก</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label>
                  <?php echo $currentRoom["apartment"] . "  " . $currentRoom["apart_name"]; ?>
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ประเภทเตียง</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $bed_map[$currentRoom["bed_type"]];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ประเภทห้อง</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $room_map[$currentRoom["room_type"]];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ราคาห้อง (ต่อเดือน)</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo number_format($currentRoom["room_price"]);?> บาท</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ค่ามัดจำ</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo number_format($currentRoom["room_rent"]);?> บาท</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>รายละเอียดห้องพัก</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentRoom["room_detail"];?></label>
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
              <label><?php echo $gender_map[$currentRoom["gender"]];?></label>
            </div>
          </div>
        </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ความต้องการ</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentRoom["room_remark"];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>คะแนน</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentRoom["room_score"];?></label>
              </div>
            </div>
          </div>
          <legend>ข้อมูลผู้ส่งคำขอ</legend>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <input type="text" class="form-control" id="req_name" name="req_name" value="<?php echo $currentUser["username"];?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>หมายเลขโทรศัพท์</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <input type="text" class="form-control" id="req_phone" name="req_phone" value="<?php echo $currentUser["phone"];?>">
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
                <input type="text" class="form-control" id="req_email" name="req_email" value="<?php echo $currentUser["email"];?>">
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" id="rooms_id" name="rooms_id" value="<?php echo $_GET["id"];?>">
          <input type="hidden" class="form-control" id="users_id" name="users_id" value="<?php echo $_SESSION["id"];?>">
          <div align="center">
            <button type="submit" name="submit" class="btn btn-success btn-lg">ส่งคำขอ</button>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 section-container-spacer">
      </div>
      <?php if(empty($allRoomGallery)){ ?>
      <?php }else{?>
        <?php foreach($allRoomGallery as $dataGal){ ?>
          <div class="col-xs-12 col-md-4 section-container-spacer">
            <img class="img-responsive" alt="" src="images/room_gallery/<?php echo $dataGal["images"];?>">
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>