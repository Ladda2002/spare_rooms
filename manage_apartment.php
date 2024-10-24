<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ดึงข้อมูลผู้ใช้ทั้งหมดของอพาร์ตเมนต์  พร้อมส่ง id ของผู้ใช้ปัจจุบันผ่าน session
$allApartmentUser = getAllApartmentUser($_SESSION["id"]);

// ตรวจสอบว่ามีการส่งค่าลบผู้ใช้มาหรือไม่
if (isset($_GET['delete'])) {
  // หากมีการส่งค่ามาให้ลบผู้ใช้ จะเรียกใช้ฟังก์ชัน deleteUser โดยส่งค่า id ของผู้ใช้ที่จะลบ และสถานะของผู้ใช้
  deleteUser($_GET['delete'],$_GET['status']);
}

?>

<body>

  <?php
  require_once("nav.php");
  ?>
  <main class="" id="main-collapse">
    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ข้อมูลหอพัก</h1>
        <div align="right">
          <a href="edit_apartment.php" class="btn btn-success btn-lg">เพิ่มหอพัก</a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อหอพัก</td>
              <td>ประเภทหอพัก</td>
              <td>จำนวนห้อง</td>
              <td>จำนวนชั้น</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($allApartmentUser)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allApartmentUser as $data){ ?>
                <?php $sumRoomInApartment = getSumRoomInApartment($data['id']);?>
                <tr>
                  <td><?php echo $data["apart_name"];?></td>
                  <td><?php echo $apart_type_map[$data["apart_type"]];?></td>
                  <td><?php echo $sumRoomInApartment["numCount"];?></td>
                  <td><?php echo $data["apart_class"];?></td>
                  <td style="text-align: right;">
                    <a href="manage_room.php?apartments_id=<?php echo $data['id'];?>" class="btn btn-primary btn-lg">ห้องพัก</a>
                    <a href="edit_apartment.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">แก้ไข</a>
                    <a href="?delete=<?php echo $data['id'];?>&role=<?php echo $data['role'];?>" class="btn btn-danger btn-lg" onClick="javascript: return confirm('ยืนยันการลบ');">ลบ</a>
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