<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $old_nisn = mysqli_real_escape_string($conn, $_GET['nisn']);
        $nisn = $data['nisn'];
        $nama = $data['nama'];
        $tanggal = $data['tanggal'];
        $kelamin = $data['kelamin'];
        $namakelas = $data['kelas'];

        $query = mysqli_query($conn, "UPDATE siswa SET NISN = '$nisn',
        Nama_siswa = '$nama', Tanggal_lahir = '$tanggal',
        jns_kelamin_siswa = '$kelamin', kelas = '$namakelas' WHERE NISN = '$old_nisn'");
    
        $response['success'] = true;
        $response['message'] = 'Data Saved';
        http_response_code(200); // Success 
        echo json_encode($response);
    } else {
        // Error JSON input
        $response['error'] = true;
        $response['message'] = 'Invalid JSON data';
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    // For GET requests or other methods, return an error response
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>