<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ดึงข้อมูลห้องเช่าทั้งหมดสำหรับผู้ใช้ที่ล็อกอินอยู่
$allRentalRoom = getAllRentalRoom($_SESSION["id"]);

// ตรวจสอบว่ามีการส่งค่าจาก URL เพื่อทำการลบห้องเช่าหรือไม่
if (isset($_GET['delete'])) {
  // เรียกฟังก์ชันเพื่อลบการเช่าห้องตาม ID ที่ส่งมา
  deleteRoomRental($_GET['delete'],$_GET['apartments_id']);
}

// ตรวจสอบว่ามีการส่งค่าจาก URL เพื่อเปิดห้องเช่าหรือไม่
if (isset($_GET['open'])) {
  // เรียกฟังก์ชันเพื่อเปิดห้องเช่าตาม ID ที่ส่งมา
  openRoomRental($_GET['open']);
}

// ตรวจสอบว่ามีการส่งค่าจาก URL เพื่อปิดห้องเช่าหรือไม่
if (isset($_GET['close'])) {
  // เรียกฟังก์ชันเพื่อปิดห้องเช่าตาม ID ที่ส่งมา
  closeRoomRental($_GET['close']);
}
?>

<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">
    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ข้อมูลห้องพัก</h1>
        <div align="right">
          <a href="edit_user_room.php" class="btn btn-success btn-lg">เพิ่มห้อง</a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อหอพัก</td>
              <td>ชื่อห้อง</td>
              <td>ประเภทเตียง</td>
              <td>ประเภทห้อง</td>
              <td>ราคาห้อง (ต่อเดือน)</td>
              <td>ค่ามัดจำ</td>
              <td>สถานะ</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($allRentalRoom)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allRentalRoom as $data){ ?>
                <tr>
                  <td><?php echo $data["apartment"] . "  " . $data["apart_name"]; ?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo $bed_map[$data["bed_type"]];?></td>
                  <td><?php echo $room_map[$data["room_type"]];?></td>
                  <td><?php echo number_format($data["room_price"]);?></td>
                  <td><?php echo number_format($data["room_rent"]);?></td>
                  <td><?php echo $room_status_map[$data["room_status"]];?></td>
                  <td style="text-align: right;">
                    <?php if($data["room_status"] == 2){ ?>
                      <a href="?open=<?php echo $data['id'];?>&apartments_id=<?php echo $data['apartments_id'];?>" class="btn btn-success btn-lg" onClick="javascript: return confirm('ยืนยันการเปลี่ยนสถานะห้อง');">ว่าง</a>
                    <?php }else{ ?>
                      <a href="?close=<?php echo $data['id'];?>&apartments_id=<?php echo $data['apartments_id'];?>" class="btn btn-danger btn-lg" onClick="javascript: return confirm('ยืนยันการเปลี่ยนสถานะห้อง');">ไม่ว่าง</a>
                    <?php } ?>
                    <a href="edit_questionaire.php?id=<?php echo $data['id'];?>" class="btn btn-success btn-lg">แบบประเมิน</a>
                    <a href="edit_user_room.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">แก้ไข</a>
                    <a href="?delete=<?php echo $data['id'];?>&apartments_id=<?php echo $data['apartments_id'];?>" class="btn btn-danger btn-lg" onClick="javascript: return confirm('ยืนยันการลบ');">ลบ</a>
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>

      </div>
    </div>
  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>