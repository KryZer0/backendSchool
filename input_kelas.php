<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header for JSON response
    header('Content-Type: application/json');
    
    // Read the input data
    $data = json_decode(file_get_contents('php://input'), true);
    $response = array();

    if ($data !== null) { // Check if JSON was successfully decoded
        $kode = mysqli_real_escape_string($conn, $data['kode_kelas']);
        $nama = mysqli_real_escape_string($conn, $data['nama_kelas']);
        $wali = mysqli_real_escape_string($conn, $data['wali_kelas']);
        
        // Assuming $status was a typo and should be $wali
        $query1 = mysqli_query($conn, "INSERT INTO kelas (kode_kelas, nama_kelas, wali_kelas) VALUES ('$kode', '$nama', '$wali')");
        
        if ($query1) {
            $response['success'] = true;
            $response['message'] = 'Materi Tersimpan';
            http_response_code(200); // Success 
        } else {
            $response['error'] = true;
            $response['message'] = 'Error saving the new record';
            http_response_code(500); // Internal Server Error
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'Invalid JSON data';
        http_response_code(400); // Bad Request
    }

    echo json_encode($response);
} else {
    // For GET requests or other methods, return an error response
    header('Content-Type: application/json');
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    echo json_encode($response);
}
?>
