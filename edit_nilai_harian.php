<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        $nisn = $data['nisn'];
        $id_mapel = $data['id_mapel'];
        $materi = $data['materi'];
        $th1 = $data['th1'];
        $th2 = $data['th2'];
        $ph = $data['ph'];

        // Fetch guru's name based on id_mapel
        $guru_query = mysqli_query($conn, "SELECT mp.id_mapel
                                           FROM mata_pelajaran mp 
                                           WHERE mp.nama_mapel = '$id_mapel'");
        if ($guru_query && mysqli_num_rows($guru_query) > 0) {
            $guru_result = mysqli_fetch_assoc($guru_query);
            $idmap = $guru_result['id_mapel'];
        } else {
            $response['error'] = true;
            $response['message'] = 'Guru tidak ditemukan untuk id_mapel tersebut.';
            http_response_code(404); // Not Found
            echo json_encode($response);
            exit();
        }

        $query = mysqli_query($conn, "UPDATE nilai_harian
        INNER JOIN materi ON nilai_harian.id_materi = materi.id_materi
        SET nilai_harian.th1 = $th1,
            nilai_harian.th2 = $th2,
            nilai_harian.ph = $ph,
            materi.nama_materi = '$materi'
        WHERE nilai_harian.nisn = $nisn 
        AND nilai_harian.id_mapel = $idmap;");
    
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