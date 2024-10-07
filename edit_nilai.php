<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $nisn = $data['nisn'];
        $id_mapel = $data['id_mapel'];
        $nilai_uts = $data['nilai_uts'];
        $nilai_uas = $data['nilai_uas'];

        $query = mysqli_query($conn, "UPDATE nilai_siswa SET 
        nilai_uts = '$nilai_uts', nilai_uas = '$nilai_uas' 
        WHERE nilai_siswa.id_nilai = '$id'");
    
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