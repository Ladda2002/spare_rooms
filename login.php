<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
// ตรวจสอบว่ามีการกดปุ่ม submit หรือไม่
if(isset($_POST["submit"])){
  
  // เรียกใช้ฟังก์ชัน checkLogin เพื่อตรวจสอบชื่อผู้ใช้และรหัสผ่านที่ผู้ใช้กรอกเข้ามา
  checkLogin($_POST["username"], $_POST["password"]);
}
?>
<?php require_once('auth.php') ?>
<?php require_once('vendor/autoload.php') ?>
<?php
$clientID = "731125983367-nr7fghqrq6kbat40kh9vcb40kjod78q2.apps.googleusercontent.com";
$secret = "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";

// สร้างอ็อบเจ็กต์ Google_Client สำหรับเชื่อมต่อกับ Google API
$gclient = new Google_Client(); 

// ตั้งค่า Client ID และ Secret ของ Google API
$gclient->setClientId($clientID); 
$gclient->setClientSecret($secret); 
$gclient->setRedirectUri('http://localhost/spare_rooms/login.php'); 

// เพิ่มสิทธิ์การเข้าถึง (Scopes) ที่ต้องการจาก Google
$gclient->addScope('email'); 
$gclient->addScope('profile'); 

// ตรวจสอบว่ามีค่า 'code' ถูกส่งมาหรือไม่ (เมื่อผู้ใช้ล็อกอินผ่าน Google สำเร็จ)
if(isset($_GET['code'])){
    // รับ (Token) จาก Google ด้วย Auth Code ที่ได้รับ
    $token = $gclient->fetchAccessTokenWithAuthCode($_GET['code']);
  
    // ตรวจสอบว่าไม่มีข้อผิดพลาดในการดึงโทเค็น
    if(!isset($token['error'])){
        // ตั้งค่า Access Token ให้กับ Google Client
        $gclient->setAccessToken($token['access_token']);

        // เก็บ Access Token ไว้ใน session
        $_SESSION['access_token'] = $token['access_token'];

        // ใช้ Google Service Oauth2 เพื่อดึงข้อมูลโปรไฟล์ของผู้ใช้
        $gservice = new Google_Service_Oauth2($gclient);

        // ดึงข้อมูลผู้ใช้จาก Google
        $udata = $gservice->userinfo->get();
        // วนลูปเก็บข้อมูลผู้ใช้ลงใน session โดยตั้งชื่อ session เป็น 'login_ชื่อข้อมูล'
        foreach($udata as $k => $v){
            $_SESSION['login_'.$k] = $v;
        }
        // เก็บค่า 'code' ที่ได้รับจาก Google ลงใน session
        $_SESSION['ucode'] = $_GET['code'];
    
        // ดึงค่าอีเมลจากข้อมูลใน session
        $email = $_SESSION['login_email'];
    
        // ดึงชื่อและนามสกุลจากข้อมูลใน session
        $firstname = $_SESSION['login_givenName'];
        $lastname = $_SESSION['login_familyName'];
        
        // ตัดอักษรจากอีเมล 
        $subEmail = substr($email,3,3);
    
        // เรียกใช้ฟังก์ชัน saveRegisterFromGoogleApi เพื่อบันทึกข้อมูลผู้ใช้
        saveRegisterFromGoogleApi($firstname,$lastname,$email);
        
        echo ("<script language='JavaScript'>
            window.location.href='index.php';
            </script>");
    
        exit; 
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
          <h1>เข้าสู่ระบบ</h1>
          <p>ยินดีต้อนรับ เข้าสู่ระบบเพื่อเริ่มการใช้งาน</p>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>อีเมล</label>
                  <input type="email" class="form-control" id="username" name="username" placeholder="E-mail">
                </div>
                <div class="form-group">
                  <label>รหัสผ่าน</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div align="right">
                  <button type="submit" name="submit" class="btn btn-success btn-lg">เข้าสู่ระบบ</button>
                  <a href="register.php" class="btn btn-warning btn-lg">สมัครสมาชิก</a>
                </div>
                <div align="right">
                  <a href="<?= $gclient->createAuthUrl() ?>" class="btn btn-outline-success">Login with Google</a>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center" style="width: 400px; margin-left: 100px;">
                  <img class="img-responsive" alt="" src="images/circular_image.png" id="blah">
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
</body>

</html>