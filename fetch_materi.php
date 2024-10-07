<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['kode'])) {
    $kode = mysqli_real_escape_string($conn, $_GET['kode']);
    $result = array();
    $query = mysqli_query($conn,"SELECT * FROM materi
    INNER JOIN mata_pelajaran ON materi.id_mapel = mata_pelajaran.id_mapel
    WHERE mata_pelajaran.nama_mapel = '$kode' 
    ORDER BY materi.id_mapel ASC ");

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
        echo json_encode(array('result' => $result));
    } else {
        $response['error'] = true;
        $response['message'] = 'Error executing the query';
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>