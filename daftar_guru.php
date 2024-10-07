<?php
require_once('connection.php');
$result = array();
$query = mysqli_query($conn,"SELECT g.id_guru, g.Nomor_induk_guru, g.nama_guru, g.email, mp.nama_mapel
FROM guru g 
LEFT JOIN mata_pelajaran mp ON g.id_guru = mp.id_guru
ORDER BY nama_guru ASC");
while($row = mysqli_fetch_assoc($query)){
$result[] = $row;
}
echo json_encode(array('result'=>$result));
?>