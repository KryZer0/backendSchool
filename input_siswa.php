<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = array();
    header('Content-Type: application/json'); // Set the content type to JSON

    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $nisn = $data['nisn'];
        $nama = $data['nama'];
        $tanggal = $data['tanggal'];
        $kelamin = $data['kelamin'];
        $kelas = $data['kelas'];

        // Check if NISN already exists
        $checkQuery = mysqli_query($conn, "SELECT * FROM siswa WHERE NISN = '$nisn'");
        if (mysqli_num_rows($checkQuery) > 0) {
            $response['error'] = true;
            $response['message'] = 'NISN Telah Terdaftar';
            http_response_code(409); // Conflict
        } else {
            // Insert the new record
            $query = mysqli_query($conn, "INSERT INTO siswa (NISN, Nama_siswa, Tanggal_lahir, jns_kelamin_siswa, kelas)
            VALUES ('$nisn', '$nama', '$tanggal', '$kelamin', '$kelas');");

            if ($query) {
                $response['success'] = true;
                $response['message'] = 'Data siswa tersimpan';
                http_response_code(200); // Success 
            } else {
                $response['error'] = true;
                $response['message'] = 'Unknown error occurred';
                http_response_code(500); // Internal Server Error
            }
        }

        echo json_encode($response);
    } else {
        // Error JSON input
        $response['error'] = true;
        $response['message'] = 'Invalid JSON data';
        http_response_code(400); // Bad Request
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
