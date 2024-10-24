<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

// ดึงข้อมูลการจองปัจจุบันตาม ID ที่ส่งมาใน URL
$currentBooking = getCurrentBooking($_GET["id"]);

// ดึงข้อมูลห้องที่เกี่ยวข้องกับการจองนั้น
$currentRoom = getCurrentRoom($currentBooking["rooms_id"]);

// ดึงข้อมูลแกลเลอรีของห้องที่เกี่ยวข้องกับการจอง
$allRoomGallery = getAllRoomGallery($currentBooking["rooms_id"]);

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่ 
if(isset($_POST["submit"])){
  // อัปเดตสถานะการจองโดยเรียกใช้ฟังก์ชัน updateBookingStatus
  updateBookingStatus($_POST["booking_id"],$_POST["booking_status"]);
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
        <legend>ข้อมูลผู้จอง</legend>
        <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
          <input type="hidden" class="form-control" id="booking_id" name="booking_id" value="<?php echo $_GET["id"];?>">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentBooking["booking_name"];?></label>
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
                <label><?php echo $currentBooking["booking_phone"];?></label>
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
                <label><?php echo $currentBooking["booking_email"];?></label>
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
                <label><?php echo $booking_map[$currentBooking["booking_status"]];?></label>
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
                <select name="booking_status" class="form-control" required>
                  <option value="">-- โปรดระบุสถานะ --</option>
                  <option value="0" <?php if($currentBooking['booking_status'] == 0){ ?> selected<?php } ?>>ยกเลิก</option>
                  <option value="2" <?php if($currentBooking['booking_status'] == 2){ ?> selected<?php } ?>>ยืนยัน</option>
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