<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allUserBuyContract = getAllUserBuyContract($_SESSION["id"]);
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <!-- Add your site or app content here -->

    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ประวัติการซื้อสัญญา</h1>
        <table class="table">
          <thead>
            <tr>
              <td>ชื่อ-นามสกุล</td>
              <td>โทรศัพท์</td>
              <td>อีเมล</td>
              <td>ชื่อหอพัก</td>
              <td>ห้อง</td>
              <td>วันที่</td>
              <td>สถานะ</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($allUserBuyContract)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allUserBuyContract as $data){ ?>
                <tr>
                  <td><?php echo $data["buy_name"];?></td>
                  <td><?php echo $data["buy_phone"];?></td>
                  <td><?php echo $data["buy_email"];?></td>
                  <td><?php echo $data["apart_name"];?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo formatDateFull($data["buy_date"]);?></td>
                  <td><?php echo $buy_map[$data["buy_status"]];?></td>
                  <td style="text-align: right;">
                    <a href="detail_buy.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">รายละเอียด</a>
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