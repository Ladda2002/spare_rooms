<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ดึงข้อมูลห้องทั้งหมดในอพาร์ตเมนต์ 
$allRoomInApartment = getAllRoomInApartmentt($_GET["apartments_id"],$_SESSION["id"]);

// ตรวจสอบว่ามีการส่งคำขอเพื่อลบห้องมาหรือไม่
if (isset($_GET['delete'])) {
  // ถ้ามีการส่งค่าให้ลบห้อง จะเรียกใช้ฟังก์ชัน deleteRoom 
  deleteRoom($_GET['delete'],$_GET['apartments_id']);
}

// ตรวจสอบว่ามีการส่งคำขอเพื่อเปิดห้องมาหรือไม่
if (isset($_GET['open'])) {
  // ถ้ามีการส่งค่าให้เปิดห้อง จะเรียกใช้ฟังก์ชัน openRoom 
  openRoom($_GET['open'],$_GET['apartments_id']);
}

// ตรวจสอบว่ามีการส่งคำขอเพื่อปิดห้องมาหรือไม่
if (isset($_GET['close'])) {
  // ถ้ามีการส่งค่าให้ปิดห้อง จะเรียกใช้ฟังก์ชัน closeRoom 
  closeRoom($_GET['close'],$_GET['apartments_id']);
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
          <a href="edit_room.php?apartments_id=<?php echo $_GET['apartments_id']?>" class="btn btn-success btn-lg">เพิ่มห้อง</a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อหอ</td>
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
            <?php if(empty($allRoomInApartment)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allRoomInApartment as $data){ ?>
                <tr>
                  <td><?php echo $data["apart_name"];?></td>
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
                    <a href="edit_room.php?id=<?php echo $data['id'];?>&apartments_id=<?php echo $_GET['apartments_id']?>" class="btn btn-warning btn-lg">แก้ไข</a>
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