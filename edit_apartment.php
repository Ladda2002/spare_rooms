<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentApartment = getCurrentApartment($_GET["id"]);
if(isset($_POST["submit"])){
  if($_POST["id"] == ""){
    saveApartment($_POST["users_id"],$_POST["apart_name"],$_POST["apart_type"],$_POST["apart_number"],
    $_POST["apart_class"],$_POST["apart_elevator"],$_POST["apart_address"],$_POST["apart_detail"],
    $_FILES["apart_image"]["name"],$_FILES["apart_contract"]["name"],$_POST["apart_lat"],$_POST["apart_lng"]);
  }else{
    editApartment($_POST["id"],$_POST["users_id"],$_POST["apart_name"],$_POST["apart_type"],
    $_POST["apart_number"],$_POST["apart_class"],$_POST["apart_elevator"],$_POST["apart_address"],
    $_POST["apart_detail"],$_FILES["apart_image"]["name"],$_FILES["apart_contract"]["name"],$_POST["apart_lat"],
    $_POST["apart_lng"]);
  }
}

if($_GET["id"] == ""){
  $txtHead = "เพิ่ม หอพัก";
}else{
  $txtHead = "แก้ไข หอพัก";
}
?>
<body onload="initialize();">

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1><?php echo $txtHead;?></h1>
          <p>ข้อมูลหอพัก</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="id" value="<?php echo $currentApartment["aid"];?>">
            <input type="hidden" class="form-control" name="apart_lat" id="lat" value="<?php if($_GET['id'] == ""){ echo "16.2439982"; }else{ echo $currentApartment["apart_lat"]; }?>">
            <input type="hidden" class="form-control" name="apart_lng" id="lng" value="<?php if($_GET['id'] == ""){ echo "103.244176"; }else{ echo $currentApartment["apart_lng"]; }?>">
            <input type="hidden" class="form-control" name="users_id" value="<?php echo $_SESSION["id"];?>">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ชื่อหอพัก</label>
                      <input type="text" class="form-control" id="apart_name" name="apart_name" value="<?php echo $currentApartment["apart_name"];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ประเภทหอพัก</label>
                      <select name="apart_type" class="form-control" required>
                        <option value="">-- โปรดระบุ --</option>
                        <option value="1" <?php if($currentApartment['apart_type'] == 1){ ?> selected<?php } ?>>หอรวม</option>
                        <option value="2" <?php if($currentApartment['apart_type'] == 2){ ?> selected<?php } ?>>หอหญิง</option>
                        <option value="3" <?php if($currentApartment['apart_type'] == 3){ ?> selected<?php } ?>>หอชาย</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>จำนวนห้อง</label>
                      <input type="text" class="form-control" id="apart_number" name="apart_number" value="<?php echo $currentApartment["apart_number"];?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>จำนวนชั้น</label>
                      <input type="text" class="form-control" id="apart_class" name="apart_class" value="<?php echo $currentApartment["apart_class"];?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>ลิฟต์</label>
                      <select name="apart_elevator" class="form-control" required>
                        <option value="">-- โปรดระบุ --</option>
                        <option value="1" <?php if($currentApartment['apart_elevator'] == 1){ ?> selected<?php } ?>>มี</option>
                        <option value="2" <?php if($currentApartment['apart_elevator'] == 2){ ?> selected<?php } ?>>ไม่มี</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>ที่อยู่</label>
                      <textarea class="form-control" rows="3" name="apart_address"><?php echo $currentApartment["apart_address"];?></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>รายละเอียด </label>
                      <textarea class="form-control" rows="3" name="apart_detail"><?php echo $currentApartment["apart_detail"];?></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รูปหอพัก</label>
                      <input type="file" class="form-control" name="apart_image" id="imgInp" >
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>สัญญาการจอง</label>
                      <input type="file" class="form-control" name="apart_contract" id="apart_contract" >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div id="map_canvas" style="width: auto; height: 500px;"></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label>ละติจูด</label>
                      <input type="text" class="form-control" id="apart_lat" name="apart_lat" value="<?php echo $currentApartment["apart_lat"];?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>ลองติจูด</label>
                      <input type="text" class="form-control" id="apart_lng" name="apart_lng" value="<?php echo $currentApartment["apart_lng"];?>">
                    </div>
                  </div>
               
               

                <div align="right" style="margin-top: 20px;">
                  <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <?php if($currentApartment["apart_image"] == ""){ ?>
                    <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                  <?php }else{ ?>
                    <img class="img-responsive" alt="" src="images/apartment/<?php echo $currentApartment["apart_image"];?>" id="blah">
                  <?php } ?>
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>

  </main>
  <?php
  require_once("footer.php");
  ?>
  <script type="text/javascript">
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#imgInp").change(function() {
      readURL(this);
    });
  </script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqB-O_qmvUMh-A8N5AbFT2LBgXIUkG7Vk &callback=initMap" async defer></script>
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
</body>

</html>