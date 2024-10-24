<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
//รียกใช้ฟังก์ชัน: ฟังก์ชัน getAllUserRequestStatus() 
$allUserRequestStatus = getAllUserRequestStatus($_SESSION["id"]);


?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">
    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ตรวจสอบคำขอ</h1>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อ-นามสกุล</td>
              <td>โทรศัพท์</td>
              <td>อีเมล</td>
              <td>ชื่อหอพัก</td>
              <td>ห้อง</td>
              <td>สถานะ</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($allUserRequestStatus)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allUserRequestStatus as $data){ ?>
                <tr>
                  <td><?php echo $data["username"];?></td>
                  <td><?php echo $data["phone"];?></td>
                  <td><?php echo $data["email"];?></td>
                  <td><?php echo $data["apartment"] . "  " . $data["apart_name"]; ?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo $request_map[$data["request_status"]];?></td>
                  <td style="text-align: right;">
                    <a href="detail_request.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">รายละเอียด</a>
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