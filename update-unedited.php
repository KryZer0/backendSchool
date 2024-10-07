<?php
require_once('connection.php');
$vid = isset($_POST['nisn']) ? $_POST['nisn']:'';
$vjudul = isset($_POST['nama_siswa']) ? $_POST['nama_siswa']:'';
$vpengarang = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir']:'';
$vharga = isset($_POST['jns_kelamin_siswa']) ? $_POST['jns_kelamin_siswa']:'';
if(!$vid || !$vjudul || !$vpengarang || !$vharga){
echo json_encode(array('message'=>'required field is empty. '.$vid));
}
else{
$myquery= "INSERT INTO siswa VALUES ($vid,'$vjudul','$vpengarang',$vharga,
'')";
$query = mysqli_query($CON, $myquery );
if($query){
echo json_encode(array('message'=>'student data successfully added.'));
}
else{
echo json_encode(array('message'=>'student data failed to add.'. $myquery
));
}
}
?>