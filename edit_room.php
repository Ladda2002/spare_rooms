<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// เรียกข้อมูลห้องปัจจุบันจากฟังก์ชัน getCurrentRoom() 
$currentRoom = getCurrentRoom($_GET["id"]);

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่ โดยเช็คว่ามีการกดปุ่ม submit
if(isset($_POST["submit"])){
  // ถ้าค่า id ว่างเปล่า แสดงว่าเป็นการเพิ่มห้องใหม่
  if($_POST["id"] == ""){
    // เก็บชื่อไฟล์รูปภาพแกลเลอรี่ลงในตัวแปร $room_gallery
    $room_gallery = $_FILES['room_gallery']['name'];
    // นับจำนวนรูปภาพแกลเลอรี่ทั้งหมดแล้วเก็บในตัวแปร $total
    $total = count($_FILES['room_gallery']['name']);
    // เรียกฟังก์ชัน saveRoom() เพื่อบันทึกข้อมูลห้องใหม่
    saveRoom($_POST["apartments_id"],$_POST["room_name"],$_POST["bed_type"],$_POST["room_type"],$_POST["room_price"],$_POST["room_rent"],$_POST["room_detail"],$_FILES["room_image"]["name"],$room_gallery,$total,$_POST["users_id"],$_POST["room_category"]);
  }else{
    // ถ้า id มีค่า แสดงว่าเป็นการแก้ไขข้อมูลห้องที่มีอยู่แล้ว
    $room_gallery = $_FILES['room_gallery']['name'];
    $total = count($_FILES['room_gallery']['name']);
    // เรียกฟังก์ชัน editRoom() เพื่อแก้ไขข้อมูลห้อง
    editRoom($_POST["id"],$_POST["apartments_id"],$_POST["room_name"],$_POST["bed_type"],$_POST["room_type"],$_POST["room_price"],$_POST["room_rent"],$_POST["room_detail"],$_FILES["room_image"]["name"],$room_gallery,$total,$_POST["users_id"],$_POST["room_category"]);
  }
}

// ถ้าไม่มีค่า id แสดงว่าเป็นการเพิ่มหอพัก
if($_GET["id"] == ""){
  $txtHead = "เพิ่ม ห้องพัก";
}else{
   // ถ้ามีค่า id แสดงว่าเป็นการแก้ไขหอพัก
  $txtHead = "แก้ไข ห้องพัก";
}
?>

<body>

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
            <input type="hidden" class="form-control" name="users_id" value="<?php echo $_SESSION["id"];?>">
            <input type="hidden" class="form-control" name="room_category" value="1">
            <input type="hidden" class="form-control" name="apartments_id" value="<?php echo $_GET["apartments_id"];?>">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-12">
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ราคาห้อง (ต่อเดือน)</label>
                      <input type="text" class="form-control" id="room_price" name="room_price" value="<?php echo $currentRoom["room_price"];?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ค่ามัดจำ</label>
                      <input type="text" class="form-control" id="room_rent" name="room_rent" value="<?php echo $currentRoom["room_rent"];?>">
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
</body>

</html>