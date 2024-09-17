<?php
error_reporting(0);

//เชื่อต่อ Database
$con = mysqli_connect("localhost","root","","spare_rooms");
$con->set_charset("utf8");

function checkLogin($username,$password){
	$data = array();
	global $con;

	$res = mysqli_query($con,"select * from users where username = '".$username."' and password='".$password."' ");
	
	while($row = mysqli_fetch_array($res)) {
		$data['id'] = $row['id'];
		$data['role'] = $row['role'];
	}
	if (!empty($data)) {
		session_start();
		$id = $data['id'];
		$_SESSION['id'] = $data['id'];
		$_SESSION['role'] = $data['role'];

		echo ("<script language='JavaScript'>
			window.location.href='index.php';
			</script>");
	}else{
		echo "<script type='text/javascript'>alert('ไม่สามารถเข้าสู่ระบบได้');</script>";
	}
	
	mysqli_close($con);

}

function formatDateFull($date){
	if($date=="0000-00-00"){
		return "";
	}
	if($date=="")
		return $date;
	$raw_date = explode("-", $date);
	return  $raw_date[2] . "/" . $raw_date[1] . "/" . $raw_date[0];
}

function logout(){
	session_start();
	session_unset();
	session_destroy();

	echo ("<script language='JavaScript'>
		window.location.href='index.php';
		</script>");
	exit();
}

function getUser($id){

	global $con;

	$sql = "SELECT * FROM users WHERE id = '".$id."'";
	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getCheckEmail($email){

	global $con;

	$res = mysqli_query($con,"SELECT COUNT(*) as numCount FROM users WHERE email = '".$email."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function saveRegister($username,$password,$address,$email,$phone,$gender,$status,$role,$profile_img){
	
	
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

function editProfile($id,$username,$password,$address,$email,$phone,$gender,$status,$profile_img){

	global $con;

	if($profile_img != null){
		if(move_uploaded_file($_FILES["profile_img"]["tmp_name"],"images/users/".$_FILES["profile_img"]["name"]))
		{
			$sql="UPDATE users SET username='".$username."',password='".$password."',address='".$address."',email='".$email."',phone='".$phone."',gender='".$gender."',status='".$status."',profile_img='".$_FILES["profile_img"]["name"]."' WHERE id = '".$id."'";
		}
	}else{
		$sql="UPDATE users SET username='".$username."',password='".$password."',address='".$address."',email='".$email."',phone='".$phone."',gender='".$gender."',status='".$status."' WHERE id = '".$id."'";
		mysqli_query($con,$sql);

	}
	mysqli_query($con,$sql);
	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='profile.php';
		</script>"); 
	
}

function deleteUser($id,$role){
	global $con;

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

function getAllUserInRole($role){
	global $con;

	$res = mysqli_query($con,"SELECT * FROM users WHERE role = '".$role."' ORDER BY id DESC");

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

function getCurrentUser($id){

	global $con;

	$res = mysqli_query($con,"SELECT * FROM users WHERE id = '".$id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function saveApartment($users_id,$apart_name,$apart_type,$apart_number,$apart_class,$apart_elevator,$apart_address,$apart_detail,$apart_image,$apart_contract,$apart_lat,$apart_lng){
	
	
	global $con;

	if($apart_image != null && $apart_contract != null){
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]) && move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{
			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_contract, apart_lat, apart_lng, apart_detail, apart_image) VALUES('".$users_id."','".$apart_name."','".$apart_address."','".$apart_type."','".$apart_number."','".$apart_class."','".$apart_elevator."','".$_FILES["apart_contract"]["name"]."','".$apart_lat."','".$apart_lng."','".$apart_detail."','".$_FILES["apart_image"]["name"]."')";
		}
	}else if($apart_image != null && $apart_contract == null){
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]))
		{
			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_lat, apart_lng, apart_detail, apart_image) VALUES('".$users_id."','".$apart_name."','".$apart_address."','".$apart_type."','".$apart_number."','".$apart_class."','".$apart_elevator."','".$apart_lat."','".$apart_lng."','".$apart_detail."','".$_FILES["apart_image"]["name"]."')";
		}
	}else if($apart_image == null && $apart_contract != null){
		if(move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{

			$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_contract, apart_lat, apart_lng, apart_detail) VALUES('".$users_id."','".$apart_name."','".$apart_address."','".$apart_type."','".$apart_number."','".$apart_class."','".$apart_elevator."','".$_FILES["apart_contract"]["name"]."','".$apart_lat."','".$apart_lng."','".$apart_detail."')";
		}
	}else{

		$sql = "INSERT INTO apartments (users_id, apart_name, apart_address, apart_type, apart_number, apart_class, apart_elevator, apart_lat, apart_lng, apart_detail) VALUES('".$users_id."','".$apart_name."','".$apart_address."','".$apart_type."','".$apart_number."','".$apart_class."','".$apart_elevator."','".$apart_lat."','".$apart_lng."','".$apart_detail."')";
	}
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_apartment.php';
		</script>"); 
	
}

function editApartment($id,$users_id,$apart_name,$apart_type,$apart_number,$apart_class,$apart_elevator,$apart_address,$apart_detail,$apart_image,$apart_contract,$apart_lat,$apart_lng){

	global $con;

	if($profile_img != null && $apart_contract != null){
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]) && move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_contract='".$_FILES["apart_contract"]["name"]."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."',apart_image='".$_FILES["apart_image"]["name"]."' WHERE id = '".$id."'";
		}
	}else if($profile_img != null && $apart_contract == null){
		if(move_uploaded_file($_FILES["apart_image"]["tmp_name"],"images/apartment/".$_FILES["apart_image"]["name"]))
		{
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."',apart_image='".$_FILES["apart_image"]["name"]."' WHERE id = '".$id."'";
		}
	}else if($profile_img == null && $apart_contract != null){
		if(move_uploaded_file($_FILES["apart_contract"]["tmp_name"],"images/contract/".$_FILES["apart_contract"]["name"]))
		{
			$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_contract='".$_FILES["apart_contract"]["name"]."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."' WHERE id = '".$id."'";
		}
	}else{
		$sql="UPDATE apartments SET users_id='".$users_id."',apart_name='".$apart_name."',apart_address='".$apart_address."',apart_type='".$apart_type."',apart_number='".$apart_number."',apart_class='".$apart_class."',apart_elevator='".$apart_elevator."',apart_lat='".$apart_lat."',apart_lng='".$apart_lng."',apart_detail='".$apart_detail."' WHERE id = '".$id."'";

	}
	mysqli_query($con,$sql);
	mysqli_close($con);

	echo ("<script language='JavaScript'>
		alert('แก้ไขข้อมูลเรียบร้อย');
		window.location.href='manage_apartment.php';
		</script>"); 
	
}

function deleteApartment($id){
	global $con;

	mysqli_query($con,"DELETE FROM apartments WHERE id='".$id."'");
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_apartment.php';
		</script>"); 

}

function getAllApartment(){
	global $con;

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

function getAllApartmentUser($users_id){
	global $con;

	$sql = "SELECT *,a.id as aid FROM apartments a LEFT JOIN users u ON a.users_id = u.id WHERE a.users_id = '".$users_id."' ORDER BY a.id DESC";
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

function getCurrentApartment($id){

	global $con;

	$res = mysqli_query($con,"SELECT *,a.id as aid FROM apartments a LEFT JOIN users u ON a.users_id = u.id WHERE a.id = '".$id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function saveRoom($apartments_id,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category){

	global $con;

	if($room_image != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{

			$sql = "INSERT INTO rooms (apartments_id, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category) VALUES('".$apartments_id."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."')";
			mysqli_query($con,$sql);
			$last_id = $con->insert_id;

		}
	}else{
		$sql = "INSERT INTO rooms (apartments_id, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category) VALUES('".$apartments_id."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."')";
		mysqli_query($con,$sql);
		$last_id = $con->insert_id;
	}



	for( $i=0 ; $i < $total ; $i++ ) {

		$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];

		if ($tmpFilePath != ""){

			$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];

			if(move_uploaded_file($tmpFilePath, $newFilePath)) {

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

function editRoom($id,$apartments_id,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category){

	global $con;

	if($room_image != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{
			$sql="UPDATE rooms SET apartments_id='".$apartments_id."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else{
		$sql="UPDATE rooms SET apartments_id='".$apartments_id."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."' WHERE id = '".$id."'";

		mysqli_query($con,$sql);

	}


	if($total > 1){
		mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
		for( $i=0 ; $i < $total ; $i++ ) {
			$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
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

function deleteRoom($id,$apartments_id){
	global $con;

	mysqli_query($con,"DELETE FROM rooms WHERE id='".$id."'");
	mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 

}

function openRoom($id,$apartments_id){
	global $con;

	$sql = "UPDATE rooms SET room_status='1' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_room.php?apartments_id=$apartments_id';
		</script>"); 

}

function closeRoom($id,$apartments_id){
	global $con;
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

function getAllRoomInApartmentt($apartments_id,$users_id){
	global $con;

	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."' AND r.users_id = '".$users_id."'
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

function getAllRoomInApartmentOpen($apartments_id){
	global $con;

	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.apartments_id = '".$apartments_id."' AND r.room_status = '1' AND r.room_category = '1' 
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

function getAllRoomGallery($rooms_id){
	global $con;

	$sql = "SELECT * FROM rooms_gallery WHERE rooms_id = '".$rooms_id."' ORDER BY id ASC";
	$res = mysqli_query($con,$sql);

	$data = array();
	while($row = mysqli_fetch_assoc($res)) {
		$namesArray[] = array(
			'id' => $row['aid'],
			'rooms_id' => $row['rooms_id'],
			'images' => $row['images']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

function getCurrentRoom($id){

	global $con;


	$res = mysqli_query($con,"SELECT *,r.id as rid FROM rooms r LEFT JOIN apartments a ON r.apartments_id = a.id WHERE r.id = '".$id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
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

function getAllUserBooking($users_id){
	global $con;

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

function getAllUserBookingPendingStatus($users_id){
	global $con;
	$sql = "SELECT *,b.id as bid 
	FROM apartments a 
	LEFT JOIN rooms r ON a.id = r.apartments_id 
	LEFT JOIN bookings b ON r.id = b.rooms_id 
	WHERE a.users_id = '".$users_id."' AND b.booking_status = '1'
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

function getCurrentBooking($id){

	global $con;

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

function updateBookingStatus($booking_id,$booking_status){
	global $con;

	$sql = "UPDATE bookings SET booking_status='".$booking_status."' WHERE id = '".$booking_id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_pending.php';
		</script>"); 

}

function getCheckNewPending($users_id){

	global $con;

	$sql = "SELECT COUNT(*) as numBook 
	FROM apartments a 
	LEFT JOIN rooms r ON a.id = r.apartments_id 
	LEFT JOIN bookings b ON r.id = b.rooms_id 
	WHERE a.users_id = '".$users_id."' AND b.booking_status = '1'
	ORDER BY b.id DESC";

	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getAllUserBookingApartment($users_id){
	global $con;
	$sql = "SELECT *,b.id as bid 
	FROM bookings b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE a.users_id = '".$users_id."'
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

function getAllBookingApartment(){
	global $con;
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

function saveRoomRental($apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$room_remark,$contract_file,$room_lat,$room_lng){

	global $con;

	if($room_image != null && $contract_file != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{

			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark, contract_file, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$room_remark."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";
			mysqli_query($con,$sql);
			$last_id = $con->insert_id;

		}
	}else if($room_image != null && $contract_file == null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{
			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$room_remark."','".$room_lat."','".$room_lng."')";
			mysqli_query($con,$sql);
			$last_id = $con->insert_id;

		}
	}else if($room_image == null && $contract_file != null){
		if(move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{

			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category, room_remark, contract_file, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$room_remark."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";
			mysqli_query($con,$sql);
			$last_id = $con->insert_id;

		}
	}else{
		$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, room_remark) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$room_remark."')";
		mysqli_query($con,$sql);
		$last_id = $con->insert_id;
	}



	for( $i=0 ; $i < $total ; $i++ ) {

		$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];

		if ($tmpFilePath != ""){

			$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];

			if(move_uploaded_file($tmpFilePath, $newFilePath)) {

				$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$last_id."','".$_FILES['room_gallery']['name'][$i]."')";
				mysqli_query($con,$sql_detail);

			}
		}
	}

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_user_room.php';
		</script>"); 

}

function editRoomRental($id,$apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$room_remark,$contract_file,$room_lat,$room_lng){

	global $con;

	if($room_image != null && $contract_file != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."',room_category='".$room_category."',room_remark='".$room_remark."',contract_file='".$_FILES["contract_file"]["name"]."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else if($room_image != null && $contract_file == null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."',room_category='".$room_category."',room_remark='".$room_remark."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else if($room_image == null && $contract_file != null){
		if(move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_category='".$room_category."',room_remark='".$room_remark."',contract_file='".$_FILES["contract_file"]["name"]."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else{
		$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_category='".$room_category."',room_remark='".$room_remark."' WHERE id = '".$id."'";

		mysqli_query($con,$sql);

	}


	if($total > 1){
		mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
		for( $i=0 ; $i < $total ; $i++ ) {
			$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$id."','".$_FILES['room_gallery']['name'][$i]."')";
					mysqli_query($con,$sql_detail);

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

function deleteRoomRental($id){
	global $con;

	mysqli_query($con,"DELETE FROM rooms WHERE id='".$id."'");
	mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_user_room.php';
		</script>"); 

}

function openRoomRental($id){
	global $con;

	$sql = "UPDATE rooms SET room_status='1' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_user_room.php';
		</script>"); 

}

function closeRoomRental($id){
	global $con;
	$sql = "UPDATE rooms SET room_status='2' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_user_room.php';
		</script>"); 

}

function getAllRentalRoom($users_id){
	global $con;

	$sql = "SELECT *,r.id as rid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE r.users_id = '".$users_id."'  
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

function getAllRoommateFindding($users_id){
	global $con;

	$res = mysqli_query($con,"select * from questionaires_finding where users_id = '".$users_id."' ");
	
	while($row = mysqli_fetch_array($res)) {
		$data['totals'] = $row['totals'];
	}

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

function getAllUserRequest($users_id){
	global $con;

	$sql = "SELECT *,q.id as qid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN requests q ON r.id = q.rooms_id 
	LEFT JOIN users u ON q.users_id = u.id
	WHERE r.users_id = '".$users_id."' AND q.request_status = '1'  
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

function getAllHistoryUserRequest($users_id){
	global $con;

	$sql = "SELECT *,q.id as qid 
	FROM requests q 
	LEFT JOIN users u ON q.users_id = u.id 
	LEFT JOIN rooms r ON q.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE q.users_id = '".$users_id."' ORDER BY q.id ASC ";

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

function getCheckNewRequest($users_id){

	global $con;

	$sql = "SELECT COUNT(*) as numReq 
	FROM requests q 
	LEFT JOIN users u ON q.users_id = u.id 
	LEFT JOIN rooms r ON q.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE q.users_id = '".$users_id."' AND q.request_status = '1'";

	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getCurrentRequest($id){

	global $con;

	$sql = "SELECT *,q.id as qid,q.users_id as qusers_id  
	FROM requests q 
	LEFT JOIN users u ON q.users_id = u.id 
	LEFT JOIN rooms r ON q.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE q.id = '".$id."' ";

	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function updateRequest($requests_id,$request_status){
	global $con;

	$sql = "UPDATE requests SET request_status='".$request_status."' WHERE id = '".$requests_id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_request_roommate.php';
		</script>"); 

}

function saveRoomContract($apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$contract_year,$contract_end,$contract_file,$room_lat,$room_lng){

	global $con;

	$arrDate1 = explode("/", $contract_end);
	$convert_contract_end = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];

	if($room_image != null && $contract_file != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]) && move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, contract_year, contract_end, contract_file, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";

		}
	}else if($room_image == null && $contract_file != null){
		if(move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_status, room_category, contract_year, contract_end, contract_file, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$_FILES["contract_file"]["name"]."','".$room_lat."','".$room_lng."')";
		}
	}else if($room_image != null && $contract_file == null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"]))
		{

			$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, contract_year, contract_end, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','".$_FILES["room_image"]["name"]."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$room_lat."','".$room_lng."')";

		}
	}else{
		$sql = "INSERT INTO rooms (apartment, users_id, room_name, bed_type, room_type, room_price, room_rent, room_detail, room_image, room_status, room_category, contract_year, contract_end, room_lat, room_lng) VALUES('".$apartment."','".$users_id."','".$room_name."','".$bed_type."','".$room_type."','".$room_price."','".$room_rent."','".$room_detail."','1','".$room_category."','".$contract_year."','".$convert_contract_end."','".$room_lat."','".$room_lng."')";
	}
	mysqli_query($con,$sql);
	$last_id = $con->insert_id;



	for( $i=0 ; $i < $total ; $i++ ) {

		$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];

		if ($tmpFilePath != ""){

			$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];

			if(move_uploaded_file($tmpFilePath, $newFilePath)) {

				$sql_detail = "INSERT INTO rooms_gallery (rooms_id, images) VALUES ('".$last_id."','".$_FILES['room_gallery']['name'][$i]."')";
				mysqli_query($con,$sql_detail);

			}
		}
	}

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_contract.php';
		</script>"); 

}

function editRoomContract($id,$apartment,$room_name,$bed_type,$room_type,$room_price,$room_rent,$room_detail,$room_image,$room_gallery,$total,$users_id,$room_category,$contract_year,$contract_end,$contract_file,$room_lat,$room_lng){

	global $con;

	$arrDate1 = explode("/", $contract_end);
	$convert_contract_end = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];

	if($room_image != null && $contract_file != null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"])  && move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."',room_category='".$room_category."',contract_year='".$contract_year."',contract_end='".$convert_contract_end."',contract_file='".$_FILES["contract_file"]["name"]."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else if($room_image == null && $contract_file != null){
		if(move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_category='".$room_category."',contract_year='".$contract_year."',contract_end='".$convert_contract_end."',contract_file='".$_FILES["contract_file"]["name"]."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else if($room_image != null && $contract_file == null){
		if(move_uploaded_file($_FILES["room_image"]["tmp_name"],"images/room/".$_FILES["room_image"]["name"])  && move_uploaded_file($_FILES["contract_file"]["tmp_name"],"images/contract/".$_FILES["contract_file"]["name"]))
		{
			$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_image='".$_FILES["room_image"]["name"]."',room_category='".$room_category."',contract_year='".$contract_year."',contract_end='".$convert_contract_end."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";
			mysqli_query($con,$sql);

		}
	}else{
		$sql="UPDATE rooms SET apartment='".$apartment."',users_id='".$users_id."',room_name='".$room_name."',bed_type='".$bed_type."',room_type='".$room_type."',room_price='".$room_price."',room_rent='".$room_rent."',room_detail='".$room_detail."',room_category='".$room_category."',contract_year='".$contract_year."',room_lat='".$room_lat."',room_lng='".$room_lng."' WHERE id = '".$id."'";

		mysqli_query($con,$sql);

	}


	if($total > 1){
		mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
		for( $i=0 ; $i < $total ; $i++ ) {
			$tmpFilePath = $_FILES['room_gallery']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$newFilePath = "images/room_gallery/" . $_FILES['room_gallery']['name'][$i];
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
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

function deleteContract($id){
	global $con;

	mysqli_query($con,"DELETE FROM rooms WHERE id='".$id."'");
	mysqli_query($con,"DELETE FROM rooms_gallery WHERE rooms_id = '".$id."'");
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ลบข้อมูลเรียบร้อยแล้ว');
		window.location.href='manage_contract.php';
		</script>"); 

}

function openContract($id){
	global $con;

	$sql = "UPDATE rooms SET room_status='1' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_contract.php';
		</script>"); 

}

function closeContract($id){
	global $con;
	$sql = "UPDATE rooms SET room_status='2' WHERE id = '".$id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_contract.php';
		</script>"); 

}

function getAllContract(){
	global $con;

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

function saveBuy($rooms_id,$users_id,$buy_name,$buy_phone,$buy_email){
	
	global $con;

	$yThai = date("Y")+543;
	$dateNow = $yThai.date("-m-d");

	$sql = "INSERT INTO buys (users_id, rooms_id, buy_name, buy_phone, buy_email, buy_date, buy_status) VALUES('".$users_id."','".$rooms_id."','".$buy_name."','".$buy_phone."','".$buy_email."','".$dateNow."','1')";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('ส่งคำขอการซื้อสัญญาเรียบร้อย');
		window.location.href='history_contract.php';
		</script>"); 
	
}

function getAllUserBuyContract($users_id){
	global $con;

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

function getCurrentBuy($id){

	global $con;

	$sql = "SELECT *,b.id as bid,r.users_id as rusers_id 
	FROM buys b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	WHERE b.id = '".$id."' 
	ORDER BY b.id DESC";
	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getAllUserRequestContract($users_id){
	global $con;

	$sql = "SELECT *,b.id as bid 
	FROM rooms r 
	LEFT JOIN apartments a ON r.apartments_id = a.id 
	LEFT JOIN buys b ON r.id = b.rooms_id 
	LEFT JOIN users u ON b.users_id = u.id
	WHERE r.users_id = '".$users_id."' AND b.buy_status = '1'  
	ORDER BY b.id ASC";
	$res = mysqli_query($con,$sql);

	$data = array();
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
			'apart_image' => $row['apart_image']);
	}

	$data = $namesArray;

	return $data;
	mysqli_close($con);

}

function getCheckNewRequestBuy($users_id){

	global $con;

	$sql = "SELECT COUNT(*) as numBuy 
	FROM buys b 
	LEFT JOIN rooms r ON b.rooms_id = r.id 
	WHERE r.users_id = '".$users_id."' AND b.buy_status = '1'";

	$res = mysqli_query($con,$sql);
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function updateBuyStatus($buy_id,$buy_status){
	global $con;

	$sql = "UPDATE buys SET buy_status='".$buy_status."' WHERE id = '".$buy_id."'";
	mysqli_query($con,$sql);
	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('บันทึกข้อมูลเรียบร้อย');
		window.location.href='manage_request_contract.php';
		</script>"); 

}

function getReportBooking($start_date,$end_date){
	global $con;

	$arrDate1 = explode("/", $start_date);
	$convert_start_date = $arrDate1[2].'-'.$arrDate1[1].'-'.$arrDate1[0];
	$arrDate2 = explode("/", $end_date);
	$convert_end_date = $arrDate2[2].'-'.$arrDate2[1].'-'.$arrDate2[0];

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

function getReportContract($start_date,$end_date){
	global $con;

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

function saveQuestionaires($rooms_id,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10){
	
	global $con;

	$res = mysqli_query($con,"select * from questionaires where rooms_id = '".$rooms_id."'");
	
	while($row = mysqli_fetch_array($res)) {
		$data['rooms_id'] = $row['rooms_id'];
	}
	if (!empty($data)) {
		$sql="UPDATE questionaires SET q1='".$q1."',q2='".$q2."',q3='".$q3."',q4='".$q4."',q5='".$q5."',q6='".$q6."',q7='".$q7."',q8='".$q8."',q9='".$q9."',q10='".$q10."' WHERE rooms_id = '".$rooms_id."'";
	}else{
		$sql = "INSERT INTO questionaires (rooms_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10) VALUES('".$rooms_id."','".$q1."','".$q2."','".$q3."','".$q4."','".$q5."','".$q6."','".$q7."','".$q8."','".$q9."','".$q10."')";
	}
	
	mysqli_query($con,$sql);
    $total = ($q1 +  $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10) / 10;

    $sql_room = "UPDATE rooms SET room_score='".$total."' WHERE id = '".$rooms_id."'";
    mysqli_query($con,$sql_room);

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='manage_user_room.php';
		</script>"); 
	
}

function getCurrentQuestionaire($rooms_id){

	global $con;


	$res = mysqli_query($con,"SELECT * FROM questionaires WHERE rooms_id = '".$rooms_id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function saveQuestionairesFindding($users_id,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10){
	
	global $con;

	$res = mysqli_query($con,"select * from questionaires_finding where users_id = '".$users_id."'");
	
	while($row = mysqli_fetch_array($res)) {
		$data['id'] = $row['id'];
		$data['rooms_id'] = $row['rooms_id'];
	}
	if (!empty($data)) {
		$sql="UPDATE questionaires_finding SET q1='".$q1."',q2='".$q2."',q3='".$q3."',q4='".$q4."',q5='".$q5."',q6='".$q6."',q7='".$q7."',q8='".$q8."',q9='".$q9."',q10='".$q10."' WHERE users_id = '".$users_id."'";
		$last_id = $data['id'];
	}else{
		$sql = "INSERT INTO questionaires_finding (users_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10) VALUES('".$users_id."','".$q1."','".$q2."','".$q3."','".$q4."','".$q5."','".$q6."','".$q7."','".$q8."','".$q9."','".$q10."')";
		$last_id = $con->insert_id;
	}
	
	mysqli_query($con,$sql);
    $total = ($q1 +  $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10) / 10;

    $sql_room = "UPDATE questionaires_finding SET totals='".$total."' WHERE users_id = '".$users_id."'";
    mysqli_query($con,$sql_room);

	mysqli_close($con);
	echo ("<script language='JavaScript'>
		alert('เพิ่มข้อมูลเรียบร้อย');
		window.location.href='edit_question_finding.php';
		</script>"); 
	
}

function getCurrentQuestionaireFindding($users_id){

	global $con;

	$res = mysqli_query($con,"SELECT * FROM questionaires_finding WHERE users_id = '".$users_id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

function getCheckQuestionaireFindding($users_id){

	global $con;

	$res = mysqli_query($con,"SELECT COUNT(*) as numCount FROM questionaires_finding WHERE users_id = '".$users_id."'");
	$result=mysqli_fetch_array($res,MYSQLI_ASSOC);
	return $result;

	mysqli_close($con);

}

?>