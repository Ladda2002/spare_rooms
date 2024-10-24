<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentRoom = getCurrentRoom($_GET["id"]);
$currentUserContract = getCurrentUser($currentRoom["users_id"]);
$allRoomGallery = getAllRoomGallery($_GET["id"]);
?>
<body onload="initialize();">

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
        <input type="hidden" class="form-control" id="lat" value="<?php echo $currentRoom["room_lat"];?>">
        <input type="hidden" class="form-control" id="lng" value="<?php echo $currentRoom["room_lng"];?>">
        <h3>ข้อมูลห้องพัก </h3>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ผู้ขายสัญญาเช่า</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["username"];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>หอพัก</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label>
                <?php echo $currentRoom["apartment"] . "  " . $currentRoom["apart_name"]; ?>
              </label>
            </div>
          </div>
        </div>
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
              <label>ระยะเวลาสัญญา</label>
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
              <label>วันที่สิ้นสุดสัญญา</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["contract_end"];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ดาวน์โหลดไฟล์สัญญา</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <a href="images/contract/<?php echo $currentRoom["contract_file"];?>" target="_blank">ดูสัญญา</a>
            </div>
          </div>
        </div>
        <div align="center">
          <a href="edit_buy.php?id=<?php echo $_GET["id"];?>" class="btn btn-primary" title="ติดต่อขอซื้อสัญญาเช่า"> ติดต่อขอซื้อสัญญาเช่า</a>
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

    <div class="row">
      <div class="col-xs-12 section-container-spacer">
        <legend>ข้อมูลที่ตั้ง</legend>
        <div id="map_canvas" style="width: auto; height: 500px;"></div>
      </div>

    </div>

  </main>

  <script type="text/javascript">
    function initialize() {

      var la = $("#lat").val();
      var ln = $("#lng").val();
      var map = new google.maps.Map(document.getElementById('map_canvas'), {
        zoom: 12,
        center: new google.maps.LatLng(la, ln),
        mapTypeId: google.maps.MapTypeId.DRIVER
      });

      var vMarker = new google.maps.Marker({
        position: new google.maps.LatLng(la, ln),
        draggable: true
      });

      google.maps.event.addListener(vMarker, 'dragend', function (evt) {
        $("#lat").val(evt.latLng.lat().toFixed(6));
        $("#lng").val(evt.latLng.lng().toFixed(6));


        var p1 = new google.maps.LatLng(la, ln);
        var p2 = new google.maps.LatLng(evt.latLng.lat(), evt.latLng.lng());


      });

      map.setCenter(vMarker.position);

      vMarker.setMap(map);
    }
  </script>

  <?php
  require_once("footer.php");
  ?>


</body>

</html>