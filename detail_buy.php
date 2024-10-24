<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

$currentBuy = getCurrentBuy($_GET["id"]);
$currentUser = getCurrentUser($currentBuy["rusers_id"]);
$currentRoom = getCurrentRoom($currentBuy["rooms_id"]);
$allRoomGallery = getAllRoomGallery($currentBuy["rooms_id"]);

?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">


    <div class="row">
      <div class="col-xs-12 col-md-6">
        <img class="img-responsive" alt="" src="images/room/<?php echo $currentRoom["room_image"];?>">
      </div>
      <div class="col-xs-12 col-md-6">
        <h1>ห้อง <?php echo $currentRoom["room_name"];?></h1>
        <h3>ข้อมูลห้องพัก </h3>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ประเภทเตียง</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $bed_map[$currentRoom["bed_type"]];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ประเภทห้อง</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $room_map[$currentRoom["room_type"]];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ราคาห้อง (ต่อเดือน)</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo number_format($currentRoom["room_price"]);?> บาท</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ค่ามัดจำ</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo number_format($currentRoom["room_rent"]);?> บาท</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>รายละเอียดห้องพัก</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["room_detail"];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ระยะเวลา</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["contract_year"];?> ปี</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>เจ้าของ</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentUser["username"];?> </label>
            </div>
          </div>
        </div>
        <legend>ข้อมูลผู้ติดต่อ</legend>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentBuy["buy_name"];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>หมายเลขโทรศัพท์</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentBuy["buy_phone"];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>อีเมล</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $currentBuy["buy_email"];?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>สถานะ</label>
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label><?php echo $booking_map[$currentBuy["buy_status"]];?></label>
              </div>
            </div>
          </div>
        
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 section-container-spacer">
      </div>
      <?php if(empty($allRoomGallery)){ ?>
      <?php }else{?>
        <?php foreach($allRoomGallery as $dataGal){ ?>
          <div class="col-xs-12 col-md-4 section-container-spacer">
            <img class="img-responsive" alt="" src="images/room_gallery/<?php echo $dataGal["images"];?>">
          </div>
        <?php } ?>
      <?php } ?>
    </div>

  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>