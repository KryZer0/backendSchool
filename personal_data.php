<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nisn'])) {
    $result = array();

    // Sanitize the input value
    $nisn = mysqli_real_escape_string($conn, $_GET['nisn']);

    $query = mysqli_query($conn, "SELECT siswa.NISN, siswa.Nama_siswa, siswa.Tanggal_lahir, siswa.jns_kelamin_siswa, kelas.nama_kelas 
        FROM siswa
        INNER JOIN kelas ON siswa.kelas = kelas.kode_kelas
        WHERE siswa.nisn = '$nisn'");

    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = $row;
    }

    echo json_encode(array('result' => $result));
} else {
    // For GET requests or other methods, return an error response
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>