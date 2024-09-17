<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 

$currentUser = getCurrentUser($_SESSION["id"]);
$currentQuestionaireFindding = getCurrentQuestionaireFindding($_SESSION["id"]);

if(isset($_POST["submit"])){
  saveQuestionairesFindding($_POST["users_id"],$_POST["q1"],$_POST["q2"],$_POST["q3"],$_POST["q4"],$_POST["q5"],$_POST["q6"],$_POST["q7"],$_POST["q8"],$_POST["q9"],$_POST["q10"]);
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
          <h1>ข้อมูลผู้ใช้งาน</h1>
          <p>รายละเอียดผู้ใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>ชื่อ-นามสกุล</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $currentUser["username"];?></label>
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
                    <label><?php echo $currentUser["address"];?></label>
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
                    <label><?php echo $currentUser["email"];?></label>
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
                    <label><?php echo $currentUser["phone"];?></label>
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
                    <label><?php echo $gender_map[$currentUser["gender"]];?></label>
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
                    <label><?php echo $status_map[$currentUser["status"]];?></label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>สิทธิ์การใช้งาน</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label><?php echo $role_map[$currentUser["role"]];?></label>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-6" >
              <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                <?php if($currentUser["profile_img"] == ""){ ?>
                  <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
                <?php }else{ ?>
                  <img class="img-responsive" alt="" src="images/users/<?php echo $currentUser["profile_img"];?>" id="blah">
                <?php } ?>
              </div>
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
                <td><input type="radio" name="q1" value="5" required <?php if($currentQuestionaireFindding["q1"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q1" value="4" required <?php if($currentQuestionaireFindding["q1"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q1" value="3" required <?php if($currentQuestionaireFindding["q1"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q1" value="2" required <?php if($currentQuestionaireFindding["q1"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q1" value="1" required <?php if($currentQuestionaireFindding["q1"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>นอนดึกมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q2" value="5" required <?php if($currentQuestionaireFindding["q2"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q2" value="4" required <?php if($currentQuestionaireFindding["q2"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q2" value="3" required <?php if($currentQuestionaireFindding["q2"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q2" value="2" required <?php if($currentQuestionaireFindding["q2"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q2" value="1" required <?php if($currentQuestionaireFindding["q2"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>ชอบทำความสะอาดมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q3" value="5" required <?php if($currentQuestionaireFindding["q3"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q3" value="4" required <?php if($currentQuestionaireFindding["q3"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q3" value="3" required <?php if($currentQuestionaireFindding["q3"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q3" value="2" required <?php if($currentQuestionaireFindding["q3"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q3" value="1" required <?php if($currentQuestionaireFindding["q3"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>ชอบเล่นเกมมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q4" value="5" required <?php if($currentQuestionaireFindding["q4"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q4" value="4" required <?php if($currentQuestionaireFindding["q4"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q4" value="3" required <?php if($currentQuestionaireFindding["q4"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q4" value="2" required <?php if($currentQuestionaireFindding["q4"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q4" value="1" required <?php if($currentQuestionaireFindding["q4"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>ดื่มแอลกอฮอลล์บ่อยแค่ไหน</td>
                <td><input type="radio" name="q5" value="5" required <?php if($currentQuestionaireFindding["q5"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q5" value="4" required <?php if($currentQuestionaireFindding["q5"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q5" value="3" required <?php if($currentQuestionaireFindding["q5"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q5" value="2" required <?php if($currentQuestionaireFindding["q5"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q5" value="1" required <?php if($currentQuestionaireFindding["q5"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>สูบบุหรี่บ่อยแค่ไหน</td>
                <td><input type="radio" name="q6" value="5" required <?php if($currentQuestionaireFindding["q6"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q6" value="4" required <?php if($currentQuestionaireFindding["q6"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q6" value="3" required <?php if($currentQuestionaireFindding["q6"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q6" value="2" required <?php if($currentQuestionaireFindding["q6"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q6" value="1" required <?php if($currentQuestionaireFindding["q6"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>เที่ยวกลางคืนบ่อยแค่ไหน</td>
                <td><input type="radio" name="q7" value="5" required <?php if($currentQuestionaireFindding["q7"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q7" value="4" required <?php if($currentQuestionaireFindding["q7"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q7" value="3" required <?php if($currentQuestionaireFindding["q7"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q7" value="2" required <?php if($currentQuestionaireFindding["q7"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q7" value="1" required <?php if($currentQuestionaireFindding["q7"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>ชอบทำอาหารบ่อยแค่ไหน</td>
                <td><input type="radio" name="q8" value="5" required <?php if($currentQuestionaireFindding["q8"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q8" value="4" required <?php if($currentQuestionaireFindding["q8"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q8" value="3" required <?php if($currentQuestionaireFindding["q8"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q8" value="2" required <?php if($currentQuestionaireFindding["q8"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q8" value="1" required <?php if($currentQuestionaireFindding["q8"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>อยู่ห้องบ่อยแค่ไหน</td>
                <td><input type="radio" name="q9" value="5" required <?php if($currentQuestionaireFindding["q9"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q9" value="4" required <?php if($currentQuestionaireFindding["q9"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q9" value="3" required <?php if($currentQuestionaireFindding["q9"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q9" value="2" required <?php if($currentQuestionaireFindding["q9"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q9" value="1" required <?php if($currentQuestionaireFindding["q9"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <td>ต้องการความเป็นส่วนตัวมากน้อยแค่ไหน</td>
                <td><input type="radio" name="q10" value="5" required <?php if($currentQuestionaireFindding["q10"]==5 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q10" value="4" required <?php if($currentQuestionaireFindding["q10"]==4 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q10" value="3" required <?php if($currentQuestionaireFindding["q10"]==3 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q10" value="2" required <?php if($currentQuestionaireFindding["q10"]==2 ){ ?>checked<?php } ?>></td>
                <td><input type="radio" name="q10" value="1" required <?php if($currentQuestionaireFindding["q10"]==1 ){ ?>checked<?php } ?>></td>
              </tr>
            </tbody>
          </table>
          <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
        </div>
      </form>
    </div>

  </main>
  <?php
  require_once("footer.php");
  ?>

</body>

</html>