<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

$allApartment = getAllApartment();
$currentRoom = getCurrentRoom($_GET["id"]);
if(isset($_POST["submit"])){

  if($_POST["id"] == ""){
    $room_gallery = $_FILES['room_gallery']['name'];
    $total = count($_FILES['room_gallery']['name']);

    saveRoomContract($_POST["apartment"],$_POST["room_name"],$_POST["bed_type"],$_POST["room_type"],$_POST["room_price"],$_POST["room_rent"],$_POST["room_detail"],$_FILES["room_image"]["name"],$room_gallery,$total,$_POST["users_id"],$_POST["room_category"],$_POST["contract_year"],$_POST["contract_end"],$_FILES["contract_file"]["name"],$_POST["room_lat"],$_POST["room_lng"]);
  }else{
    $room_gallery = $_FILES['room_gallery']['name'];
    $total = count($_FILES['room_gallery']['name']);
    editRoomContract($_POST["id"],$_POST["apartment"],$_POST["room_name"],$_POST["bed_type"],$_POST["room_type"],$_POST["room_price"],$_POST["room_rent"],$_POST["room_detail"],$_FILES["room_image"]["name"],$room_gallery,$total,$_POST["users_id"],$_POST["room_category"],$_POST["contract_year"],$_POST["contract_end"],$_FILES["contract_file"]["name"],$_POST["room_lat"],$_POST["room_lng"]);
  }
}

if($_GET["id"] == ""){
  $txtHead = "เพิ่ม ห้องพัก";
}else{
  $txtHead = "แก้ไข ห้องพัก";
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
          <p>ข้อมูลห้องพัก</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" name="id" value="<?php echo $currentRoom["rid"];?>">
            <input type="hidden" class="form-control" name="room_category" value="3">
            <input type="hidden" class="form-control" name="users_id" value="<?php echo $_SESSION["id"];?>">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <!--<div class="col-md-6">
                    <div class="form-group">
                      <label>หอพัก</label>
                      <select name="apartments_id" class="form-control" id="apartments_id" required>
                        <option value="">-- โปรดเลือก --</option>
                        <?php foreach($allApartment as $data){ ?>
                          <?php $selected = "";
                          if($currentRoom['apartments_id'] == $data['id']){
                            $selected = " selected";

                          }
                          ?>
                          <option value="<?php echo $data['id']?>" <?php echo $selected;?>><?php echo $data['apart_name']?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>-->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>หอพัก</label>
                      <input type="text" class="form-control" id="apartment" name="apartment" value="<?php echo $currentRoom["apartment"];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ชื่อห้อง</label>
                      <input type="text" class="form-control" id="room_name" name="room_name" value="<?php echo $currentRoom["room_name"];?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ประเภทเตียง</label>
                      <select name="bed_type" class="form-control" required>
                        <option value="">-- โปรดระบุ --</option>
                        <option value="1" <?php if($currentRoom['bed_type'] == 1){ ?> selected<?php } ?>>เตียงคู่</option>
                        <option value="2" <?php if($currentRoom['bed_type'] == 2){ ?> selected<?php } ?>>เตียงเดี่ยว</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ประเภทห้อง</label>
                      <select name="room_type" class="form-control" required>
                        <option value="">-- โปรดระบุ --</option>
                        <option value="1" <?php if($currentRoom['room_type'] == 1){ ?> selected<?php } ?>>แอร์</option>
                        <option value="2" <?php if($currentRoom['room_type'] == 2){ ?> selected<?php } ?>>พัดลม</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>ราคาห้อง (ต่อเดือน)</label>
                      <input type="text" class="form-control" id="room_price" name="room_price" value="<?php echo $currentRoom["room_price"];?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>ค่ามัดจำ</label>
                      <input type="text" class="form-control" id="room_rent" name="room_rent" value="<?php echo $currentRoom["room_rent"];?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>ระยะเวลาสัญญาเช่า (ปี)</label>
                      <input type="text" class="form-control" id="contract_year" name="contract_year" value="<?php echo $currentRoom["contract_year"];?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>วันที่สิ้นสุดสัญญา</label>
                      <input type="text" class="form-control" id="contract_end" name="contract_end" value="<?php echo formatDateFull($currentRoom["contract_end"]);?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ไฟล์หนังสือสัญญา</label>
                      <input type="file" class="form-control" name="contract_file" id="imgInp2" >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>รายละเอียดห้องพัก </label>
                      <textarea class="form-control" rows="3" name="room_detail"><?php echo $currentRoom["room_detail"];?></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รูปหน้าปก</label>
                      <input type="file" class="form-control" name="room_image" id="imgInp" >
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รูปภายในห้อง (สามารถเลือกได้มากกว่า 1 รูป)</label>
                      <input type="file" id="room_gallery" class="form-control" name="room_gallery[]" multiple >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ละติจูด</label>
                      <input type="text" class="form-control" id="lat" name="room_lat" value="<?php if($_GET['id'] == ""){ echo "16.2439983";} echo $currentRoom["room_lat"];?>" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ลองติจูด</label>
                      <input type="text" class="form-control" id="lng" name="room_lng" value="<?php if($_GET['id'] == ""){ echo "103.246472";} echo $currentRoom["room_lng"];?>" readonly>
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
                

                <div align="right">
                  <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <?php if($currentRoom["room_image"] == ""){ ?>
                    <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                  <?php }else{ ?>
                    <img class="img-responsive" alt="" src="images/room/<?php echo $currentRoom["room_image"];?>" id="blah">
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
  <script>

    $('#contract_end').datetimepicker({
      lang:'th',
      timepicker:false,
      format:'d/m/Y'
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