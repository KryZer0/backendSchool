<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set header for JSON response
    header('Content-Type: application/json');
    
    // Read the input data
    $data = json_decode(file_get_contents('php://input'), true);
    $response = array();

    if ($data !== null) { // Check if JSON was successfully decoded
        $kls = mysqli_real_escape_string($conn, $data['kelas']);
        $mapel = mysqli_real_escape_string($conn, $data['mapel']);
        $hari = mysqli_real_escape_string($conn, $data['hari']);
        $mulai = mysqli_real_escape_string($conn, $data['mulai']);
        $selesai = mysqli_real_escape_string($conn, $data['selesai']);
        
        // Fetch the active tahun_ajar
        $tahunQuery = "SELECT id_tahun FROM tahun_ajaran WHERE status = 'active' LIMIT 1";
        $result = mysqli_query($conn, $tahunQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $tahun = mysqli_real_escape_string($conn, $row['id_tahun']);

            // Assuming $nama should be an ID or some key representing mapel
            $query1 = mysqli_query($conn, "INSERT INTO jadwal_kelas (kode_kelas, id_tahun, id_mapel, hari, jam) VALUES ('$kls', '$tahun', '$mapel', '$hari', '$mulai - $selesai')");
            
            if ($query1) {
                $response['success'] = true;
                $response['message'] = 'Jadwal Tersimpan';
                http_response_code(200); // Success 
            } else {
                $response['error'] = true;
                $response['message'] = 'Error saving the new record';
                http_response_code(500); // Internal Server Error
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Somethings wrong on the server';
            http_response_code(500); // Internal Server Error (Failed to fetch active tahun_ajar)
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
