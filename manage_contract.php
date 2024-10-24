<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ดึงข้อมูลห้องเช่าทั้งหมดที่ผู้ใช้มี โดยใช้ฟังก์ชัน getAllRentalRoom 
$allRentalRoom = getAllRentalRoom($_SESSION["id"]);

// ตรวจสอบว่ามีการส่งค่า delete มาหรือไม่ ถ้ามีให้เรียกฟังก์ชัน deleteContract เพื่อลบข้อมูลห้องเช่า
if (isset($_GET['delete'])) {
  deleteContract($_GET['delete']);
}

// ตรวจสอบว่ามีการส่งค่า open มาหรือไม่ ถ้ามีให้เรียกฟังก์ชัน openContract เพื่อเปิดสัญญาหรือห้องเช่า
if (isset($_GET['open'])) {
  openContract($_GET['open']);
}

// ตรวจสอบว่ามีการส่งค่า close มาหรือไม่ ถ้ามีให้เรียกฟังก์ชัน closeContract เพื่อปิดสัญญาหรือห้องเช่า
if (isset($_GET['close'])) {
  closeContract($_GET['close']);
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
          <a href="edit_contract.php" class="btn btn-success btn-lg">เพิ่มห้อง</a>
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
                  <td><?php echo $contract_status_map[$data["room_status"]];?></td>
                  <td style="text-align: right;">
                    <?php if($data["room_status"] == 2){ ?>
                      <a href="?open=<?php echo $data['id'];?>" class="btn btn-success btn-lg" onClick="javascript: return confirm('ยืนยันการเปลี่ยนสถานะห้อง');">ปกติ</a>
                    <?php }else{ ?>
                      <a href="?close=<?php echo $data['id'];?>" class="btn btn-danger btn-lg" onClick="javascript: return confirm('ยืนยันการเปลี่ยนสถานะห้อง');">ระงับ</a>
                    <?php } ?>
                    <a href="edit_contract.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">แก้ไข</a>
                    <a href="?delete=<?php echo $data['id'];?>" class="btn btn-danger btn-lg" onClick="javascript: return confirm('ยืนยันการลบ');">ลบ</a>
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