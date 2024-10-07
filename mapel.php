<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $whereClause = '';

    // Check if nomor_induk is set in the GET request
    if (isset($_GET['nomor_induk'])) {
        // Sanitize the input value
        $nomor_induk = mysqli_real_escape_string($conn, $_GET['nomor_induk']);
        $whereClause = "WHERE Nomor_induk_guru = '$nomor_induk'";
        $query = mysqli_query($conn, "SELECT guru.id_guru, mata_pelajaran.id_mapel from guru
        INNER JOIN mata_pelajaran ON guru.nama_mapel = mata_pelajaran.nama_mapel
        $whereClause");
    } else {
        $query = mysqli_query($conn, "SELECT * FROM mata_pelajaran ORDER BY mata_pelajaran.id_mapel ASC");
    }

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
