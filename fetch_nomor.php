<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['privilege'])) {
    $privilege = mysqli_real_escape_string($conn, $_GET['privilege']);
    $result = array();

    switch ($privilege) {
        case 'Guru':
            $query = mysqli_query($conn, "SELECT Nomor_induk_guru, nama_guru FROM guru");
            break;
        case 'Siswa':
            $query = mysqli_query($conn, "SELECT nisn, Nama_siswa FROM siswa");
            break;
        default:
            $result[] = array('nomor' => '-');
            $query = false; // No query to execute
            break;
    }

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
    }

    echo json_encode(array('result' => $result));
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
