<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allApartment = getAllApartment();
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">


    <div class="row">
      <div class="col-xs-12 section-container-spacer">
        <h1>หอพักทั้งหมด</h1>
        <p>ข้อมูลหอพักที่ยังว่าง</p>
      </div>

      <?php if(empty($allApartment)){ ?>
      <?php }else{?>
        <?php $i=1;?>
        <?php foreach($allApartment as $data){ ?>
          <div class="col-xs-12 col-md-4 section-container-spacer">
            <img class="img-responsive" alt="" src="images/apartment/<?php echo $data["apart_image"];?>" style="width: 514px;height: 342px;">
            <h2><?php echo $data["apart_name"];?></h2>
            <p><?php echo $data["apart_address"];?></p>
            <a href="apartment_room.php?apartments_id=<?php echo $data['id'];?>" class="btn btn-primary" title=""> ข้อมูลห้อง</a>
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