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
                  <td><?php echo $data["apartment"] . "  " . $data["apart_name"]; ?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo $request_map[$data["request_status"]];?></td>
                  <td style="text-align: right;">
                      <?php 
                      // ตรวจสอบค่า request_status และกำหนด URL ตามเงื่อนไข
                      if ($data["request_status"] == 1) {
                          $url = "detail_history_request_finding_waiting.php?id=" . $data['id'];
                      } elseif ($data["request_status"] == 2) {
                          $url = "detail_history_request_finding_approve.php?id=" . $data['id'];
                      } else {
                        // ถ้า request_status ไม่มีค่าเป็น 1 หรือ 2 ให้ใช้ URL เป็น "#" 
                          $url = "#"; 
                      }
                      ?>
                      <a href="<?php echo $url; ?>" class="btn btn-warning btn-lg">รายละเอียด</a>
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