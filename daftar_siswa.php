<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    if (isset($_GET['kls'])){
        $kelas = mysqli_real_escape_string($conn, $_GET['kls']);
        $query = mysqli_query($conn,"SELECT * FROM siswa  WHERE siswa.kelas ='$kelas' ORDER BY siswa.Nama_siswa ASC ");
    } else {
        $query = mysqli_query($conn,"SELECT * FROM siswa ORDER BY siswa.Nama_siswa ASC ");
    }
    while($row = mysqli_fetch_assoc($query)){
        $result[] = $row;
    }
echo json_encode(array('result'=>$result));
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>