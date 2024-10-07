<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nomor_induk'])) {
    $result = array();

    // Sanitize the input value
    $nomor_induk = mysqli_real_escape_string($conn, $_GET['nomor_induk']);

    $query = mysqli_query($conn, "SELECT guru.Nomor_induk_guru, 
        guru.nama_guru, 
        guru.email, 
        kelas.nama_kelas, 
        GROUP_CONCAT(mata_pelajaran.nama_mapel SEPARATOR ', ') AS nama_mapel FROM guru
        LEFT JOIN kelas
        ON guru.nama_guru = kelas.wali_kelas
        LEFT JOIN mata_pelajaran
        ON guru.id_guru = mata_pelajaran.id_guru
        WHERE guru.Nomor_induk_guru = '$nomor_induk'");

    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = $row;
    }

    echo json_encode(array('result' => $result));
} else {
    // For GET requests or other methods, return an error response
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>