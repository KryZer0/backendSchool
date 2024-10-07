<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $kelas = mysqli_real_escape_string($conn, $_GET['kelas']);
    $query = mysqli_query($conn,"SELECT jk.id_jadwal, jk.kode_kelas, jk.id_tahun, ta.tahun_ajar, ta.semester, jk.id_mapel, mp.nama_mapel, jk.hari, jk.jam
        FROM jadwal_kelas jk
        INNER JOIN mata_pelajaran mp ON jk.id_mapel = mp.id_mapel
        INNER JOIN tahun_ajaran ta ON jk.id_tahun = ta.id_tahun
        WHERE jk.kode_kelas = '$kelas'");

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