<?php
session_start();
require("function/function.php");
?>

<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width,initial-scale=1" name="viewport">
  <meta content="Page description" name="description">
  <meta name="google" content="notranslate" />
  <meta content="จองห้องพัก ซื้อ-ขายสัญญาหอพัก หารูมเมท (Spare Room)" name="author">

  <!-- Disable tap highlight on IE -->
  <meta name="msapplication-tap-highlight" content="no">
  
  <link href="assets/apple-icon-180x180.png" rel="apple-touch-icon">
  <link href="assets/favicon.ico" rel="icon">

  <title>จองห้องพัก ซื้อ-ขายสัญญาหอพัก หารูมเมท (Spare Room)</title>  

  <link href="assets/main.82cfd66e.css" rel="stylesheet">
  <script src="assets/js/jquery-3.5.0.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqB-O_qmvUMh-A8N5AbFT2LBgXIUkG7Vk &callback=initMap" async defer></script>

  <script src="assets/datepicker/datetimepicker-master/jquery.datetimepicker.js"></script>
  <link href="assets/datepicker/datetimepicker-master/jquery.datetimepicker.css" rel="stylesheet" />
</head>
<?php 
$bed_map = array( 1=>'เตียงคู่',2=>'เตียงเดี่ยว');
$room_map = array( 1=>'แอร์',2=>'พัดลม');
$room_status_map = array( 1=>'<label style="color:green">ว่าง</label>',2=>'<label style="color:red">ไม่ว่าง</label>');
$contract_status_map = array( 1=>'<label style="color:green">เปิด</label>',2=>'<label style="color:red">ปิด</label>');
$room_category_map = array( 1=>'<label style="color:green">จองห้อง</label>',2=>'<label style="color:red">หารูมเมท</label>');
$request_map = array( 0=>'<label style="color:green">ปฏิเสธคำขอ</label>',1=>'<label style="color:blue">รอการตอบรับ</label>',2=>'<label style="color:green">ยืนยันคำขอ</label>');
$gender_map = array( 1=>'ชาย',2=>'หญิง');
$status_map = array( 1=>'โสด',2=>'มีแฟน');
$question_map = array( 5=>'ดีมาก',4=>'ดี',3=>'ปานกลาง',2=>'น้อย',1=>'น้อยมาก');
$apart_type_map = array( 1=>'หอรวม',2=>'หอหญิง',3=>'หอชาย');
$role_map = array( 1=>'<label style="color:red">ผู้ดูแลระบบ</label>',2=>'<label style="color:blue">เจ้าของหอ</label>',3=>'<label style="color:green">ผู้ขายสัญญา</label>',4=>'<label style="color:black">เจ้าของห้อง</label>',5=>'<label style="color:violet">ผู้หาห้องเช่า</label>');
$booking_map = array( 0=>'<label style="color:red">ยกเลิก</label>',1=>'<label style="color:blue">รอยืนยัน</label>',2=>'<label style="color:green">ยืนยัน</label>');
$buy_map = array( 0=>'<label style="color:red">ยกเลิก</label>',1=>'<label style="color:blue">รอยืนยัน</label>',2=>'<label style="color:green">ยืนยัน</label>');
?>
