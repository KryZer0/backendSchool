<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        $materi = $data['nama'];
        $kls = $data['kelas'];
        $nama_pelajaran = $data['id'];
        // Fetch guru's name based on id_mapel
        $guru_query = mysqli_query($conn, "SELECT mp.id_mapel
                                           FROM mata_pelajaran mp 
                                           WHERE mp.nama_mapel = '$nama_pelajaran'");
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
        $tahunQuery = "SELECT id_tahun FROM tahun_ajaran WHERE status = 'active' LIMIT 1";
        $tahunresult = mysqli_query($conn, $tahunQuery);
        if ($tahunresult && mysqli_num_rows($tahunresult) > 0) {
            $row = mysqli_fetch_assoc($tahunresult);
            $tahun = mysqli_real_escape_string($conn, $row['id_tahun']);
        } else {
            $response['error'] = true;
            $response['message'] = 'Somethings wrong on the server';
            http_response_code(500); // Internal Server Error (Failed to fetch active tahun_ajar)
            echo json_encode($response);
            exit();
        }        

        $query1 = mysqli_query($conn, "INSERT INTO materi (id_materi, id_tahun, kode_kelas,id_mapel, nama_materi) VALUES (null, '$tahun','$kls','$idmap', '$materi')");
        $response['success'] = true;
        $response['message'] = 'Materi Tersimpan';
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
