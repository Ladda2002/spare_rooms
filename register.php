<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ตรวจสอบว่ามีการกดปุ่ม submit หรือไม่
if(isset($_POST["submit"])){
  // ตรวจสอบอีเมลจากฐานข้อมูลโดยใช้ฟังก์ชัน getCheckEmail
  $checkEmail = getCheckEmail($_GET["email"]);
   // หากไม่มีการใช้อีเมลนี้มาก่อน (numCount เท่ากับ 0)
  if($checkEmail["numCount"] == 0){
    // เรียกใช้ฟังก์ชัน saveRegister เพื่อบันทึกข้อมูลการลงทะเบียนของผู้ใช้
    saveRegister($_POST["username"],$_POST["password"],$_POST["address"],$_POST["email"],$_POST["phone"],$_POST["gender"],$_POST["status"],$_POST["role"],$_FILES["profile_img"]["name"]);
  }else{
    echo '<script>alert("Email มีการใช้งานแล้วไม่สามารถสมัครได้")</script>';  
    echo '<script>window.history.go(-1)</script>'; 
  }
  
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
          <h1>สมัครสมาชิก</h1>
          <p>ข้อมูลผู้ใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>ชื่อ-นามสกุล</label>
                      <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รหัสผ่าน</label>
                      <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>ที่อยู่</label>
                      <textarea class="form-control" rows="3" name="address" required></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>อีเมล</label>
                      <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>โทรศัพท์</label>
                      <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>เพศ &nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="radio" name="gender" id="gender" value="1" <?php if($currentUser["gender"]==1 || $currentUser["gender"]==""){ ?>checked<?php } ?>> ชาย &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="radio" name="gender" id="gender" value="2" <?php if($currentUser["gender"]==2 ){ ?>checked<?php } ?>> หญิง
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>สถานะ &nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="radio" name="status" id="status" value="1" <?php if($currentUser["status"]==1 || $currentUser["status"]==""){ ?>checked<?php } ?>> โสด &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="radio" name="status" id="status" value="2" <?php if($currentUser["status"]==2 ){ ?>checked<?php } ?>> มีแฟน
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>สิทธิ์การใช้งาน</label>
                      <select name="role" class="form-control" required>
                        <option value="">-- โปรดระบุสถานะ --</option>
                        <option value="2" >เจ้าของหอ</option>
                        <option value="3" >ผู้ขายสัญญาเช่า</option>
                        <option value="4" >เจ้าของห้อง</option>
                        <option value="5" >ผู้หาห้องเช่า</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>รูปประจำตัว</label>
                      <input type="file" class="form-control" name="profile_img" id="imgInp" >
                    </div>
                  </div>
                </div>
                <div align="right">
                  <button type="submit" name="submit" class="btn btn-success btn-lg">บันทึก</button>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <img class="img-responsive" alt="" src="images/user_ico.png" id="blah">
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
  // ฟังก์ชัน readURL สำหรับอ่านไฟล์ที่ถูกอัปโหลดโดยผู้ใช้
  function readURL(input) {
    
    // ตรวจสอบว่ามีไฟล์ถูกเลือกหรือไม่
    if (input.files && input.files[0]) {
      var reader = new FileReader(); 
      
      // เมื่ออ่านไฟล์เสร็จแล้ว ให้ทำการเปลี่ยนแปลงรูปภาพใน tag <img> โดยใช้ ID "blah"
      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result); 
      }

      // อ่านไฟล์ที่ผู้ใช้เลือกในรูปแบบ DataURL
      reader.readAsDataURL(input.files[0]);
    }
  }

  // เมื่อมีการเปลี่ยนแปลงใน input ที่มี ID "imgInp" (การเลือกไฟล์รูปภาพใหม่)
  $("#imgInp").change(function() {
    readURL(this); 
  });
</script>

</body>

</html>