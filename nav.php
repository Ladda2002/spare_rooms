<!-- Add your content of header -->
<?php
$user = getUser($_SESSION["id"]);
$checkNewPending = getCheckNewPending($_SESSION["id"]);
$checkNewRequest = getCheckNewRequest($_SESSION["id"]);
$checkNewRequestBuy = getCheckNewRequestBuy($_SESSION["id"]);

if (isset($_GET['logout'])) {
  logout();
}

?>
<style>
  
.badge {
  background-color: red;
  color: white;
  padding: 4px 8px;
  text-align: center;
  border-radius: 5px;
}
.sidebar {
  background-color: #f1ece6;
}
</style>
<header class="" style="margin-top: -25px;">
  <div class="navbar navbar-default visible-xs">
    <button type="button" class="navbar-toggle collapsed">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a href="index.php" class="navbar-brand">Spare Room</a>
  </div>

  <nav class="sidebar">
    <div class="navbar-collapse" id="navbar-collapse">
      <div class="site-header hidden-xs">
        <a class="site-brand" href="index.php" title="">
          <img class="img-responsive site-logo" alt="" src="./assets/images/mashup-logo.svg">
          Spare Room
        </a>
        <?php if($_SESSION['id'] == ""){ ?>
          <p>จองห้องพัก ซื้อ-ขายสัญญาหอพัก หารูมเมท ลงประกาศฟรี!</p>
        <?php }else{ ?>
          <p>จองห้องพัก ซื้อ-ขายสัญญาหอพัก หารูมเมท ลงประกาศฟรี!</p>
          <p><?php echo $user['username'];?> <br/> <?php echo $role_map[$user['role']];?></p>
        <?php } ?>
      </div>
      <ul class="nav">
        <?php if($_SESSION['id'] == "" && empty($_SESSION['id'])){ ?>
          <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
          <li><a href="login.php" title="เข้าสู่ระบบ">เข้าสู่ระบบ</a></li>
          <!--<li><a href="all_apartment.php" title="จองห้องพัก">จองห้องพัก</a></li>
          <li><a href="all_roomate.php" title="ค้นหารูมเมท">ค้นหารูมเมท</a></li>-->
        <?php }else{ ?>
          <?php if($_SESSION['role'] == 1){ ?>
            <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
            <li><a href="manage_user.php?role=1" title="ข้อมูลผู้ดูแลระบบ">ข้อมูลผู้ดูแลระบบ</a></li>
            <li><a href="manage_user.php?role=2" title="ข้อมูลเจ้าของหอ">ข้อมูลเจ้าของหอ</a></li>
            <li><a href="manage_user.php?role=3" title="ข้อมูลผู้ขายประกัน">ข้อมูลผู้ขายสัญญาเช่า</a></li>
            <li><a href="manage_user.php?role=4" title="ข้อมูลเจ้าของห้อง">ข้อมูลเจ้าของห้อง</a></li>
            <li><a href="manage_user.php?role=5" title="ข้อมูลผู้หาห้องเช่า">ข้อมูลผู้หาห้องเช่า</a></li>
            <li><a href="history_all_booking.php" title="ข้อมูลการจอง">ข้อมูลการจอง</a></li>
            <li><a href="report_booking.php" title="รายงานการจอง">รายงานการจอง</a></li>
            <li><a href="pdf_roommate.php" title="รายงานการจับคู่" target="_blank">รายงานการจับคู่</a></li>
            <li><a href="report_contract.php" title="รายงานการขายสัญญาเช่า">รายงานการขายสัญญาเช่า</a></li>
            <li><a href="profile.php" title="ข้อมูลส่วนตัว">ข้อมูลส่วนตัว</a></li>
            <li><a href="?logout=true" title="ออกจากระบบ">ออกจากระบบ</a></li>
          <?php } ?>
          <?php if($_SESSION['role'] == 2){ ?>
            <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
            <li><a href="manage_apartment.php" title="จัดการหอพัก">จัดการหอพัก</a></li>
            <li><a href="manage_pending.php" title="ตรวจสอบการจอง">ตรวจสอบการจอง <?php if($checkNewPending["numBook"] != 0){ ?><span class="badge"><?php echo $checkNewPending["numBook"];?></span><?php } ?></a> </li>
            <li><a href="history_booking_apartment.php" title="ประวัติการจอง">ประวัติการจอง</a></li>
            <li><a href="report_statistic.php" title="ดูรายงานการจอง">ดูรายงานการจอง</a></li>
            <li><a href="report_booking_owner.php" title="พิมพ์รายงานการจอง">พิมพ์รายงานการจอง</a></li>
            <li><a href="profile.php" title="ข้อมูลส่วนตัว">ข้อมูลส่วนตัว</a></li>
            <li><a href="?logout=true" title="ออกจากระบบ">ออกจากระบบ</a></li>
          <?php } ?>
          <?php if($_SESSION['role'] == 3){ ?>
            <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
            <li><a href="manage_contract.php" title="จัดการห้องพัก">จัดการห้องพัก</a></li>
            <li><a href="manage_request_contract.php" title="ตรวจสอบคำขอ">ตรวจสอบคำขอ<?php if($checkNewRequestBuy["numBuy"] != 0){ ?><span class="badge"><?php echo $checkNewRequestBuy["numBuy"];?></span><?php } ?></a></li>
            <li><a href="history_contract.php" title="ประวัติการขายสัญญา">ประวัติการขายสัญญา</a></li>
            <li><a href="profile.php" title="ข้อมูลส่วนตัว">ข้อมูลส่วนตัว</a></li>
            <li><a href="?logout=true" title="ออกจากระบบ">ออกจากระบบ</a></li>
            

          <?php } ?>
          <?php if($_SESSION['role'] == 4){ ?>
            <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
            <li><a href="manage_user_room.php" title="จัดการห้องพัก">จัดการห้องพัก</a></li>
            <li><a href="manage_request_roommate.php" title="ตรวจสอบคำขอ">ตรวจสอบคำขอ<?php if($checkNewRequest["numReq"] != 0){ ?><span class="badge"><?php echo $checkNewRequest["numReq"];?></span><?php } ?></a></li>
            <li><a href="history_request_roommate.php" title="ประวัติคำขอ">ประวัติคำขอ</a></li>
            <li><a href="profile.php" title="ข้อมูลส่วนตัว">ข้อมูลส่วนตัว</a></li>
            <li><a href="?logout=true" title="ออกจากระบบ">ออกจากระบบ</a></li>
          <?php } ?>
          <?php if($_SESSION['role'] == 5){ ?>
            <li><a href="index.php" title="หน้าหลัก">หน้าหลัก</a></li>
            <li><a href="all_apartment.php" title="จองห้องพัก">จองห้องพัก</a></li>
            <li><a href="all_roomate.php" title="ค้นหารูมเมท">ค้นหารูมเมท</a></li>
            <li><a href="all_contract.php" title="ค้นหาสัญญาเช่า">ค้นหาสัญญาเช่า</a></li>
            <li><a href="history_booking.php" title="ประวัติการจอง">ประวัติการจอง</a></li>
            <li><a href="history_request.php" title="ประวัติคำขอ">ประวัติคำขอ</a></li>
            <li><a href="history_contract_finding.php" title="ประวัติการซื้อสัญญา">ประวัติการซื้อสัญญา</a></li>
            <li><a href="edit_question_finding.php" title="ทำแบบประเมิน">ทำแบบประเมิน</a></li>
            <li><a href="profile.php" title="ข้อมูลส่วนตัว">ข้อมูลส่วนตัว</a></li>
            <li><a href="?logout=true" title="ออกจากระบบ">ออกจากระบบ</a></li>
          <?php } ?>
        <?php } ?>
        
        

          <!--<li><a href="./about.html" title="">About</a></li>
          <li><a href="./services.html" title="">Services</a></li>
          <li><a href="./contact.html" title="">Contact</a></li>
          <li><a href="./components.html" title="">Components</a></li>-->

        </ul>

        <nav class="nav-footer">
          <!--<p class="nav-footer-social-buttons">
            <a class="fa-icon" href="https://www.instagram.com/" title="">
              <i class="fa fa-instagram"></i>
            </a>
            <a class="fa-icon" href="https://dribbble.com/" title="">
              <i class="fa fa-dribbble"></i>
            </a>
            <a class="fa-icon" href="https://twitter.com/" title="">
              <i class="fa fa-twitter"></i>
            </a>
          </p>-->
          <p>© Create By <br/> แสงตะวัน มีบง <br/> ลัดดาวรรณ ภูพวก</p>
        </nav>  
      </div> 
    </nav>
  </header>