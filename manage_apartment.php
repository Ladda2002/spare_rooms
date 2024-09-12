<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allApartmentUser = getAllApartmentUser($_SESSION["id"]);
if (isset($_GET['delete'])) {
  deleteUser($_GET['delete'],$_GET['status']);
}

?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <!-- Add your site or app content here -->

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
                <tr>
                  <td><?php echo $data["apart_name"];?></td>
                  <td><?php echo $apart_type_map[$data["apart_type"]];?></td>
                  <td><?php echo $data["apart_number"];?></td>
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