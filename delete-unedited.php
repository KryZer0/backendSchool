<?php

require_once('connection.php');
$id = $_GET['nisn'];
if(!$id){
echo json_encode(array('message'=>'required field is empty'));
}
else{
$query = mysqli_query($CON, "DELETE FROM siswa WHERE nisn='$id'");
if($query){
echo json_encode(array('message'=>'student data successfully deleted.'));
}
else{
echo json_encode(array('message'=>'student data failed to delete.'));
}
}
?>