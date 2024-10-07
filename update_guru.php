<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $old_nomor = mysqli_real_escape_string($conn, $_GET['nomor']);
        $nomor = $data['nomorguru'];
        $nama = $data['nama'];
        $email = $data['email'];
        $mapel = $data['mapel'];

        // Fetch guru's name based on id_mapel
        $guru_query = mysqli_query($conn, "SELECT g.id_guru
                                           FROM guru g 
                                           WHERE g.Nomor_induk_guru = '$nomor'");
        if ($guru_query && mysqli_num_rows($guru_query) > 0) {
            $guru_result = mysqli_fetch_assoc($guru_query);
            $idguru = $guru_result['id_guru'];
        } else {
            $response['error'] = true;
            $response['message'] = 'Guru tidak ditemukan untuk id_mapel tersebut.';
            http_response_code(404); // Not Found
            echo json_encode($response);
            exit();
        }

        
        $query = mysqli_query($conn, "UPDATE guru g
        INNER JOIN  mata_pelajaran mp ON g.id_guru = mp.id_guru
        SET g.Nomor_induk_guru = '$nomor', 
        g.nama_guru = '$nama', 
        g.email = '$email',
        mp.id_guru = '$idguru'
        WHERE g.Nomor_induk_guru = '$old_nomor' AND mp.nama_mapel = '$mapel';");
    
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