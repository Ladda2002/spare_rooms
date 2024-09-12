<?php
require_once('PHPMailer/PHPMailerAutoload.php');
function send_mail($recipient,$subject,$message,$full_name_th,$users_id){
  $mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "smtp.gmail.com"; // ถ้าใช้ smtp ของ server เป็นอยู่ในรูปแบบนี้ mail.domainyour.com
	$mail->Port = 587;
	$mail->isHTML();
	$mail->CharSet = "utf-8"; //ตั้งเป็น UTF-8 เพื่อให้อ่านภาษาไทยได้
	$mail->Username = ""; //กรอก Email Gmail หรือ เมลล์ที่สร้างบน server ของคุณเ
	$mail->Password = ""; // ใส่รหัสผ่าน email ของคุณ
	$mail->SetFrom = ('admin@gmail.com'); //กำหนด email เพื่อใช้เป็นเมล์อ้างอิงในการส่ง
	$mail->FromName = "admin_card"; //ชื่อที่ใช้ในการส่ง
	$mail->Subject = $subject;  //หัวเรื่อง emal ที่ส่ง
	$mail->Body = $message; //รายละเอียดที่ส่ง
	$mail->AddAddress($recipient,$full_name_th); //อีเมล์และชื่อผู้รับ
	
	//ส่วนของการแนบไฟล์ รองรับ .rar , .jpg , png
	//$mail->AddAttachment("files/1.rar");
	//$mail->AddAttachment("files/1.png");
	
  //$mail->Send();
	//ตรวจสอบว่าส่งผ่านหรือไม่
	if ($mail->Send()){
	//echo "ข้อความของคุณได้ส่งพร้อมไฟล์แนบแล้ว!!";

	}else{
		//echo $mail->Send();
	//echo "การส่งไม่สำเร็จ";
  //echo "Error while sending Email.";
  //var_dump($mail);
	}
}


?>