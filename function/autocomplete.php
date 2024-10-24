<?php

$con = mysqli_connect("localhost","root","","spare_rooms");
$con->set_charset("utf8");
$term = mysqli_real_escape_string($con,$_GET['term']);

$sql = "SELECT * FROM apartments WHERE apart_name LIKE '%".$term."%'";
$query = mysqli_query($con, $sql);

$sql_room = "SELECT * FROM rooms WHERE apartment LIKE '%".$term."%'";
$queryRoom = mysqli_query($con, $sql_room);

$result = array();
$resultRe = array();
while($data = mysqli_fetch_array($query))
{
    //$result[] = $data['apart_name'];
    array_push($result, $data['apart_name']);
}
while($data2 = mysqli_fetch_array($queryRoom))
{
    //$result[] = $data2['apartment'];
    array_push($result, $data2['apartment']);
}
$resultRe = array_unique($result);

echo json_encode($resultRe);
?>