<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nisn'])) {
    $nisn = mysqli_real_escape_string($conn, $_GET['nisn']);
    $result = array();
    $query = mysqli_query($conn,"SELECT s.nisn, s.Nama_siswa, s.Tanggal_lahir, s.jns_kelamin_siswa, k.nama_kelas
    FROM siswa s
    LEFT JOIN kelas k On s.kelas = k.kode_kelas
    WHERE s.nisn='$nisn'");

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
        echo json_encode(array('result' => $result));
    } else {
        $response['error'] = true;
        $response['message'] = 'Error executing the query';
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode($response);
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