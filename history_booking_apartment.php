<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// เรียกใช้งานฟังก์ชัน getAllUserBookingApartment เพื่อดึงข้อมูลการจองทั้งหมดของผู้ใช้ที่เข้าสู่ระบบ
$allUserBookingApartment = getAllUserBookingApartment($_SESSION["id"]);
?>
<body>

  <?php
  require_once("nav.php");
  ?>
  <main class="" id="main-collapse">
    <div class="hero-full-wrapper">
      <div class="grid">
        <h1>ประวัติการจอง</h1>
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
            <?php if(empty($allUserBookingApartment)){ ?>
            <?php }else{?>
              <?php $i=1;?>
              <?php foreach($allUserBookingApartment as $data){ ?>
                <tr>
                  <td><?php echo $data["booking_name"];?></td>
                  <td><?php echo $data["booking_phone"];?></td>
                  <td><?php echo $data["booking_email"];?></td>
                  <td><?php echo $data["apart_name"];?></td>
                  <td><?php echo $data["room_name"];?></td>
                  <td><?php echo formatDateFull($data["booking_date"]);?></td>
                  <td><?php echo $booking_map[$data["booking_status"]];?></td>
                  <td style="text-align: right;">
                    <a href="detail_booking.php?id=<?php echo $data['id'];?>" class="btn btn-warning btn-lg">รายละเอียด</a>
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