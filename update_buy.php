<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

// รับข้อมูลการซื้อปัจจุบันจาก ID ที่ส่งมาใน URL
$currentBuy = getCurrentBuy($_GET["id"]);

// รับข้อมูลผู้ใช้จากรหัสผู้ใช้ที่อยู่ในข้อมูลการซื้อ
$currentUser = getCurrentUser($currentBuy["rusers_id"]);

// รับข้อมูลห้องจากรหัสห้องที่อยู่ในข้อมูลการซื้อ
$currentRoom = getCurrentRoom($currentBuy["rooms_id"]);

// รับข้อมูลแกลลอรีของห้องที่อยู่ในข้อมูลการซื้อ
$allRoomGallery = getAllRoomGallery($currentBuy["rooms_id"]);

// ตรวจสอบว่ามีการส่งฟอร์มที่มีชื่อ submit หรือไม่
if (isset($_POST["submit"])) {
    // อัปเดตสถานะการซื้อโดยเรียกใช้ฟังก์ชัน updateBuyStatus
    updateBuyStatus($_POST["buy_id"], $_POST["buy_status"]);
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
              <label>ระยะเวลา</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["contract_year"];?> ปี</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>เจ้าของ</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentUser["username"];?></label>
            </div>
          </div>
        </div>
        <legend>ข้อมูลผู้จอง</legend>
        <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
          <input type="hidden" class="form-control" id="buy_id" name="buy_id" value="<?php echo $_GET["id"];?>">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentBuy["buy_name"];?></label>
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
                <label><?php echo $currentBuy["buy_phone"];?></label>
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
                <label><?php echo $currentBuy["buy_email"];?></label>
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
                <label><?php echo $buy_map[$currentBuy["buy_status"]];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>อัพเดทสถานะ</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <select name="buy_status" class="form-control" required>
                  <option value="">-- โปรดระบุสถานะ --</option>
                  <option value="0" <?php if($currentBuy['buy_status'] == 0){ ?> selected<?php } ?>>ยกเลิก</option>
                  <option value="2" <?php if($currentBuy['buy_status'] == 2){ ?> selected<?php } ?>>ยืนยัน</option>
                </select>
              </div>
            </div>
          </div>
          <div align="center">
            <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
          </div>
        </form>
        
      </div>
    </div>

  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>