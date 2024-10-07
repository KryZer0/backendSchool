<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();

    if ($data !== null) { // Check if JSON was successfully decoded
        // Check if all required keys exist in the JSON data
        if (isset($data['id_jadwal'], $data['kelas'], $data['mapel'], $data['hari'], $data['mulai'], $data['selesai'])) {
            // Get the user's input
            $idjadwal = mysqli_real_escape_string($conn, $data['id_jadwal']);
            $kls = mysqli_real_escape_string($conn, $data['kelas']);
            $mapel = mysqli_real_escape_string($conn, $data['mapel']);
            $hari = mysqli_real_escape_string($conn, $data['hari']);
            $mulai = mysqli_real_escape_string($conn, $data['mulai']);
            $selesai = mysqli_real_escape_string($conn, $data['selesai']);

            // Update query
            $query = mysqli_query($conn, "UPDATE jadwal_kelas 
            SET kode_kelas = '$kls',
            id_mapel = '$mapel', 
            hari = '$hari',
            jam = '$mulai - $selesai' 
            WHERE id_jadwal = '$idjadwal'");

            if ($query) {
                $response['success'] = true;
                $response['message'] = 'Data Saved';
                http_response_code(200); // Success 
                echo json_encode($response);
            } else {
                $response['error'] = true;
                $response['message'] = 'Error executing the query';
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Missing required fields';
            http_response_code(400); // Bad Request
            header('Content-Type: application/json');
            echo json_encode($response);
        }
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
    