<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentRequest = getCurrentRequest($_GET["id"]);
$CurrentQuestionaire = getCurrentQuestionaire($currentRequest["qusers_id"]);


?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>ข้อมูลผู้ขอ</h1>
          <p>รายละเอียดผู้ใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>หอพัก</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentRequest["apartment"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ห้อง</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentRequest["room_name"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ชื่อ-นามสกุล</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentRequest["username"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ที่อยู่</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentRequest["address"];?></label>
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
                    <label><?php echo $currentRequest["email"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>โทรศัพท์</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentRequest["phone"];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>เพศ</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $gender_map[$currentRequest["gender"]];?></label>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-6" >
              <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                <?php if($currentRequest["profile_img"] == ""){ ?>
                  <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                <?php }else{ ?>
                  <img class="img-responsive" alt="" src="images/users/<?php echo $currentRequest["profile_img"];?>" id="blah">
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
      <input type="hidden" class="form-control" id="requests_id" name="requests_id" value="<?php echo $_GET["id"];?>">
      <div class="row">
        <div class="col-xs-12 section-container-spacer">
          <legend>คำตอบจากแบบสอบถาม</legend>
        </div>

        <div align="center">
          <table class="table">
            <thead>
              <tr>
                <th>หัวข้อ</th>
                <th>ผลลัพธ์</th>
              </tr>
            </thead>
            <tbody>
              <?php $total = 0;?>
              <?php $total = ($currentRequest["q1"] + $currentRequest["q2"] + $currentRequest["q3"]+$currentRequest["q4"]+$currentRequest["q5"]+$currentRequest["q6"]+$currentRequest["q7"]+$currentRequest["q8"]+$currentRequest["q9"]+$currentRequest["q10"]) / 10;?>
              <?php 
              if($total >= 1.00 && $total <= 1.99){
                $txtRes = "น้อยมาก";
              }else if($total >= 2.00 && $total <= 2.99){
                $txtRes = "น้อย";
              }else if($total >= 3.00 && $total <= 3.99){
                $txtRes = "ปานกลาง";
              }else if($total <= 4.00 && $total <= 4.99){
                $txtRes = "ดี";
              }else{
                $txtRes = "ดีมาก";
              }
              ?>
              <tr>
                <td>ชอบสัตว์มากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q1"]];?></td>
              </tr>
              <tr>
                <td>นอนดึกมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q2"]];?></td>
              </tr>
              <tr>
                <td>ชอบทำความสะอาดมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q3"]];?></td>
              </tr>
              <tr>
                <td>ชอบเล่นเกมมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q4"]];?></td>
              </tr>
              <tr>
                <td>ดื่มแอลกอฮอลล์บ่อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q5"]];?></td>
              </tr>
              <tr>
                <td>สูบบุหรี่บ่อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q6"]];?></td>
              </tr>
              <tr>
                <td>เที่ยวกลางคืนบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q7"]];?></td>
              </tr>
              <tr>
                <td>ชอบทำอาหารบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q8"]];?></td>
              </tr>
              <tr>
                <td>อยู่ห้องบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q9"]];?></td>
              </tr>
              <tr>
                <td>ต้องการความเป็นส่วนตัวมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$CurrentQuestionaire["q10"]];?></td>
              </tr>
              <tr>
                <td>คะแนนที่ได้</td>
                <td style="color: red;"><?php echo number_format($total,2);?></td>
              </tr>
              <tr>
                <td>เกณฑ์การประเมิน</td>
                <td style="color: red;"><?php echo $txtRes;?></td>
              </tr>
              <tr>
                <td>สถานะ</td>
                <td style="color: red;"><?php echo $request_map[$currentRequest["request_status"]];?></td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </form>

  </main>
  <?php
  require_once("footer.php");
  ?>

</body>

</html>