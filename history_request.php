<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allHistoryUserRequest = getAllHistoryUserRequest($_SESSION["id"]);
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <!-- Add your site or app content here -->

    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ประวัติคำขอ</h1>
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
            <?php if(empty($allHistoryUserRequest)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allHistoryUserRequest as $data){ ?>
                <tr>
                  <td><?php echo $data["username"];?></td>
                  <td><?php echo $data["phone"];?></td>
                  <td><?php echo $data["email"];?></td>
                  <td><?php echo $data["apartment"];?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo $request_map[$data["request_status"]];?></td>
                  <td style="text-align: right;">
                    <a href="detail_history_request.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">รายละเอียด</a>
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