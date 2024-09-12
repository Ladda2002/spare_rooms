<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allUserInRole = getAllUserInRole($_GET["role"]);
if (isset($_GET['delete'])) {
  deleteUser($_GET['delete'],$_GET['status']);
}

if($_GET["role"] == 1){
  $txtHead = "ข้อมูล ผู้ดูแลระบบ";
}else if($_GET["role"] == 2){
  $txtHead = "ข้อมูล เจ้าของหอ";
}else if($_GET["role"] == 3){
  $txtHead = "ข้อมูล ผู้ขายประกัน";
}else if($_GET["role"] == 4){
  $txtHead = "ข้อมูล เจ้าของห้อง";
}else{
  $txtHead = "ข้อมูล ผู้หาห้องเช่า";
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
        <h1><?php echo $txtHead;?></h1>
        <div align="right">
          <a href="edit_user.php" class="btn btn-success btn-lg">เพิ่ม</a>
        </div>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อ-นามสกุล</td>
              <td>โทรศัพท์</td>
              <td>อีเมล</td>
              <td>เพศ</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($allUserInRole)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allUserInRole as $data){ ?>
                <tr>
                  <td><?php echo $data["username"];?></td>
                  <td><?php echo $data["phone"];?></td>
                  <td><?php echo $data["email"];?></td>
                  <td><?php echo $gender_map[$data["gender"]];?></td>
                  <td style="text-align: right;">
                    <a href="detail_user.php?id=<?php echo $data['id'];?>" class="btn btn-default btn-lg">รายละเอียด</a>
                    <a href="edit_user.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">แก้ไข</a>
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