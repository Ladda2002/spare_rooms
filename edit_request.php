<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentUser = getCurrentUser($_SESSION["id"]);
$currentRoom = getCurrentRoom($_GET["id"]);
$allRoomGallery = getAllRoomGallery($_GET["id"]);

if(isset($_POST["submit"])){
  saveRequest($_POST["rooms_id"],$_POST["users_id"],$_POST["q1"],$_POST["q2"],$_POST["q3"],$_POST["q4"],$_POST["q5"],$_POST["q6"],$_POST["q7"],$_POST["q8"],$_POST["q9"],$_POST["q10"]);
}
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
              <label>หอพัก</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["apart_name"];?></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ที่ตั้ง</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label><?php echo $currentRoom["apart_address"];?></label>
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
        <legend>ข้อมูลผู้ส่งคำขอ</legend>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>ชื่อ-นามสกุล</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <?php echo $currentUser["username"];?>
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
              <?php echo $currentUser["phone"];?>
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
              <?php echo $currentUser["email"];?>
            </div>
          </div>
        </div>
        
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 section-container-spacer">
        <legend>แบบสอบถาม</legend>
      </div>
      <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" class="form-control" id="rooms_id" name="rooms_id" value="<?php echo $_GET["id"];?>">
        <input type="hidden" class="form-control" id="users_id" name="users_id" value="<?php echo $_SESSION["id"];?>">
        <div align="center">
          <table class="table">
            <thead>
              <tr>
                <th>หัวข้อ</th>
                <th>5</th>
                <th>4</th>
                <th>3</th>
                <th>2</th>
                <th>1</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>ชอบสัตว์มากน้อยแค่ไหน</td>
                <td><input type="radio" name="q1" value="5" required></td>
                <td><input type="radio" name="q1" value="4" required></td>
                <td><input type="radio" name="q1" value="3" required></td>
                <td><input type="radio" name="q1" value="2" required></td>
                <td><input type="radio" name="q1" value="1" required></td>
              </tr>
              <tr>
                <td>นอนดึกมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q2" value="5" required></td>
                <td><input type="radio" name="q2" value="4" required></td>
                <td><input type="radio" name="q2" value="3" required></td>
                <td><input type="radio" name="q2" value="2" required></td>
                <td><input type="radio" name="q2" value="1" required></td>
              </tr>
              <tr>
                <td>ชอบทำความสะอาดมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q3" value="5" required></td>
                <td><input type="radio" name="q3" value="4" required></td>
                <td><input type="radio" name="q3" value="3" required></td>
                <td><input type="radio" name="q3" value="2" required></td>
                <td><input type="radio" name="q3" value="1" required></td>
              </tr>
              <tr>
                <td>ชอบเล่นเกมมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q4" value="5" required></td>
                <td><input type="radio" name="q4" value="4" required></td>
                <td><input type="radio" name="q4" value="3" required></td>
                <td><input type="radio" name="q4" value="2" required></td>
                <td><input type="radio" name="q4" value="1" required></td>
              </tr>
              <tr>
                <td>ดื่มแอลกอฮอลล์บ่อยแค่ไหน</td>
                <td><input type="radio" name="q5" value="5" required></td>
                <td><input type="radio" name="q5" value="4" required></td>
                <td><input type="radio" name="q5" value="3" required></td>
                <td><input type="radio" name="q5" value="2" required></td>
                <td><input type="radio" name="q5" value="1" required></td>
              </tr>
              <tr>
                <td>สูบบุหรี่บ่อยแค่ไหน</td>
                <td><input type="radio" name="q6" value="5" required></td>
                <td><input type="radio" name="q6" value="4" required></td>
                <td><input type="radio" name="q6" value="3" required></td>
                <td><input type="radio" name="q6" value="2" required></td>
                <td><input type="radio" name="q6" value="1" required></td>
              </tr>
              <tr>
                <td>เที่ยวกลางคืนบ่อยแค่ไหน</td>
                <td><input type="radio" name="q7" value="5" required></td>
                <td><input type="radio" name="q7" value="4" required></td>
                <td><input type="radio" name="q7" value="3" required></td>
                <td><input type="radio" name="q7" value="2" required></td>
                <td><input type="radio" name="q7" value="1" required></td>
              </tr>
              <tr>
                <td>ชอบทำอาหารบ่อยแค่ไหน</td>
                <td><input type="radio" name="q8" value="5" required></td>
                <td><input type="radio" name="q8" value="4" required></td>
                <td><input type="radio" name="q8" value="3" required></td>
                <td><input type="radio" name="q8" value="2" required></td>
                <td><input type="radio" name="q8" value="1" required></td>
              </tr>
              <tr>
                <td>อยู่ห้องบ่อยแค่ไหน</td>
                <td><input type="radio" name="q9" value="5" required></td>
                <td><input type="radio" name="q9" value="4" required></td>
                <td><input type="radio" name="q9" value="3" required></td>
                <td><input type="radio" name="q9" value="2" required></td>
                <td><input type="radio" name="q9" value="1" required></td>
              </tr>
              <tr>
                <td>ต้องการความเป็นส่วนตัวมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q10" value="5" required></td>
                <td><input type="radio" name="q10" value="4" required></td>
                <td><input type="radio" name="q10" value="3" required></td>
                <td><input type="radio" name="q10" value="2" required></td>
                <td><input type="radio" name="q10" value="1" required></td>
              </tr>
            </tbody>
          </table>
          <button type="submit" name="submit" class="btn btn-success btn-lg">ส่งคำขอ</button>
        </div>
      </form>
    </div>

  </main>

  <?php
  require_once("footer.php");
  ?>
</body>

</html>