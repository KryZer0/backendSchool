<?php
require_once('connection.php');
$result = array();
$query = mysqli_query($CON,"SELECT * FROM siswa ORDER BY nisn DESC");
while($row = mysqli_fetch_assoc($query)){
$result[] = $row;
}
echo json_encode(array('result'=>$result));
?>