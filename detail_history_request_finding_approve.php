<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$currentRequest = getCurrentRequest($_GET["id"]);
$CurrentRequestDataroomer = getCurrentRequestDataroomer($_GET["id"]);
$currentQuestionaireFindding = getCurrentQuestionaireFindding($currentRequest["qusers_id"]);

?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>ข้อมูลเจ้าของห้อง</h1>
          <p>รายละเอียดการจับคู่</p>
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

                    <label><?php echo $currentRequest["apart_name"];?></label>
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
                    <label><?php echo $CurrentRequestDataroomer["username"];?></label>
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
                    <label><?php echo $CurrentRequestDataroomer["address"];?></label>
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
                    <label><?php echo $CurrentRequestDataroomer["email"];?></label>
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
                    <label><?php echo $CurrentRequestDataroomer["phone"];?></label>
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
                    <label><?php echo $gender_map[$CurrentRequestDataroomer["gender"]];?></label>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-6" >
              <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                <?php if($CurrentRequestDataroomer["profile_img"] == ""){ ?>
                  <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                <?php }else{ ?>
                  <img class="img-responsive" alt="" src="images/users/<?php echo $CurrentRequestDataroomer["profile_img"];?>" id="blah">
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
              <?php $total = ($currentQuestionaireFindding["q1"] + $currentQuestionaireFindding["q2"] + $currentQuestionaireFindding["q3"]+$currentQuestionaireFindding["q4"]+$currentQuestionaireFindding["q5"]+$currentQuestionaireFindding["q6"]+$currentQuestionaireFindding["q7"]+$currentQuestionaireFindding["q8"]+$currentQuestionaireFindding["q9"]+$currentQuestionaireFindding["q10"]) / 10;?>
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

                <td><?php echo $question_map[$currentQuestionaireFindding["q1"]];?></td>
              </tr>
              <tr>
                <td>นอนดึกมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q2"]];?></td>
              </tr>
              <tr>
                <td>ชอบทำความสะอาดมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q3"]];?></td>
              </tr>
              <tr>
                <td>ชอบเล่นเกมมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q4"]];?></td>
              </tr>
              <tr>
                <td>ดื่มแอลกอฮอลล์บ่อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q5"]];?></td>
              </tr>
              <tr>
                <td>สูบบุหรี่บ่อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q6"]];?></td>
              </tr>
              <tr>
                <td>เที่ยวกลางคืนบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q7"]];?></td>
              </tr>
              <tr>
                <td>ชอบทำอาหารบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q8"]];?></td>
              </tr>
              <tr>
                <td>อยู่ห้องบ่อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q9"]];?></td>
              </tr>
              <tr>
                <td>ต้องการความเป็นส่วนตัวมากน้อยแค่ไหน</td>
                <td><?php echo $question_map[$currentQuestionaireFindding["q10"]];?></td>
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