<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allRoomInApartment = getAllRoomInApartmentOpen($_GET["apartments_id"]);
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">
    

    <div class="row">
      <div class="col-xs-12 section-container-spacer">
        <h1>ห้องทั้งหมด</h1>
        <p>ข้อมูลห้องที่ยังว่าง</p>
      </div>

      <?php if(empty($allRoomInApartment)){ ?>
      <?php }else{?>
        <?php $i=1;?>
        <?php foreach($allRoomInApartment as $data){ ?>
          <div class="col-xs-12 col-md-4 section-container-spacer">
            <img class="img-responsive" alt="" src="images/room/<?php echo $data["room_image"];?>" style="width: 514px;height: 342px;">
            <h2><?php echo $data["room_name"];?></h2>
            <p><?php echo $room_map[$data["room_type"]];?></p>
            <a href="detail_room.php?id=<?php echo $data['id'];?>" class="btn btn-primary" title=""> รายละเอียด</a>
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