<?php
error_reporting(0);

//เชื่อต่อ Database
$con = mysqli_connect("localhost","root","","spare_rooms");
$con->set_charset("utf8");

// ฟังก์ชันสำหรับตรวจสอบข้อมูลการเข้าสู่ระบบ
function checkLogin($username, $password){
	$data = array(); 
	global $con; 

	// คำสั่ง SQL สำหรับตรวจสอบชื่อผู้ใช้และรหัสผ่านจากฐานข้อมูล
	$res = mysqli_query($con, "SELECT * FROM users WHERE email = '".$username."' AND password = '".$password."' ");
	
	// วนลูปเพื่อดึงข้อมูลผู้ใช้
	while($row = mysqli_fetch_array($res)) {
		$data['id'] = $row['id']; // เก็บข้อมูล id ของผู้ใช้
		$data['role'] = $row['role']; // เก็บข้อมูลบทบาทของผู้ใช้
	}
	
	// ตรวจสอบว่ามีข้อมูลผู้ใช้หรือไม่
	if (!empty($data)) {
		session_start(); 
		$id = $data['id']; // เก็บค่า id ของผู้ใช้
		$_SESSION['id'] = $data['id']; // เก็บค่า id ลงใน session
		$_SESSION['role'] = $data['role']; // เก็บค่า role ลงใน session

		
		echo ("<script language='JavaScript'>
			window.location.href='index.php';
			</script>");
	} else {
		echo "<script type='text/javascript'>alert('ไม่สามารถเข้าสู่ระบบได้');</script>";
	}
	
	// ปิดการเชื่อมต่อฐานข้อมูล
	mysqli_close($con);
}


// ฟังก์ชันสำหรับจัดรูปแบบวันที่ให้อยู่ในรูปแบบ DD/MM/YYYY
function formatDateFull($date) {
	// ตรวจสอบถ้าวันที่เป็นค่า "0000-00-00" ให้คืนค่าว่าง
	if($date == "0000-00-00") {
		return "";
	}
	// ตรวจสอบถ้าวันที่ว่างให้คืนค่าวันที่นั้นๆ
	if($date == "") {
		return $date;
	}
	// แปลงวันที่จากรูปแบบ YYYY-MM-DD เป็นอาเรย์โดยใช้ "-" เป็นตัวแบ่ง
	$raw_date = explode("-", $date);
	// คืนค่าวันที่ในรูปแบบ DD/MM/YYYY
	return $raw_date[2] . "/" . $raw_date[1] . "/" . $raw_date[0];
}

// ฟังก์ชันสำหรับออกจากระบบ (Logout)
function logout() {
	session_start(); // เริ่มต้น session
	session_unset();  // เคลียร์ข้อมูลใน session ทั้งหมด
	session_destroy(); // ทำลาย session ปัจจุบัน

	echo ("<script language='JavaScript'>
		window.location.href='index.php';
		</script>");
	exit(); 
}

//ฟังชั่นเรียกใช้ผู้ใช้งาน
function getUser($id){

	global $con;

	$sql = "SELECT * FROM users WHERE id = '".$id."'";
	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getSumRoomInApartment($apartments_id){

	global $con;

	$sql = "SELECT COUNT(*) as numCount FROM rooms WHERE apartments_id = '".$apartments_id."'";
	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

// ฟังก์ชันสำหรับตรวจสอบอีเมลในฐานข้อมูล
function getCheckEmail($email){

	global $con;

	// คำสั่ง SQL สำหรับนับจำนวนผู้ใช้ที่มีอีเมลซ้ำกัน
	$res = mysqli_query($con,"SELECT COUNT(*) as numCount FROM users WHERE email = '".$email."'");
	
	$result = mysqli_fetch_array($res, MYSQLI_ASSOC); //ฟังก์ชันนี้จะดึงข้อมูลจากผลลัพธ์ $res ทีละแถวแล้วนำไปเก็บในตัวแปร $result
	return $result;
	mysqli_close($con);
}

// ฟังก์ชันสำหรับบันทึกข้อมูลการลงทะเบียนผู้ใช้
function saveRegister($username, $password, $address, $email, $phone, $gender, $status, $role, $profile_img){
	
	global $con;

	// ตรวจสอบว่ามีไฟล์รูปโปรไฟล์หรือไม่
	if($profile_img != null){

		// ย้ายไฟล์รูปโปรไฟล์ไปยังโฟลเดอร์ images/users/
		if(move_uploaded_file($_FILES["profile_img"]["tmp_name"],"images/users/".$_FILES["profile_img"]["name"])){

			// หากมีไฟล์รูปโปรไฟล์ให้ทำการเพิ่มข้อมูลพร้อมรูปโปรไฟล์ในฐานข้อมูล
			$sql = "INSERT INTO users (username, password, address, email, phone, gender, status, role, profile_img) 
					VALUES('".$username."','".$password."','".$address."','".$email."','".$phone."','".$gender."','".$status."','".$role."','".$_FILES["profile_img"]["name"]."')";
		}
	}else{
		// หากไม่มีไฟล์รูปโปรไฟล์ให้ทำการเพิ่มข้อมูลโดยไม่มีรูปโปรไฟล์
		$sql = "INSERT INTO users (username, password, address, email, phone, gender, status, role) 
				VALUES('".$username."','".$password."','".$address."','".$email."','".$phone."','".$gender."','".$status."','".$role."')";
	}

	mysqli_query($con, $sql);
	
	mysqli_close($con);
	echo ("<script language='JavaScript'>
			alert('เพิ่มข้อมูลเรียบร้อย');
			window.location.href='login.php';
			</script>"); 
}


function saveUser($username,$password,$address,$email,$phone,$gender,$status,$role,$profile_img){
	
	
	global $con;

	if($profile_img != null){
		if(move_uploaded_file($_FILES["profile_img"]["tmp_name"],"images/users/".$_FILES["profile_img"]["name"]))
		{

			$sql = "INSERT INTO users (username, password, address, email, phone, gender, status, role, profile_img) VALUES('".$username."','".$password."','".$address."','".$email."','".$phone."','".$gender."','".$status."','".$role."','".$_FILES["profile_img"]["name"]."')";
		}
	}else{

		$sql = "INSERT INTO users (username, password, address, email, phone, gender, status, role) VALUES('".$username."','".$password."','".$address."','".$email."','".$phone."','".$gender."','".$status."','".$role."')";
	}
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_user.php?role=$role';
		</script>"); 
	
}

function editUser($id,$username,$password,$address,$email,$phone,$gender,$status,$role,$profile_img){

	global $con;

	if($profile_img != null){
		if(move_uploaded_file($_FILES["profile_img"]["tmp_name"],"images/users/".$_FILES["profile_img"]["name"]))
		{
			$sql="UPDATE users SET username='".$username."',password='".$password."',address='".$address."',email='".$email."',phone='".$phone."',gender='".$gender."',status='".$status."',role='".$role."',profile_img='".$_FILES["profile_img"]["name"]."' WHERE id = '".$id."'";
		}
	}else{
		$sql="UPDATE users SET username='".$username."',password='".$password."',address='".$address."',email='".$email."',phone='".$phone."',gender='".$gender."',status='".$status."',role='".$role."' WHERE id = '".$id."'";

	}
	mysqli_query($con,$sql);
	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='manage_user.php?role=$role';
		</script>"); 
	
}

//ฟังก์ชั่นสำหรับแก้ไขProfile
function editProfile($id, $username, $password, $address, $email, $phone, $gender, $status, $profile_img) {

	global $con; 

	// เช็คว่ามีการอัปโหลดภาพโปรไฟล์หรือไม่
	if($profile_img != null) {
		// หากมีการอัปโหลดภาพ ให้ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
		if(move_uploaded_file($_FILES["profile_img"]["tmp_name"], "images/users/".$_FILES["profile_img"]["name"])) {
			// สร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูลผู้ใช้ รวมถึงภาพโปรไฟล์
			$sql = "UPDATE users SET username='".$username."', password='".$password."', address='".$address."', email='".$email."', phone='".$phone."', gender='".$gender."', status='".$status."', profile_img='".$_FILES["profile_img"]["name"]."' WHERE id = '".$id."'";
		}
	} else {
		// หากไม่มีการอัปโหลดภาพ ให้สร้างคำสั่ง SQL สำหรับการอัปเดตข้อมูลผู้ใช้โดยไม่รวมภาพโปรไฟล์
		$sql = "UPDATE users SET username='".$username."', password='".$password."', address='".$address."', email='".$email."', phone='".$phone."', gender='".$gender."', status='".$status."' WHERE id = '".$id."'";
	}
	mysqli_query($con, $sql);
	mysqli_close($con); 

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='profile.php';
		</script>"); 
}

// ฟังชั่นลบข้อมูลUser
function deleteUser($id,$role){
	global $con;

	// สั่งให้ลบข้อมูลผู้ใช้จากตาราง users โดยอ้างอิงจาก id ที่ส่งเข้ามา
	mysqli_query($con,"DELETE FROM users WHERE id='".$id."'");
	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_user.php?role=$role';
		</script>"); 
}


function getAllUser(){
	global $con;

	$res = mysqli_query($con,"SELECT * FROM users ORDER BY id DESC");

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['id'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'profile_img' => $row['profile_img']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}
//ฟังก์ชัน getAllUserInRoleทำหน้าที่ดึงข้อมูลผู้ใช้ทั้งหมดที่มีบทบาท (role)
function getAllUserInRole($role){
	global $con;

	// ดึงข้อมูลผู้ใช้ทั้งหมดที่มีบทบาทตามที่ระบุ
	$res = mysqli_query($con,"SELECT * FROM users WHERE role = '".$role."' ORDER BY id DESC");

	$data = array();
	// ลูปผ่านข้อมูลที่ดึงมาเพื่อเก็บข้อมูลในรูปแบบของอาเรย์
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['id'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'profile_img' => $row['profile_img']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังก์ชันที่ชื่อ getCurrentUser สำหรับรับพารามิเตอร์ $id ซึ่งเป็น ID ของผู้ใช้ที่ต้องการดึงข้อมูล
function getCurrentUser($id) {
    global $con; 

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลผู้ใช้ที่มี ID ตรงกับที่กำหนด
    $res = mysqli_query($con,"SELECT * FROM users WHERE id = '".$id."'");

    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result; 
    mysqli_close($con); 
}

//ฟังก์ชันsaveApartmentเพื่อบันทึกข้อมูลอพาร์ตเมนต์ลงในฐานข้อมูล
function saveApartment($users_id,$apart_name,$apart_type,$apart_number,$apart_class,$apart_elevator,$apart_address,$apart_detail,$apart_image,$apart_contract,$apart_lat,$apart_lng){
	
	global $con;

	// ตรวจสอบว่ามีการอัพโหลดทั้งรูปภาพอพาร์ตเมนต์และสัญญาหรือไม่
	if($apart_image != null && $apart_contract != null) {
		// ย้ายไฟล์รูปภาพและสัญญาไปเก็บในโฟลเดอร์ที่กำหนด
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"], "images/apartment/".$_FILES["apart_image"]["name"]) && 
		   move_uploaded_file($_FILES["apart_contract"]["tmp_name"], "images/contract/".$_FILES["apart_contract"]["name"])) {
			// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลอพาร์ตเมนต์และอัปโหลดไฟล์ทั้งสอง
			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_contract, apart_lat, apart_lng, apart_detail, apart_image) 
			VALUES('".$users_id."', '".$apart_name."', '".$apart_address."', '".$apart_type."', '".$apart_number."', '".$apart_class."', '".$apart_elevator."', '".$_FILES["apart_contract"]["name"]."', '".$apart_lat."', '".$apart_lng."', '".$apart_detail."', '".$_FILES["apart_image"]["name"]."')";
		}
	}
	// ตรวจสอบว่ามีการอัพโหลดเฉพาะรูปภาพอพาร์ตเมนต์
	else if($apart_image != null && $apart_contract == null) {
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"], "images/apartment/".$_FILES["apart_image"]["name"])) {
			// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลอพาร์ตเมนต์พร้อมไฟล์รูปภาพ
			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_lat, apart_lng, apart_detail, apart_image) 
			VALUES('".$users_id."', '".$apart_name."', '".$apart_address."', '".$apart_type."', '".$apart_number."', '".$apart_class."', '".$apart_elevator."', '".$apart_lat."', '".$apart_lng."', '".$apart_detail."', '".$_FILES["apart_image"]["name"]."')";
		}
	}
	// ตรวจสอบว่ามีการอัพโหลดเฉพาะสัญญา
	else if($apart_image == null && $apart_contract != null) {
		if(move_uploaded_file($_FILES["apart_contract"]["tmp_name"], "images/contract/".$_FILES["apart_contract"]["name"])) {
			// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลอพาร์ตเมนต์พร้อมไฟล์สัญญา
			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_contract, apart_lat, apart_lng, apart_detail) 
			VALUES('".$users_id."', '".$apart_name."', '".$apart_address."', '".$apart_type."', '".$apart_number."', '".$apart_class."', '".$apart_elevator."', '".$_FILES["apart_contract"]["name"]."', '".$apart_lat."', '".$apart_lng."', '".$apart_detail."')";
		}
	}
	// กรณีที่ไม่มีการอัพโหลดไฟล์รูปภาพหรือสัญญา
	else {
		// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลอพาร์ตเมนต์โดยไม่มีไฟล์อัพโหลด
		$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_lat, apart_lng, apart_detail) 
		VALUES('".$users_id."', '".$apart_name."', '".$apart_address."', '".$apart_type."', '".$apart_number."', '".$apart_class."', '".$apart_elevator."', '".$apart_lat."', '".$apart_lng."', '".$apart_detail."')";
	}
	// ส่งคำสั่ง SQL ไปยังฐานข้อมูล
	mysqli_query($con, $sql);
	$last_id = $con->insert_id;

	$sqlCheck = "SELECT * FROM rooms WHERE apartment = '".$apart_name."'";
	$res = mysqli_query($con, $sqlCheck);
	
	// วนลูปเพื่อดึงข้อมูลผู้ใช้
	while($row = mysqli_fetch_array($res)) {
		$data['apartment'] = $row['apartment'];
	}
	if(!empty($data)){
		$sqlUp = "UPDATE rooms SET apartments_id='".$last_id."',apartment='' WHERE apartment = '".$data['apartment']."'";
		mysqli_query($con, $sqlUp);
	}

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_apartment.php';
		</script>");
}

//ฟังก์ชันeditApartmentเพื่อแก้ไขข้อมูลอพาร์ตเมนต์ลงในฐานข้อมูล
function editApartment($id,$users_id,$apart_name,$apart_type,$apart_number,$apart_class,$apart_elevator,$apart_address,$apart_detail,$apart_image,$apart_contract,$apart_lat,$apart_lng){

	global $con;
	// ตรวจสอบว่ามีการอัพโหลดทั้งรูปภาพอพาร์ตเมนต์และสัญญาหรือไม่
	if($apart_image != null && $apart_contract != null){
		// ย้ายไฟล์รูปภาพและสัญญาไปเก็บในโฟลเดอร์ที่กำหนด
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]) && move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{
			// สร้างคำสั่ง SQL เพื่อแก้ไขข้อมูลอพาร์ตเมนต์และอัปโหลดไฟล์ทั้งสอง
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_contract='".$_FILES["apart_contract"]["name"]."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."',apart_image='".$_FILES["apart_image"]["name"]."' WHERE id = '".$id."'";
		}
	// ตรวจสอบว่ามีการอัพโหลดเฉพาะรูปภาพอพาร์ตเมนต์
	}else if($apart_image != null && $apart_contract == null){
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]))
		{
			// สร้างคำสั่ง SQL เพื่อแก้ไขข้อมูลอพาร์ตเมนต์พร้อมไฟล์รูปภาพ
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."',apart_image='".$_FILES["apart_image"]["name"]."' WHERE id = '".$id."'";
		}
	// ตรวจสอบว่ามีการอัพโหลดเฉพาะสัญญา
	}else if($apart_image == null && $apart_contract != null){
		if(move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{
			// สร้างคำสั่ง SQL เพื่อแก้ไขข้อมูลอพาร์ตเมนต์พร้อมไฟล์สัญญา
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_contract='".$_FILES["apart_contract"]["name"]."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."' WHERE id = '".$id."'";
		}
		// กรณีที่ไม่มีการอัพโหลดไฟล์รูปภาพหรือสัญญา
	}else{
		// สร้างคำสั่ง SQL เพื่อแก้ไขข้อมูลอพาร์ตเมนต์โดยไม่มีไฟล์อัพโหลด
		$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."' WHERE id = '".$id."'";

	}
	mysqli_query($con,$sql);
	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='manage_apartment.php';
		</script>"); 
	
}
//ฟังก์ชั่นลบหอพัก
function deleteApartment($id){
	global $con;

	mysqli_query($con,"DELETE FROM apartments WHERE id='".$id."'");
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_apartment.php';
		</script>"); 

}

//ฟังก์ชันนี้ทำหน้าที่ดึงข้อมูลหอพักทั้งหมดจากฐานข้อมูล ตาราง apartments และ users
function getAllApartment(){
	global $con;
// สร้างคำสั่ง SQL เพื่อดึงข้อมูลหอพักทั้งหมด พร้อมข้อมูลผู้ใช้ที่เกี่ยวข้อง
	$sql = "SELECT *,a.id as aid FROM apartments a LEFT JOIN users u ON a.users_id = u.id ORDER BY a.id DESC";
	$res = mysqli_query($con,$sql); 

	$data = array();
	while($row = mysqli_fetch_assoc($res)) { 
		$namesArray[] = array( 
			'id' => $row['aid'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'apart_name' => $row['apart_name'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image'],
			'profile_img' => $row['profile_img']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

// ดึงข้อมูลอพาร์ตเมนต์ของผู้ใช้ตาม users_id
function getAllApartmentUser($users_id){
	global $con; 

	// ดึงข้อมูลอพาร์ตเมนต์และข้อมูลผู้ใช้โดยใช้ LEFT JOIN และเรียงลำดับตาม id ของอพาร์ตเมนต์จากมากไปน้อย
	$sql = "SELECT *, a.id as aid FROM apartments a 
			LEFT JOIN users u ON a.users_id = u.id 
			WHERE a.users_id = '".$users_id."' 
			ORDER BY a.id DESC"; 

	// รันคำสั่ง SQL
	$res = mysqli_query($con, $sql);

	// สร้าง array สำหรับเก็บข้อมูลอพาร์ตเมนต์และผู้ใช้
	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		// เก็บข้อมูลในรูปแบบ array ตามคอลัมน์ต่าง ๆ ในฐานข้อมูล
		$namesArray[] = array(
			'id' => $row['aid'],                
			'username' => $row['username'],     
			'password' => $row['password'],     
			'address' => $row['address'],        
			'email' => $row['email'],           
			'phone' => $row['phone'],            
			'gender' => $row['gender'],          
			'status' => $row['status'],          
			'role' => $row['role'],              
			'apart_name' => $row['apart_name'],  
			'apart_address' => $row['apart_address'],  
			'apart_type' => $row['apart_type'],        
			'apart_number' => $row['apart_number'],    
			'apart_class' => $row['apart_class'],      
			'apart_elevator' => $row['apart_elevator'], 
			'apart_contract' => $row['apart_contract'], 
			'apart_lat' => $row['apart_lat'],          
			'apart_lng' => $row['apart_lng'],          
			'apart_detail' => $row['apart_detail'],    
			'apart_image' => $row['apart_image'],      
			'profile_img' => $row['profile_img']      
		);
	}

	$data = $namesArray;
	return $data;
	mysqli_close($con);
}

//ฟังชั่นเพื่อตรวจสอบและดึงข้อมูลอพาร์ตเมนต์โดยใช้ id ของอพาร์ตเมนต์เป็นเงื่อนไขในการค้นหา
function getCurrentApartment($id){
    global $con;

    // ส่งคำสั่ง SQL เพื่อดึงข้อมูลอพาร์ตเมนต์จากตาราง apartments (a) 
    // โดยเชื่อมตาราง users (u) เพื่อดึงข้อมูลของผู้ใช้งานที่เกี่ยวข้อง
    $res = mysqli_query($con,"SELECT *,a.id as aid FROM apartments a 
                               LEFT JOIN users u ON a.users_id = u.id 
                               WHERE a.id = '".$id."'");

    // ดึงข้อมูลจากผลลัพธ์ของการ query และแปลงเป็น array 
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;
    mysqli_close($con);
}
//ฟังก์ชัน saveRoom() ใช้สำหรับ บันทึกข้อมูลห้องroom
function saveRoom($apartments_id,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category){

	global $con;

	// ตรวจสอบว่ามีการอัพโหลดภาพห้องหรือไม่
    if($room_image != null){
        // ถ้ามีภาพ ให้ย้ายไฟล์ภาพจากตำแหน่งชั่วคราวไปยังโฟลเดอร์ images/room/
        if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
        {
            // สร้าง SQL สำหรับการเพิ่มข้อมูลห้องลงในตาราง rooms พร้อมกับชื่อไฟล์รูปภาพ
            $sql = "INSERT INTO rooms (apartments_id, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category) VALUES('".$apartments_id."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."')";
            mysqli_query($con,$sql);
            // เก็บค่า ID ของห้องล่าสุดที่ถูกเพิ่ม
            $last_id = $con->insert_id;
        }
    } else {
        // ถ้าไม่มีการอัพโหลดภาพห้อง ให้เพิ่มข้อมูลห้องโดยไม่ใส่ภาพ
        $sql = "INSERT INTO rooms (apartments_id, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category) VALUES('".$apartments_id."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."')";
        mysqli_query($con,$sql);
        // เก็บค่า ID ของห้องล่าสุดที่ถูกเพิ่ม
        $last_id = $con->insert_id;
    }

    // ลูปผ่านแกลเลอรี่รูปภาพที่อัพโหลดมา (ถ้ามี)
    for( $i=0 ; $i < $total ; $i++ ) {

        // ตำแหน่งไฟล์ชั่วคราวของรูปภาพในแกลเลอรี่
        $tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];

        if ($tmpFilePath != ""){
            // ตำแหน่งไฟล์ใหม่ในโฟลเดอร์ images/room_gallery/
            $newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];

            // ย้ายไฟล์รูปภาพจากตำแหน่งชั่วคราวไปยังตำแหน่งใหม่
            if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                // เพิ่มข้อมูลรูปภาพแกลเลอรี่ในตาราง rooms_gallery
                $sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$last_id."','".$_FILES['room_gallery']['name'][$i]."')";
                mysqli_query($con,$sql_detail);
            }
        }
    }
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('เพิ่มข้อมูลเรียบร้อย');
        window.location.href='manage_room.php?apartments_id=$apartments_id';
    </script>"); 
}

//ฟังก์ชัน editRoom() ใช้สำหรับ แก้ไขข้อมูลห้องที่มีอยู่แล้วในฐานข้อมูล.
function editRoom($id,$apartments_id,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category){

	global $con;

	// ตรวจสอบว่ามีการอัพโหลดรูปภาพห้องใหม่หรือไม่
	if($room_image != null){
		// ถ้ามีรูปภาพห้องใหม่ ให้ย้ายไฟล์จากโฟลเดอร์ชั่วคราวไปยังโฟลเดอร์ images/room/
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{
			// อัพเดทข้อมูลห้องรวมถึงชื่อไฟล์ภาพใหม่ในฐานข้อมูล
			$sql="UPDATE rooms SET apartments_id='".$apartments_id."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);
		}
	} else {
		// ถ้าไม่มีรูปภาพห้องใหม่ ให้แก้ไขข้อมูลห้องในฐานข้อมูลโดยไม่เปลี่ยนแปลงภาพ
		$sql="UPDATE rooms SET apartments_id='".$apartments_id."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."' WHERE id = '".$id."'";
		mysqli_query($con,$sql);
	}

	// ถ้ามีการอัพโหลดรูปภาพแกลเลอรี่มากกว่า 1 รูป
	if($total > 1){
		// ลบข้อมูลรูปภาพเก่าทั้งหมดจากตาราง rooms_gallery
		mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
		
		// ลูปเพื่ออัพโหลดรูปภาพแกลเลอรี่ใหม่
		for( $i=0 ; $i < $total ; $i++ ) {
			$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];

			// ถ้ามีไฟล์ภาพในตำแหน่งชั่วคราว
			if ($tmpFilePath != ""){
				// ย้ายไฟล์ภาพไปยังโฟลเดอร์ images/room_gallery/
				$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					// บันทึกข้อมูลรูปภาพใหม่ลงในตาราง rooms_gallery
					$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$id."','".$_FILES['room_gallery']['name'][$i]."')";
					mysqli_query($con,$sql_detail);
				}
			}
		}
	}
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 

}


//ฟังก์ชัน deleteRoom   เพื่อใช้ลบข้อมูลห้อง โดยรับค่าพารามิเตอร์สองค่า  id  และ apartments_id 
function deleteRoom($id,$apartments_id){
	global $con;

	// ลบข้อมูลห้องจากตาราง rooms โดยอ้างอิงจาก id ของห้องที่ต้องการลบ
	mysqli_query($con,"DELETE FROM rooms WHERE id='".$id."'");

	// ลบข้อมูลรูปภาพของห้องจากตาราง rooms_gallery โดยอ้างอิงจาก id ของห้องที่ถูกลบ
	mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 
}

//ฟังก์ชัน openRoom  เพื่อ "เปิด" ห้อง โดยอัปเดตสถานะของห้องในฐานข้อมูลเป็น '1' 
function openRoom($id,$apartments_id){
	global $con;

	// สร้างคำสั่ง SQL เพื่ออัปเดตสถานะของห้องเป็น '1' (เปิดห้อง) โดยอ้างอิงจาก id ของห้อง
	$sql = "UPDATE rooms SET room_status='1' WHERE id = '".$id."'";

	// ส่งคำสั่ง SQL ไปที่ฐานข้อมูลเพื่ออัปเดตสถานะของห้อง
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 
}

//ฟังก์ชัน closeRoom  เพื่อ "ปิด" ห้อง โดยอัปเดตสถานะของห้องในฐานข้อมูลเป็น '2' 
function closeRoom($id,$apartments_id){
	global $con;
	// สร้างคำสั่ง SQL เพื่ออัปเดตสถานะของห้องเป็น '2' (ปิดห้อง) โดยอ้างอิงจาก id ของห้อง
	$sql = "UPDATE rooms SET room_status='2' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 

}

function getAllRoomInApartment($apartments_id,$users_id){
	global $con;

	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."' AND r.users_id = '".$users_id."' AND r.room_status = '1'  
	ORDER BY r.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['rid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

// ดึงข้อมูลอพาร์ตเมนต์ของผู้ใช้ตาม users_id,apartments_id
function getAllRoomInApartmentt($apartments_id,$users_id){
	
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องทั้งหมด โดยเชื่อมตาราง rooms (r) และ apartments (a) 
	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."' AND r.users_id = '".$users_id."'
	ORDER BY r.id DESC";//DESC เรียงลำดับจากใหม่ไปเก่า เพื่อให้ข้อมูลห้องที่ถูกสร้างล่าสุดขึ้นมาก่อน

	// สั่งให้ query ข้อมูลจากฐานข้อมูล
	$res = mysqli_query($con,$sql);

	// ประกาศตัวแปร $data เป็น array เพื่อเก็บข้อมูลที่ได้จากการ query
	$data = array();

	// วนลูปเพื่อดึงข้อมูลแต่ละแถวจากผลลัพธ์ที่ได้
	while($row = mysqli_fetch_assoc($res)) {
		// เก็บข้อมูลแต่ละแถวในรูปแบบ array 
		$namesArray[] = array(
			'id' => $row['rid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	// กำหนดค่าให้ตัวแปร $data ด้วย array ข้อมูลที่ได้จากการ query
	$data = $namesArray;
	return $data;
	mysqli_close($con);
}

//งก์ชัน getAllRoomInApartmentOpen($apartments_id) คือฟังก์ชันที่ใช้สำหรับดึงข้อมูลห้องที่เปิดให้เช่า
function getAllRoomInApartmentOpen($apartments_id){
	global $con;

 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องที่เปิดให้เช่าจากตาราง rooms และ apartments
	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."' AND r.room_status = '1' AND r.room_category = '1' 
	ORDER BY r.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	 // วนลูปผ่านผลลัพธ์ที่ได้จากการ query
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['rid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังก์ชันนี้ใช้ในการดึงข้อมูลรูปภาพทั้งหมดจากแกลเลอรี่ของห้องที่มี rooms_id
function getAllRoomGallery($rooms_id){
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง rooms_gallery โดยเลือกแกลเลอรี่ที่มี rooms_id ตรงกับ $rooms_id
	$sql = "SELECT * FROM rooms_gallery WHERE rooms_id = '".$rooms_id."' ORDER BY id ASC"; //จัดเรียงข้อมูลตาม id จากน้อยไปมาก
	$res = mysqli_query($con,$sql);
	$data = array();

	// วนลูปเพื่อดึงข้อมูลแต่ละแถวจากผลลัพธ์และเก็บใน array
	while($row = mysqli_fetch_assoc($res)) {
		// สร้าง array ย่อยเพื่อเก็บข้อมูลของแต่ละแถว
		$namesArray[] = array(
			'id' => $row['aid'], 
			'rooms_id' => $row['rooms_id'], 
			'images' => $row['images'] 
		);
	}

	$data = $namesArray;
	return $data;
	mysqli_close($con);

}


// ดึงข้อมูลของห้อง (room)
function getCurrentRoom($id){

    global $con;

    // รันคำสั่ง SQL เพื่อดึงข้อมูลห้องที่มี id ตรงกับ $id
    // โดยเชื่อมตาราง `rooms` (r) กับตาราง `apartments` (a) ตาม `apartments_id`
    $res = mysqli_query($con,"SELECT *,r.id as rid 
    	FROM rooms r 
    	LEFT JOIN users u ON r.users_id = u.id 
    	LEFT JOIN apartments a ON r.apartments_id = a.id WHERE r.id = '".$id."'");

    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;
    mysqli_close($con);

}



function saveBooking($rooms_id,$users_id,$booking_name,$booking_phone,$booking_email){
	
	global $con;

	$yThai = date("Y")+543;
	$dateNow = $yThai.date("-m-d");

	$sql = "INSERT INTO bookings (users_id, rooms_id, booking_name, booking_phone, booking_email, booking_date, booking_status) VALUES('".$users_id."','".$rooms_id."','".$booking_name."','".$booking_phone."','".$booking_email."','".$dateNow."','1')";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ส่งคำขอการจองเรียบร้อย');
		window.location.href='history_booking.php';
		</script>"); 
	
}

//ฟังก์ชัน getAllUserBooking($users_id) คือฟังก์ชันที่ใช้ในการดึงข้อมูลการจองห้องของผู้ใช้
function getAllUserBooking($users_id){
	global $con;

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลการจองห้องของผู้ใช้
	$sql = "SELECT *,b.id as bid 
	FROM bookings b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE b.users_id = '".$users_id."' 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'],
			'booking_phone' => $row['booking_phone'],
			'booking_email' => $row['booking_email'],
			'booking_date' => $row['booking_date'],
			'booking_status' => $row['booking_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}
 
 // ฟังก์ชันนี้ใช้ในการดึงข้อมูลการจองห้องทั้งหมดที่มีสถานะการจองเป็น '1' (รอดำเนินการ)
function getAllUserBookingPendingStatus($users_id){
	global $con;

	//ดึงข้อมูลจากตาราง apartments, rooms และ bookings ตามผู้ใช้ที่ส่งเข้ามา (users_id)
	$sql = "SELECT *,b.id as bid 
	FROM apartments a 
	LEFT JOIN rooms r ON a.id = r.apartments_id 
	LEFT JOIN bookings b ON r.id = b.rooms_id 
	WHERE a.users_id = '".$users_id."' AND b.booking_status = '1'
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	// สร้าง array เพื่อเก็บข้อมูลที่ดึงมา
	$data = array();
	
	// ลูปข้อมูลจากผลลัพธ์การดึงข้อมูล
	while($row = mysqli_fetch_assoc($res)) {
		// เก็บข้อมูลแต่ละแถวไว้ใน array 
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'],
			'booking_phone' => $row['booking_phone'],
			'booking_email' => $row['booking_email'],
			'booking_date' => $row['booking_date'],
			'booking_status' => $row['booking_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;
	return $data;
	mysqli_close($con);
}

//ฟังก์ชันนี้ใช้ในการดึงข้อมูลการจองห้องที่มี id
function getCurrentBooking($id){

	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลการจอง (bookings) โดยเชื่อมกับตาราง rooms และ apartments 
	// โดยเลือกการจองที่มี id ตรงกับ $id ที่ส่งเข้ามา
	$sql = "SELECT *,b.id as bid 
	FROM bookings b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE b.id = '".$id."' 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

//ฟังก์ชันนี้ใช้ในการอัปเดตสถานะของการจองในฐานข้อมูล
function updateBookingStatus($booking_id,$booking_status){
	global $con;

	//ใช้คำสั่ง SQL เพื่ออัปเดตค่าของ booking_status ในตาราง bookings โดยระบุ id ของการจองที่ต้องการแก้ไข.
	$sql = "UPDATE bookings SET booking_status='".$booking_status."' WHERE id = '".$booking_id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		// เปลี่ยนเส้นทางไปยังหน้า manage_pending.php หลังจากการบันทึกสำเร็จ
		window.location.href='manage_pending.php';
		</script>"); 

}

//ฟังชั่นเพื่อใช้ในการดึงข้อมูลที่เกี่ยวข้องกับการจองที่ยังไม่ได้รับการยืนยัน
function getCheckNewPending($users_id){

    global $con;

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลจำนวนการจองที่อยู่ในสถานะรอการยืนยัน
    $sql = "SELECT COUNT(*) as numBook 
            FROM apartments a 
            LEFT JOIN rooms r ON a.id = r.apartments_id 
            LEFT JOIN bookings b ON r.id = b.rooms_id 
            WHERE a.users_id = '".$users_id."' AND b.booking_status = '1'
            ORDER BY b.id DESC";

    // ทำการเรียกใช้งานคำสั่ง SQL
    $res = mysqli_query($con,$sql);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);

    return $result;
    mysqli_close($con);
}


//ฟังก์ชัน getAllUserBookingApartmentใช้ดึงข้อมูลการจองของผู้ใช้ตาม users_id
function getAllUserBookingApartment($users_id){
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลการจองของผู้ใช้ตาม users_id
	$sql = "SELECT *,b.id as bid 
	FROM bookings b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE a.users_id = '".$users_id."'
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	// วนลูปเพื่อเก็บข้อมูลแต่ละแถวในรูปแบบอาเรย์
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'], 
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'], 
			'booking_phone' => $row['booking_phone'], 
			'booking_email' => $row['booking_email'], 
			'booking_date' => $row['booking_date'], 
			'booking_status' => $row['booking_status'], 
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'], 
			'bed_type' => $row['bed_type'], 
			'room_type' => $row['room_type'], 
			'room_price' => $row['room_price'], 
			'room_rent' => $row['room_rent'], 
			'room_detail' => $row['room_detail'], 
			'room_image' => $row['room_image'], 
			'room_status' => $row['room_status'], 
			'users_id' => $row['users_id'], 
			'apart_address' => $row['apart_address'], 
			'apart_type' => $row['apart_type'], 
			'apart_name' => $row['apart_name'], 
			'apart_number' => $row['apart_number'], 
			'apart_class' => $row['apart_class'], 
			'apart_elevator' => $row['apart_elevator'], 
			'apart_contract' => $row['apart_contract'], 
			'apart_lat' => $row['apart_lat'], 
			'apart_lng' => $row['apart_lng'], 
			'apart_detail' => $row['apart_detail'], 
			'apart_image' => $row['apart_image'] 
		);
	}

	$data = $namesArray; 

	return $data; 
	mysqli_close($con); 
}

//ฟังกืชั่นดึงข้อมูลผู้ใช้งานที่จองห้องเช่า
function getAllBookingApartment(){
	global $con;
	 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลการจองทั้งหมด โดยใช้ JOIN กับตารางห้องและอพาร์ตเมนต์
	$sql = "SELECT *,b.id as bid 
	FROM  bookings b  
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'],
			'booking_phone' => $row['booking_phone'],
			'booking_email' => $row['booking_email'],
			'booking_date' => $row['booking_date'],
			'booking_status' => $row['booking_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังชั่นบันทึกข้อมูลห้อง
function saveRoomRental($apartment, $room_name, $bed_type, $room_type, $room_price, $room_rent, $room_detail, $room_image, $room_gallery, $total, $users_id, $room_category, $room_remark, $contract_file, $room_lat, $room_lng) {

    global $con; 

    // ตรวจสอบว่ามีการอัปโหลดภาพห้องและไฟล์สัญญาหรือไม่
    if ($room_image != null && $contract_file != null) {
        // ย้ายไฟล์ภาพห้องและไฟล์สัญญาไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
            // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลห้องเช่า
            $sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark, contract_file, room_lat, room_lng) VALUES('" . $apartment . "','" . $users_id . "','" . $room_name . "','" . $bed_type . "','" . $room_type . "','" . $room_price . "','" . $room_rent . "','" . $room_detail . "','" . $_FILES["room_image"]["name"] . "','1','" . $room_category . "','" . $room_remark . "','" . $_FILES["contract_file"]["name"] . "','" . $room_lat . "','" . $room_lng . "')";
            mysqli_query($con, $sql);
            $last_id = $con->insert_id; // เก็บ ID ของห้องเช่าที่เพิ่งเพิ่ม
        }
    // ตรวจสอบกรณีที่มีเพียงภาพห้อง
    } else if ($room_image != null && $contract_file == null) {
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"])) {
        	 // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลห้องเช่าและภาพห้อง
            $sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark, room_lat, room_lng) VALUES('" . $apartment . "','" . $users_id . "','" . $room_name . "','" . $bed_type . "','" . $room_type . "','" . $room_price . "','" . $room_rent . "','" . $room_detail . "','" . $_FILES["room_image"]["name"] . "','1','" . $room_category . "','" . $room_remark . "','" . $room_lat . "','" . $room_lng . "')";
            mysqli_query($con, $sql);
            $last_id = $con->insert_id;
        }
    // ตรวจสอบกรณีที่มีเพียงไฟล์สัญญา
    } else if ($room_image == null && $contract_file != null) {
        if (move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
        	// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลห้องเช่าและไฟล์สัญญา
            $sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category, room_remark, contract_file, room_lat, room_lng) VALUES('" . $apartment . "','" . $users_id . "','" . $room_name . "','" . $bed_type . "','" . $room_type . "','" . $room_price . "','" . $room_rent . "','" . $room_detail . "','1','" . $room_category . "','" . $room_remark . "','" . $_FILES["contract_file"]["name"] . "','" . $room_lat . "','" . $room_lng . "')";
            mysqli_query($con, $sql);
            $last_id = $con->insert_id;
        }
    // กรณีที่ไม่มีการอัปโหลดไฟล์ใด ๆ
    } else {
    	// สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลห้องเช่า
        $sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark) VALUES('" . $apartment . "','" . $users_id . "','" . $room_name . "','" . $bed_type . "','" . $room_type . "','" . $room_price . "','" . $room_rent . "','" . $room_detail . "','1','" . $room_category . "','" . $room_remark . "')";
        mysqli_query($con, $sql);
        $last_id = $con->insert_id;
    }

    // จัดการกับภาพในห้องภาพ (gallery)
    for ($i = 0; $i < $total; $i++) {
        $tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i]; // เก็บเส้นทางชั่วคราวของไฟล์

        if ($tmpFilePath != "") {
            $newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i]; // เส้นทางใหม่สำหรับไฟล์

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                // เพิ่มภาพลงในฐานข้อมูล
                $sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('" . $last_id . "','" . $_FILES['room_gallery']['name'][$i] . "')";
                mysqli_query($con, $sql_detail);
            }
        }
    }

    mysqli_close($con); 
    echo ("<script language='JavaScript'>
        alert('เพิ่มข้อมูลเรียบร้อย');
        window.location.href='manage_user_room.php';
        </script>"); 
}

//ฟังก์ชั่นแก้ไขข้อมูลห้อง
function editRoomRental($id, $apartment, $room_name, $bed_type, $room_type, $room_price, $room_rent, $room_detail, $room_image, $room_gallery, $total, $users_id, $room_category, $room_remark, $contract_file, $room_lat, $room_lng) {

    global $con; 
    // ตรวจสอบว่ามีการอัปโหลดภาพห้องและไฟล์สัญญาหรือไม่
    if ($room_image != null && $contract_file != null) {
        // ย้ายไฟล์ภาพห้องและไฟล์สัญญาไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
            // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูลห้องเช่า
            $sql = "UPDATE rooms SET apartment='" . $apartment . "', users_id='" . $users_id . "', room_name='" . $room_name . "', bed_type='" . $bed_type . "', room_type='" . $room_type . "', room_price='" . $room_price . "', room_rent='" . $room_rent . "', room_detail='" . $room_detail . "', room_image='" . $_FILES["room_image"]["name"] . "', room_category='" . $room_category . "', room_remark='" . $room_remark . "', contract_file='" . $_FILES["contract_file"]["name"] . "', room_lat='" . $room_lat . "', room_lng='" . $room_lng . "' WHERE id = '" . $id . "'";
            mysqli_query($con, $sql);
        }
    // ตรวจสอบกรณีที่มีเพียงภาพห้อง
    } else if ($room_image != null && $contract_file == null) {
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"])) {
            $sql = "UPDATE rooms SET apartment='" . $apartment . "', users_id='" . $users_id . "', room_name='" . $room_name . "', bed_type='" . $bed_type . "', room_type='" . $room_type . "', room_price='" . $room_price . "', room_rent='" . $room_rent . "', room_detail='" . $room_detail . "', room_image='" . $_FILES["room_image"]["name"] . "', room_category='" . $room_category . "', room_remark='" . $room_remark . "', room_lat='" . $room_lat . "', room_lng='" . $room_lng . "' WHERE id = '" . $id . "'";
            mysqli_query($con, $sql);
        }
    // ตรวจสอบกรณีที่มีเพียงไฟล์สัญญา
    } else if ($room_image == null && $contract_file != null) {
        if (move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
            $sql = "UPDATE rooms SET apartment='" . $apartment . "', users_id='" . $users_id . "', room_name='" . $room_name . "', bed_type='" . $bed_type . "', room_type='" . $room_type . "', room_price='" . $room_price . "', room_rent='" . $room_rent . "', room_detail='" . $room_detail . "', room_category='" . $room_category . "', room_remark='" . $room_remark . "', contract_file='" . $_FILES["contract_file"]["name"] . "', room_lat='" . $room_lat . "', room_lng='" . $room_lng . "' WHERE id = '" . $id . "'";
            mysqli_query($con, $sql);
        }
    // กรณีที่ไม่มีการอัปโหลดไฟล์ใด ๆ
    } else {
        $sql = "UPDATE rooms SET apartment='" . $apartment . "', users_id='" . $users_id . "', room_name='" . $room_name . "', bed_type='" . $bed_type . "', room_type='" . $room_type . "', room_price='" . $room_price . "', room_rent='" . $room_rent . "', room_detail='" . $room_detail . "', room_category='" . $room_category . "', room_remark='" . $room_remark . "' WHERE id = '" . $id . "'";
        mysqli_query($con, $sql);
    }

    // ถ้ามีห้องภาพ (gallery) มากกว่าหนึ่งภาพ
    if ($total > 1) {
        // ลบภาพเก่าจากฐานข้อมูล
        mysqli_query($con, "DELETE FROM rooms_gallery WHERE rooms_id = '" . $id . "'");
        for ($i = 0; $i < $total; $i++) {
            $tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i]; // เก็บเส้นทางชั่วคราวของไฟล์

            if ($tmpFilePath != "") {
                $newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i]; // เส้นทางใหม่สำหรับไฟล์

                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    // เพิ่มภาพใหม่ลงในฐานข้อมูล
                    $sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('" . $id . "','" . $_FILES['room_gallery']['name'][$i] . "')";
                    mysqli_query($con, $sql_detail);
                }
            }
        }
    }

    mysqli_close($con); 
    echo ("<script language='JavaScript'>
        alert('แก้ไขข้อมูลเรียบร้อย');
        window.location.href='manage_user_room.php';
        </script>"); 
}


//ฟังก์ชัน deleteRoomRental($id) ทำหน้าที่ลบข้อมูลห้องเช่าจากฐานข้อมูล 
function deleteRoomRental($id) {
    global $con;

    // ลบข้อมูลห้องจากตาราง rooms โดยใช้ ID ที่ส่งมา
    mysqli_query($con, "DELETE FROM rooms WHERE id='" . $id . "'");

    // ลบข้อมูลแกลลอรีของห้องจากตาราง rooms_gallery โดยใช้ rooms_id ที่ตรงกัน
    mysqli_query($con, "DELETE FROM rooms_gallery WHERE rooms_id = '" . $id . "'");

    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('ลบข้อมูลเรียบร้อยแล้ว');
        window.location.href='manage_user_room.php';
    </script>"); 
}

//ฟังก์ชัน openRoomRental ทำหน้าที่เปิดห้องเช่า
function openRoomRental($id) {
    global $con;

    // สร้างคำสั่ง SQL เพื่ออัปเดตสถานะห้องให้เป็น "เปิด" (1)
    $sql = "UPDATE rooms SET room_status='1' WHERE id = '" . $id . "'";

    mysqli_query($con, $sql);

    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('บันทึกข้อมูลเรียบร้อย');
        window.location.href='manage_user_room.php';
    </script>"); 
}

//ฟังก์ชัน closeRoomRental ทำหน้าที่ปิดห้องเช่า
function closeRoomRental($id){
	global $con;
	// สร้างคำสั่ง SQL เพื่ออัปเดตสถานะห้องให้เป็น "ปิด" (2)
	$sql = "UPDATE rooms SET room_status='2' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_user_room.php';
		</script>"); 

}

//ฟังก์ชัน getAllRentalRoomใช้สำหรับดึงข้อมูลห้องเช่าทั้งหมดที่เป็นของผู้ใช้ที่มี users_id
function getAllRentalRoom($users_id) {
	global $con; 

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องที่เชื่อมโยงกับผู้ใช้
	$sql = "SELECT *, r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.users_id = '".$users_id."'  
	ORDER BY r.id DESC"; 

	$res = mysqli_query($con, $sql); 

	$data = array(); 
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			
			'id' => $row['rid'], 
			'apartments_id' => $row['apartments_id'], 
			'room_name' => $row['room_name'], 
			'bed_type' => $row['bed_type'], 
			'room_type' => $row['room_type'], 
			'room_price' => $row['room_price'], 
			'room_rent' => $row['room_rent'], 
			'room_detail' => $row['room_detail'], 
			'room_image' => $row['room_image'], 
			'room_status' => $row['room_status'], 
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'], 
			'apart_type' => $row['apart_type'], 
			'apart_name' => $row['apart_name'], 
			'apartment' => $row['apartment'], 
			'apart_number' => $row['apart_number'], 
			'apart_class' => $row['apart_class'], 
			'apart_elevator' => $row['apart_elevator'], 
			'apart_contract' => $row['apart_contract'], 
			'apart_lat' => $row['apart_lat'], 
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'], 
			'apart_image' => $row['apart_image']
		);
	}

	$data = $namesArray;

	return $data; 
	mysqli_close($con); 
}

function getAllRoommate(){
	global $con;

	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.room_category = '2'  
	ORDER BY r.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['rid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังก์ชัน getAllRoommateFinddingคือฟังก์ชันที่ใช้เพื่อดึงข้อมูลห้องแชร์ (roommate finding)
function getAllRoommateFindding($users_id){
	global $con;

	// รันคำสั่ง SQL เพื่อดึงข้อมูลจากตาราง questionaires_finding ที่มี users_id ตรงกับที่ระบุ
	$res = mysqli_query($con,"select * from questionaires_finding where users_id = '".$users_id."' ");
	
	// วนลูปผ่านผลลัพธ์เพื่อดึงค่าจำนวนรวม (totals)
	while($row = mysqli_fetch_array($res)) {
		$data['totals'] = $row['totals'];
	}


    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องที่เป็นห้องแชร์ (room_category = '2')
    // และคะแนนห้องต้องน้อยกว่าหรือเท่ากับ totals ที่ได้จากการ query ก่อนหน้า
    // ห้องต้องเปิดให้เช่า (room_status = '1')
	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.room_category = '2' AND r.room_score <= '".$data['totals']."' AND r.room_status = '1'
	ORDER BY r.room_score DESC"; 
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['rid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_score' => $row['room_score'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

function saveRequest($rooms_id,$users_id,$req_name,$req_phone,$req_email){
	
	global $con;

	$yThai = date("Y")+543;
	$dateNow = $yThai.date("-m-d");

	$sql = "INSERT INTO requests (rooms_id, users_id, req_name, req_phone, req_email, request_status) VALUES('".$rooms_id."','".$users_id."','".$req_name."','".$req_phone."','".$req_email."','1')";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ส่งคำขอเรียบร้อย');
		window.location.href='history_request.php';
		</script>"); 
	
}

//ฟังก์ชัน getAllUserRequestคือฟังก์ชันที่ใช้เพื่อดึงข้อมูลคำขอทั้งหมดของผู้ใช้
function getAllUserRequest($users_id){
	global $con;

   // สร้างคำสั่ง SQL เพื่อดึงข้อมูลคำขอจากตาราง rooms, apartments, requests, และ users
	$sql = "SELECT *,q.id as qid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN requests q ON r.id = q.rooms_id 
	LEFT JOIN users u ON q.users_id = u.id
	WHERE r.users_id = '".$users_id."'
	ORDER BY q.id ASC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['qid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apartment' => $row['apartment'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'request_status' => $row['request_status'],
			'profile_img' => $row['profile_img'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังก์ชัน getAllUserRequestStatus มีหน้าที่ดึงข้อมูลคำร้องขอ (requests)
function getAllUserRequestStatus($users_id){
	global $con;

	 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้อง, อพาร์ตเมนต์, และคำร้องขอ
    $sql = "SELECT *, q.id as qid 
    FROM rooms r 
    LEFT JOIN apartments a ON r.apartments_id = a.id 
    LEFT JOIN requests q ON r.id = q.rooms_id 
    LEFT JOIN users u ON q.users_id = u.id
    WHERE r.users_id = '".$users_id."' AND q.request_status = '1'  
    ORDER BY q.id DESC";
    $res = mysqli_query($con, $sql);

    $data = array(); // สร้างอาร์เรย์เพื่อเก็บข้อมูลที่ดึงมา
    while ($row = mysqli_fetch_assoc($res)) {
        $namesArray[] = array(
            'id' => $row['qid'],
            'apartments_id' => $row['apartments_id'],
            'room_name' => $row['room_name'],
            'bed_type' => $row['bed_type'],
            'room_type' => $row['room_type'],
            'room_price' => $row['room_price'],
            'room_rent' => $row['room_rent'],
            'room_detail' => $row['room_detail'],
            'room_image' => $row['room_image'],
            'room_status' => $row['room_status'],
            'users_id' => $row['users_id'],
            'apart_address' => $row['apart_address'],
            'apart_type' => $row['apart_type'],
            'apart_name' => $row['apart_name'],
            'apartment' => $row['apartment'],
            'apart_number' => $row['apart_number'],
            'apart_class' => $row['apart_class'],
            'apart_elevator' => $row['apart_elevator'],
            'apart_contract' => $row['apart_contract'],
            'apart_lat' => $row['apart_lat'],
            'apart_lng' => $row['apart_lng'],
            'username' => $row['username'],
            'password' => $row['password'],
            'address' => $row['address'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'gender' => $row['gender'],
            'status' => $row['status'],
            'role' => $row['role'],
            'request_status' => $row['request_status'],
            'profile_img' => $row['profile_img'],
            'apart_detail' => $row['apart_detail'],
            'apart_image' => $row['apart_image']
        );
    }

    $data = $namesArray;

    return $data; 
    mysqli_close($con); 
}

//ฟังก์ชัน getAllHistoryUserRequest($users_id) ทำหน้าที่ในการดึงประวัติการขอห้องของผู้ใช้
function getAllHistoryUserRequest($users_id){
	global $con;

  // สร้างคำสั่ง SQL เพื่อดึงประวัติการขอห้องของผู้ใช้
	$sql = "SELECT *,q.id as qid 
	FROM requests q 
	LEFT JOIN users u ON q.users_id = u.id 
	LEFT JOIN rooms r ON q.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE q.users_id = '".$users_id."' ORDER BY q.id ASC ";// ดึงข้อมูลที่เกี่ยวข้องและจัดเรียงตาม ID ของการขอห้องจากเก่าไปใหม่

	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['qid'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apartment' => $row['apartment'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'request_status' => $row['request_status'],
			'profile_img' => $row['profile_img'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}
//ฟังชั่นสำหรับห้องที่ผู้ใช้เป็นเจ้าของคำขอที่อยู่ในสถานะรอการยืนยัน
function getCheckNewRequest($users_id){

    global $con;

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลจำนวนคำขอ (requests) ที่อยู่ในสถานะรอการยืนยัน (request_status = '1')
    $sql = "SELECT COUNT(*) as numReq 
            FROM requests q 
            LEFT JOIN users u ON q.users_id = u.id 
            LEFT JOIN rooms r ON q.rooms_id = r.id 
            WHERE r.users_id = '".$users_id."' AND q.request_status = '1'";
    $res = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;
    mysqli_close($con);
}


//ฟังก์ชัน getCurrentRequest มีหน้าที่ดึงข้อมูลคำร้องขอ (request) 
function getCurrentRequest($id){

	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลคำร้องขอโดยใช้ id ที่ส่งมา
    $sql = "SELECT *, q.id as qid, q.users_id as qusers_id, q.rooms_id as rooms_id  
    FROM requests q 
    LEFT JOIN users u ON q.users_id = u.id 
    LEFT JOIN rooms r ON q.rooms_id = r.id 
    LEFT JOIN apartments a ON r.apartments_id = a.id 
    WHERE q.id = '".$id."' ";

    // $sql = "SELECT *, q.id as qid, q.rooms_id as rooms_id  
    // FROM requests q 
    // LEFT JOIN rooms r ON q.rooms_id = r.id 
    // LEFT JOIN users u ON r.users_id = u.id 
    // WHERE q.id = '".$id."' ";

    $res = mysqli_query($con, $sql);
    
    // ดึงข้อมูลแถวแรกและเก็บในรูปแบบอาร์เรย์
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;

    mysqli_close($con); 
}


//ฟังก์ชัน getCurrentRequestDataroomer มีหน้าที่ดึงข้อมูลของเจ้าของห้อง
function getCurrentRequestDataroomer($id) {

	global $con;

    $sql = "SELECT *, q.id as qid, q.rooms_id as rooms_id  
    FROM requests q 
    LEFT JOIN rooms r ON q.rooms_id = r.id 
    LEFT JOIN users u ON r.users_id = u.id 
    WHERE q.id = '".$id."' ";



    $res = mysqli_query($con, $sql);
    
    // ดึงข้อมูลแถวแรกและเก็บในรูปแบบอาร์เรย์
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;

    mysqli_close($con); 
}




//ฟังก์ชัน updateRequest คือฟังก์ชันที่ใช้สำหรับอัปเดตสถานะของคำขอในฐานข้อมูล
function updateRequest($requests_id,$request_status){
	global $con;

   // สร้างคำสั่ง SQL สำหรับอัพเดตสถานะของคำขอในตาราง requests
    $sql = "UPDATE requests SET request_status='" . $request_status . "' WHERE id = '" . $requests_id . "'";
    mysqli_query($con, $sql);
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('บันทึกข้อมูลเรียบร้อย');
        window.location.href='manage_request_roommate.php';
    </script>");
}
//ฟังก์ชันนี้ทำหน้าที่บันทึกข้อมูลห้องพักใหม่ลงในฐานข้อมูล
function saveRoomContract($apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$contract_year,$contract_end,$contract_file,$room_lat,$room_lng){

	global $con;

	// แปลงวันที่สิ้นสุดสัญญาจากรูปแบบ DD/MM/YYYY เป็น YYYY-MM-DD
	$arrDate1 = explode("/", $contract_end);
	$convert_contract_end = $arrDate1[2] . '-' . $arrDate1[1] . '-' . $arrDate1[0];
	$sqlCheck = "SELECT * FROM apartments WHERE apart_name = '".$apartment."'";
	$res = mysqli_query($con, $sqlCheck);
	
	// วนลูปเพื่อดึงข้อมูลผู้ใช้
	while($row = mysqli_fetch_array($res)) {
		$data['id'] = $row['id'];
		$data['apart_name'] = $row['apart_name'];
	}
	if(empty($data)){
		//$data['id'] = 0;
		$apartments_id = 0;
		$apar = $apartment;
	}else{
		$apartments_id = $data['id'];
		$apar = "";
	}

	// ตรวจสอบว่ามีการอัปโหลดรูปภาพห้องและไฟล์สัญญาหรือไม่
	if ($room_image != null && $contract_file != null) {
		// หากมีการอัปโหลดทั้งรูปภาพห้องและไฟล์สัญญา
		if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
			// เพิ่มข้อมูลห้องและไฟล์สัญญาลงในฐานข้อมูล
			$sql = "INSERT INTO rooms (apartments_id, apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, contract_year, contract_end, contract_file, room_lat, room_lng) VALUES('".$apartments_id."','".$apar."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";
		}
	} else if ($room_image == null && $contract_file != null) {
		// หากไม่มีการอัปโหลดรูปภาพห้อง แต่มีการอัปโหลดไฟล์สัญญา
		if (move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
			// เพิ่มข้อมูลห้องพร้อมไฟล์สัญญา
			$sql = "INSERT INTO rooms (apartments_id, apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category, contract_year, contract_end, contract_file, room_lat, room_lng) VALUES('".$apartments_id."','".$apar."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";
		}
	} else if ($room_image != null && $contract_file == null) {
		// หากมีการอัปโหลดรูปภาพห้อง แต่ไม่มีไฟล์สัญญา
		if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"])) {
			// เพิ่มข้อมูลห้องพร้อมรูปภาพห้อง
			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, contract_year, contract_end, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$room_lat."','".$room_lng."')";
		}
	} else {
		// หากไม่มีการอัปโหลดทั้งรูปภาพห้องและไฟล์สัญญา
		// เพิ่มข้อมูลห้องโดยไม่มีรูปภาพห้องและไฟล์สัญญา
		$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category, contract_year, contract_end, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$room_lat."','".$room_lng."')";
	}

	mysqli_query($con, $sql);

	// รับ ID ของห้องที่ถูกสร้างใหม่
	$last_id = $con->insert_id; 

	// อัปโหลดภาพในแกลลอรีห้อง
	for ($i = 0; $i < $total; $i++) {
		$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i]; // เส้นทางไฟล์ชั่วคราวของภาพแกลลอรี

		if ($tmpFilePath != "") {
			$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i]; // เส้นทางที่บันทึกภาพแกลลอรี

			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				// บันทึกภาพแกลลอรีของห้องลงในฐานข้อมูล
				$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$last_id."','".$_FILES['room_gallery']['name'][$i]."')";
				mysqli_query($con, $sql_detail); // รันคำสั่ง SQL เพื่อบันทึกภาพแกลลอรี
			}
		}
	}

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย'); 
		window.location.href='manage_contract.php';
		</script>");
}

//ฟังก์ชัน editRoomContract ใช้สำหรับแก้ไขข้อมูลห้องพัก
function editRoomContract($id,$apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$contract_year,$contract_end,$contract_file,$room_lat,$room_lng){

  global $con; 

    // แปลงวันที่สิ้นสุดสัญญาจากรูปแบบ DD/MM/YYYY เป็น YYYY-MM-DD
    $arrDate1 = explode("/", $contract_end);
    $convert_contract_end = $arrDate1[2] . '-' . $arrDate1[1] . '-' . $arrDate1[0];

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพห้องและไฟล์สัญญาหรือไม่
    if ($room_image != null && $contract_file != null) {
        // หากมีการอัปโหลดทั้งรูปภาพห้องและไฟล์สัญญา
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
            // อัปเดทข้อมูลห้องรวมถึงชื่อไฟล์ภาพและไฟล์สัญญาในฐานข้อมูล
            $sql = "UPDATE rooms SET apartment='".$apartment."', users_id='".$users_id."', room_name='".$room_name."', bed_type='".$bed_type."', room_type='".$room_type."', room_price='".$room_price."', room_rent='".$room_rent."', room_detail='".$room_detail."', room_image='".$_FILES["room_image"]["name"]."', room_category='".$room_category."', contract_year='".$contract_year."', contract_end='".$convert_contract_end."', contract_file='".$_FILES["contract_file"]["name"]."', room_lat='".$room_lat."', room_lng='".$room_lng."' WHERE id = '".$id."'";
            mysqli_query($con, $sql);
        }
    } else if ($room_image == null && $contract_file != null) {
        // หากไม่มีการอัปโหลดรูปภาพห้อง แต่มีไฟล์สัญญา
        if (move_uploaded_file($_FILES["contract_file"]["tmp_name"], "images/contract/" . $_FILES["contract_file"]["name"])) {
            // อัปเดทข้อมูลห้องพร้อมไฟล์สัญญาในฐานข้อมูล
            $sql = "UPDATE rooms SET apartment='".$apartment."', users_id='".$users_id."', room_name='".$room_name."', bed_type='".$bed_type."', room_type='".$room_type."', room_price='".$room_price."', room_rent='".$room_rent."', room_detail='".$room_detail."', room_category='".$room_category."', contract_year='".$contract_year."', contract_end='".$convert_contract_end."', contract_file='".$_FILES["contract_file"]["name"]."', room_lat='".$room_lat."', room_lng='".$room_lng."' WHERE id = '".$id."'";
            mysqli_query($con, $sql);
        }
    } else if ($room_image != null && $contract_file == null) {
        // หากมีการอัปโหลดรูปภาพห้อง แต่ไม่มีไฟล์สัญญา
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], "images/room/" . $_FILES["room_image"]["name"])) {
            // อัปเดทข้อมูลห้องพร้อมรูปภาพห้องในฐานข้อมูล
            $sql = "UPDATE rooms SET apartment='".$apartment."', users_id='".$users_id."', room_name='".$room_name."', bed_type='".$bed_type."', room_type='".$room_type."', room_price='".$room_price."', room_rent='".$room_rent."', room_detail='".$room_detail."', room_image='".$_FILES["room_image"]["name"]."', room_category='".$room_category."', contract_year='".$contract_year."', contract_end='".$convert_contract_end."', room_lat='".$room_lat."', room_lng='".$room_lng."' WHERE id = '".$id."'";
            mysqli_query($con, $sql);
        }
    } else {
        // หากไม่มีการอัปโหลดทั้งรูปภาพห้องและไฟล์สัญญา
        // อัปเดทข้อมูลห้องโดยไม่ต้องอัปโหลดไฟล์ใหม่
        $sql = "UPDATE rooms SET apartment='".$apartment."', users_id='".$users_id."', room_name='".$room_name."', bed_type='".$bed_type."', room_type='".$room_type."', room_price='".$room_price."', room_rent='".$room_rent."', room_detail='".$room_detail."', room_category='".$room_category."', contract_year='".$contract_year."', room_lat='".$room_lat."', room_lng='".$room_lng."' WHERE id = '".$id."'";
        mysqli_query($con, $sql);
    }

    // หากมีการอัปโหลดรูปภาพในแกลลอรีมากกว่า 1 รูป
    if ($total > 1) {
        // ลบรูปภาพในแกลลอรีเดิมออกจากฐานข้อมูล
        mysqli_query($con, "DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
          // อัปโหลดรูปภาพใหม่ในแกลลอรี
		for( $i=0 ; $i < $total ; $i++ ) {
			$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					// เพิ่มรูปภาพแกลลอรีใหม่ลงในฐานข้อมูล
					$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$id."','".$_FILES['room_gallery']['name'][$i]."')";
					mysqli_query($con,$sql_detail);

				}
			}
		}
	}

	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='manage_contract.php';
		</script>"); 

}

//ฟังก์ชัน deleteContract ใช้สำหรับลบข้อมูลสัญญาหรือห้องเช่าที่มี id
function deleteContract($id){
    global $con; 

    // ลบข้อมูลห้องจากตาราง rooms โดยใช้ ID ที่ระบุ
    mysqli_query($con,"DELETE FROM rooms WHERE id='".$id."'");

    // ลบข้อมูลภาพในตาราง rooms_gallery ที่เชื่อมโยงกับห้องนั้น
    mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
    mysqli_close($con);

    echo ("<script language='JavaScript'>
        alert('ลบข้อมูลเรียบร้อยแล้ว');
        window.location.href='manage_contract.php';
        </script>"); 
}


//ฟังก์ชัน openContract ใช้สำหรับเปิดสัญญาหรือห้องเช่าที่มี id
function openContract($id){
    global $con; 

    // อัปเดตสถานะห้องให้เป็น '1' เปิด โดยใช้ ID ที่ระบุ
    $sql = "UPDATE rooms SET room_status='1' WHERE id = '".$id."'";
    mysqli_query($con,$sql);
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('บันทึกข้อมูลเรียบร้อย');
        window.location.href='manage_contract.php';
        </script>"); 
}

//ฟังก์ชัน closeContract ใช้สำหรับปิดสัญญาหรือห้องเช่าที่มี id
function closeContract($id){
    global $con; 

    // อัปเดตสถานะห้องให้เป็น '2' ปิด โดยใช้ ID ที่ระบุ
    $sql = "UPDATE rooms SET room_status='2' WHERE id = '".$id."'";

    mysqli_query($con,$sql);
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('บันทึกข้อมูลเรียบร้อย');
        window.location.href='manage_contract.php';
        </script>"); 
}

//ฟังก์ชัน getAllContract() คือฟังก์ชันที่ใช้สำหรับดึงข้อมูลห้องเช่า
function getAllContract(){
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องที่มีประเภทเป็น "ห้องเช่า" (room_category = '3')
    // และสถานะห้องต้องเปิดให้เช่า (room_status = '1')
	$sql = "SELECT * 
	FROM rooms 
	WHERE room_category = '3' AND room_status = '1' 
	ORDER BY id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['id'],
			'apartments_id' => $row['apartments_id'],
			'apartment' => $row['apartment'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังชั่นบันทึกข้อมูลการซื้อห้องพัก
function saveBuy($rooms_id,$users_id,$buy_name,$buy_phone,$buy_email){
	
	global $con;

	$yThai = date("Y")+543;
	$dateNow = $yThai.date("-m-d");

	$sql = "INSERT INTO buys (users_id, rooms_id, buy_name, buy_phone, buy_email, buy_date, buy_status) VALUES('".$users_id."','".$rooms_id."','".$buy_name."','".$buy_phone."','".$buy_email."','".$dateNow."','1')";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ส่งคำขอการซื้อสัญญาเรียบร้อย');
		window.location.href='history_contract_finding.php';
		</script>"); 
	
}

//ฟังก์ชัน getAllUserBuyContractทำหน้าที่ดึงข้อมูลสัญญาการซื้อของผู้ใช้ที่มีรหัสระบุ ($users_id)
function getAllUserBuyContract($users_id){
	global $con;

	$sql = "SELECT *,b.id as bid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN buys b ON r.id = b.rooms_id 
	LEFT JOIN users u ON b.users_id = u.id
	WHERE r.users_id = '".$users_id."'
	ORDER BY b.id ASC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'buy_name' => $row['buy_name'],
			'buy_phone' => $row['buy_phone'],
			'buy_email' => $row['buy_email'],
			'apartment' => $row['apartment'],
			'buy_date' => $row['buy_date'],
			'buy_status' => $row['buy_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

// ฟังก์ชันนี้ทำหน้าที่ดึงข้อมูลการซื้อจากฐานข้อมูลตาม ID ของการซื้อที่กำหนด
function getCurrentBuy($id){
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลการซื้อที่มี ID ตรงกับที่กำหนด
	$sql = "SELECT *,b.id as bid,r.users_id as rusers_id 
	FROM buys b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE b.id = '".$id."' 
	ORDER BY b.id DESC";
	
	$res = mysqli_query($con,$sql);
	$result = mysqli_fetch_array($res, MYSQLI_ASSOC);
	
	return $result;
	mysqli_close($con);
}


// ฟังก์ชันสำหรับดึงข้อมูลคำร้องขอสัญญาที่ผู้ใช้ทำ
function getAllUserRequestContract($users_id) {
	global $con;

	// สร้างคำสั่ง SQL เพื่อดึงข้อมูลห้องพักและคำร้องซื้อที่เกี่ยวข้อง
	$sql = "SELECT *,b.id as bid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN buys b ON r.id = b.rooms_id 
	LEFT JOIN users u ON b.users_id = u.id
	WHERE r.users_id = '".$users_id."' AND b.buy_status = '1'  
	ORDER BY b.id ASC";

	$res = mysqli_query($con, $sql);

	$data = array();
	// วนลูปเพื่อดึงข้อมูลแต่ละแถวในผลลัพธ์
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'], 
			'apartments_id' => $row['apartments_id'], 
			'room_name' => $row['room_name'], 
			'bed_type' => $row['bed_type'], 
			'room_type' => $row['room_type'], 
			'room_price' => $row['room_price'], 
			'room_rent' => $row['room_rent'], 
			'room_detail' => $row['room_detail'], 
			'apartment' => $row['apartment'], 
			'room_image' => $row['room_image'], 
			'room_status' => $row['room_status'], 
			'users_id' => $row['users_id'], 
			'apart_address' => $row['apart_address'], 
			'apart_type' => $row['apart_type'], 
			'apart_name' => $row['apart_name'], 
			'apart_number' => $row['apart_number'], 
			'apart_class' => $row['apart_class'], 
			'apart_elevator' => $row['apart_elevator'], 
			'apart_contract' => $row['apart_contract'], 
			'apart_lat' => $row['apart_lat'], 
			'apart_lng' => $row['apart_lng'], 
			'buy_name' => $row['buy_name'], 
			'buy_phone' => $row['buy_phone'], 
			'address' => $row['address'], 
			'buy_email' => $row['buy_email'], 
			'phone' => $row['phone'], 
			'gender' => $row['gender'], 
			'status' => $row['status'], 
			'role' => $row['role'], 
			'buy_status' => $row['buy_status'], 
			'profile_img' => $row['profile_img'], 
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image'] 
		);
	}

	$data = $namesArray;

	return $data; 
	mysqli_close($con); 
}

//ฟังชั่นเพื่อใช้ในการดึงข้อมูลจากฐานข้อมูลที่เกี่ยวกับ "การซื้อ" ที่ยังไม่ได้รับการยืนยัน
function getCheckNewRequestBuy($users_id){

    global $con;

    // สร้างคำสั่ง SQL เพื่อดึงข้อมูลจำนวนการซื้อ (buys) ที่อยู่ในสถานะรอการยืนยัน (buy_status = '1')
    $sql = "SELECT COUNT(*) as numBuy 
            FROM buys b 
            LEFT JOIN rooms r ON b.rooms_id = r.id 
            WHERE r.users_id = '".$users_id."' AND b.buy_status = '1'";

    $res = mysqli_query($con, $sql);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;
    mysqli_close($con);
}


//ฟังก์ชั่นอัพเดตสถานะการซื้อ
function updateBuyStatus($buy_id, $buy_status) {
    global $con;

    // สร้างคำสั่ง SQL เพื่ออัปเดตสถานะการซื้อในฐานข้อมูล
    $sql = "UPDATE buys SET buy_status='" . $buy_status . "' WHERE id = '" . $buy_id . "'";
    
    mysqli_query($con, $sql);
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('บันทึกข้อมูลเรียบร้อย');
        window.location.href='manage_request_contract.php';
    </script>"); 
}

//ฟังชั่นรายงานจองห้องพัก
function getReportBooking($start_date,$end_date){
	global $con;

	// แปลงรูปแบบวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd สำหรับการใช้ในคำสั่ง SQL
	$arrDate1 = explode("/", $start_date);
	$convert_start_date = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];
	$arrDate2 = explode("/", $end_date);
	$convert_end_date = $arrDate2[2].'-'.$arrDate2[1].'-'.$arrDate2[0];

	 // เขียนคำสั่ง SQL เพื่อดึงข้อมูลการจอง (booking) และข้อมูลที่เกี่ยวข้องจากหลายตาราง
	$sql = "SELECT *,b.id as bid 
	FROM  bookings b  
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE (b.booking_date BETWEEN '".$convert_start_date."' AND '".$convert_end_date."')
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'],
			'booking_phone' => $row['booking_phone'],
			'booking_email' => $row['booking_email'],
			'booking_date' => $row['booking_date'],
			'booking_status' => $row['booking_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

function getReportBookingOwner($start_date,$end_date,$users_id){
	global $con;

	// แปลงรูปแบบวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd สำหรับการใช้ในคำสั่ง SQL
	$arrDate1 = explode("/", $start_date);
	$convert_start_date = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];
	$arrDate2 = explode("/", $end_date);
	$convert_end_date = $arrDate2[2].'-'.$arrDate2[1].'-'.$arrDate2[0];

	 // เขียนคำสั่ง SQL เพื่อดึงข้อมูลการจอง (booking) และข้อมูลที่เกี่ยวข้องจากหลายตาราง
	$sql = "SELECT *,b.id as bid 
	FROM  bookings b  
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE a.users_id = '".$users_id."' AND (b.booking_date BETWEEN '".$convert_start_date."' AND '".$convert_end_date."')
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'booking_name' => $row['booking_name'],
			'booking_phone' => $row['booking_phone'],
			'booking_email' => $row['booking_email'],
			'booking_date' => $row['booking_date'],
			'booking_status' => $row['booking_status'],
			'apartments_id' => $row['apartments_id'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}
//ฟังชั่นรายงานเจ้าของห้อง
function getReportRoomate(){
	global $con;

	$sql = "SELECT *,q.id as qid 
	FROM requests q  
	LEFT JOIN rooms r ON q.rooms_id = r.id
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN users u ON q.users_id = u.id 
	ORDER BY q.id ASC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['qid'],
			'apartments_id' => $row['apartments_id'],
			'apartment' => $row['apartment'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'username' => $row['username'],
			'password' => $row['password'],
			'address' => $row['address'],
			'email' => $row['email'],
			'phone' => $row['phone'],
			'gender' => $row['gender'],
			'status' => $row['status'],
			'role' => $row['role'],
			'request_status' => $row['request_status'],
			'profile_img' => $row['profile_img'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//รายงานการขายสัญญาเช่า
function getReportContract($start_date,$end_date){
	global $con;

	// แปลงรูปแบบวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd สำหรับการใช้ในคำสั่ง SQL
	$arrDate1 = explode("/", $start_date);
	$convert_start_date = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];
	$arrDate2 = explode("/", $end_date);
	$convert_end_date = $arrDate2[2].'-'.$arrDate2[1].'-'.$arrDate2[0];

	$sql = "SELECT *,b.id as bid 
	FROM buys b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE (b.buy_date BETWEEN '".$convert_start_date."' AND '".$convert_end_date."') 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'buy_name' => $row['buy_name'],
			'buy_phone' => $row['buy_phone'],
			'buy_email' => $row['buy_email'],
			'buy_date' => $row['buy_date'],
			'buy_status' => $row['buy_status'],
			'apartments_id' => $row['apartments_id'],
			'apartment' => $row['apartment'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

//ฟังก์ชัน saveQuestionaires ทำหน้าที่เก็บข้อมูลแบบสอบถาม (questionnaires) เจ้าของห้อง
function saveQuestionaires($rooms_id, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10){
    
    global $con;

    // ดึงข้อมูลจากฐานข้อมูลที่มี rooms_id ตรงกัน
    $res = mysqli_query($con, "SELECT * FROM questionaires WHERE rooms_id = '".$rooms_id."'");

    // ตรวจสอบว่ามีข้อมูลอยู่ในฐานข้อมูลหรือไม่
    while($row = mysqli_fetch_array($res)) {
        $data['rooms_id'] = $row['rooms_id'];
    }

    // ถ้ามีข้อมูลอยู่แล้วให้ทำการอัปเดต
    if (!empty($data)) {
        $sql = "UPDATE questionaires SET q1='".$q1."', q2='".$q2."', q3='".$q3."', q4='".$q4."', q5='".$q5."', q6='".$q6."', q7='".$q7."', q8='".$q8."', q9='".$q9."', q10='".$q10."' WHERE rooms_id = '".$rooms_id."'";
    } else {
        // ถ้ายังไม่มีข้อมูลให้ทำการเพิ่มข้อมูลใหม่
        $sql = "INSERT INTO questionaires (rooms_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10) VALUES('".$rooms_id."', '".$q1."', '".$q2."', '".$q3."', '".$q4."', '".$q5."', '".$q6."', '".$q7."', '".$q8."', '".$q9."', '".$q10."')";
    }
    
    mysqli_query($con, $sql);
    
    // คำนวณคะแนนเฉลี่ย
    $total = ($q1 +  $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10) / 10;

    // อัปเดตคะแนนห้องในตาราง rooms
    $sql_room = "UPDATE rooms SET room_score='".$total."' WHERE id = '".$rooms_id."'";
    mysqli_query($con, $sql_room);
    mysqli_close($con);
    echo ("<script language='JavaScript'>
        alert('เพิ่มข้อมูลเรียบร้อย');
        window.location.href='manage_user_room.php';
        </script>"); 
}


//ฟังก์ชัน getCurrentQuestionaire มีหน้าที่ในการดึงข้อมูลแบบสอบถาม (questionnaire)
function getCurrentQuestionaire($rooms_id) {

    global $con; 

    // สืบค้นข้อมูลจากตาราง questionaires โดยใช้ rooms_id ที่ส่งเข้ามา
    $res = mysqli_query($con, "SELECT * FROM questionaires WHERE rooms_id = '" . $rooms_id . "'");
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC); 

    return $result; 
    mysqli_close($con); 
}

//ฟังก์ชั่นบันทึกแบบสอบถามความต้องการ
function saveQuestionairesFindding($users_id,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10){
	
	global $con;

	// ดึงข้อมูลจากตาราง questionaires_finding เพื่อตรวจสอบว่ามีข้อมูลของผู้ใช้หรือไม่
	$res = mysqli_query($con,"select * from questionaires_finding where users_id = '".$users_id."'");
	
	while($row = mysqli_fetch_array($res)) {
		// ถ้ามีข้อมูลของผู้ใช้ จะเก็บข้อมูลในตัวแปร $data
		$data['id'] = $row['id'];
		$data['rooms_id'] = $row['rooms_id'];
	}
	// ถ้ามีข้อมูลอยู่แล้วให้ทำการอัปเดต
	if (!empty($data)) {
		$sql="UPDATE questionaires_finding SET q1='".$q1."',q2='".$q2."',q3='".$q3."',q4='".$q4."',q5='".$q5."',q6='".$q6."',q7='".$q7."',q8='".$q8."',q9='".$q9."',q10='".$q10."' WHERE users_id = '".$users_id."'";
		$last_id = $data['id'];  // เก็บ id ของข้อมูลล่าสุด
	}else{
		// ถ้ายังไม่มีข้อมูลให้ทำการเพิ่มข้อมูลใหม่
		$sql = "INSERT INTO questionaires_finding (users_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10) VALUES('".$users_id."','".$q1."','".$q2."','".$q3."','".$q4."','".$q5."','".$q6."','".$q7."','".$q8."','".$q9."','".$q10."')";
		$last_id = $con->insert_id; // เก็บ id ของข้อมูลที่เพิ่งเพิ่ม
	}
	
	mysqli_query($con,$sql);
	// คำนวณค่าเฉลี่ยจากคำตอบทั้งหมด
    $total = ($q1 +  $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10) / 10;

    
     // อัปเดตค่าทั้งหมดในตาราง questionaires_finding
    $sql_room = "UPDATE questionaires_finding SET totals='".$total."' WHERE users_id = '".$users_id."'";
    mysqli_query($con,$sql_room);

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='edit_question_finding.php';
		</script>"); 
	
}

//ฟังก์ชัน getCurrentQuestionaireFindding มีหน้าที่ดึงข้อมูลแบบสอบถาม (questionnaire)
function getCurrentQuestionaireFindding($users_id){

	global $con;

	 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลแบบสอบถามที่เกี่ยวข้องกับผู้ใช้ตาม users_id
    $res = mysqli_query($con, "SELECT * FROM questionaires_finding WHERE users_id = '".$users_id."'");
    
    // ดึงข้อมูลแถวแรกและเก็บในรูปแบบอาร์เรย์
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    return $result;

    mysqli_close($con); 
}

//ฟังก์ชัน getCheckQuestionaireFindding คือฟังก์ชันที่ใช้เพื่อตรวจสอบจำนวนคำถามในตาราง questionaires_finding
function getCheckQuestionaireFindding($users_id){

	global $con;

	// รันคำสั่ง SQL เพื่อคำนวณจำนวนคำถามจากตาราง questionaires_finding ที่มี users_id ตรงกับที่ระบุ
	$res = mysqli_query($con,"SELECT COUNT(*) as numCount FROM questionaires_finding WHERE users_id = '".$users_id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

//ฟังก์ชัน getAllUserBuyContractfinding($users_id) คือฟังก์ชันที่ใช้เพื่อดึงข้อมูลการซื้อสัญญาของผู้ใช้
function getAllUserBuyContractfinding($users_id){
	global $con;

	 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลการซื้อจากตาราง buys, rooms และ apartments
	$sql = "SELECT *,b.id as bid 
	FROM buys b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE b.users_id = '".$users_id."' 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['bid'],
			'rooms_id' => $row['rooms_id'],
			'buy_name' => $row['buy_name'],
			'buy_phone' => $row['buy_phone'],
			'buy_email' => $row['buy_email'],
			'buy_date' => $row['buy_date'],
			'buy_status' => $row['buy_status'],
			'apartments_id' => $row['apartments_id'],
			'apartment' => $row['apartment'],
			'room_name' => $row['room_name'],
			'bed_type' => $row['bed_type'],
			'room_type' => $row['room_type'],
			'room_price' => $row['room_price'],
			'room_rent' => $row['room_rent'],
			'room_detail' => $row['room_detail'],
			'room_image' => $row['room_image'],
			'room_status' => $row['room_status'],
			'users_id' => $row['users_id'],
			'apart_address' => $row['apart_address'],
			'apart_type' => $row['apart_type'],
			'apart_name' => $row['apart_name'],
			'apart_number' => $row['apart_number'],
			'apart_class' => $row['apart_class'],
			'apart_elevator' => $row['apart_elevator'],
			'apart_contract' => $row['apart_contract'],
			'apart_lat' => $row['apart_lat'],
			'apart_lng' => $row['apart_lng'],
			'apart_detail' => $row['apart_detail'],
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}
//ฟังชั่นเข้าสู่ระบบด้วยอีเมล
function saveRegisterFromGoogleApi($firstname,$lastname,$email){
	
	global $con; 

	// ตรวจสอบว่ามีผู้ใช้งานที่มี email นี้อยู่ในฐานข้อมูลแล้วหรือไม่
	$sqlCheck = "select * from users where email = '".$email."'";
	$res = mysqli_query($con,$sqlCheck); 
	
	// วนลูปเพื่อดึงข้อมูลผู้ใช้งาน ถ้ามี email นี้ในฐานข้อมูล
	while($row = mysqli_fetch_array($res)) {
		$data['id'] = $row['id'];  
		$data['role'] = $row['role']; 
	}
	
	// ตรวจสอบว่าพบผู้ใช้งานในฐานข้อมูลหรือไม่
	if (!empty($data)) {
		session_start();
		$_SESSION['id'] = $data['id']; 
		$_SESSION['role'] = $data['role']; 
		echo ("<script language='JavaScript'>
				window.location.href='index.php';
				</script>");
		exit();
		
	}else{
		// กรณีผู้ใช้งานใหม่ยังไม่มีข้อมูลในฐานข้อมูล
		$fullName =  $firstname." ".$lastname; 
		// สร้างคำสั่ง SQL เพื่อเพิ่มผู้ใช้งานใหม่ลงในฐานข้อมูล
		$sql = "INSERT INTO users (username, password, email, status, role) VALUES('".$fullName."','".$email."','".$email."','1','5')";
		mysqli_query($con,$sql); 
		$last_id = $con->insert_id; 
		session_start(); 
		$_SESSION['id'] = $last_id; 
		$_SESSION['role'] = 5; 
	}
	mysqli_close($con);
	
}

function getAllDataChart($apartments_id){
	global $con;

	$sql = "SELECT COUNT(*) as numCount,r.room_name 
	FROM  bookings b  
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."'
	GROUP BY b.rooms_id
	ORDER BY b.id DESC";

	$res = mysqli_query($con,$sql);

	$jsonArray = array();

	//$arrData["data"] = array();

	while($row = mysqli_fetch_array($res)) {
		array_push($jsonArray, array('label' => $row['room_name'],'y' => $row['numCount']));
	}


	return $jsonArray;

	mysqli_close($con);


}

function getAllDataDetailChart($apartments_id){
	global $con;

	 // สร้างคำสั่ง SQL เพื่อดึงข้อมูลการซื้อจากตาราง buys, rooms และ apartments
	$sql = "SELECT COUNT(*) as numCount,r.room_name 
	FROM  bookings b  
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."'
	GROUP BY b.rooms_id
	ORDER BY b.id DESC";

	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'numCount' => $row['numCount'],
			'room_name' => $row['room_name']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

?>

