<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nisn'])) {
    $result = array();

    $nisn = mysqli_real_escape_string($conn, $_GET['nisn']);

    $query = mysqli_query($conn, "SELECT kelas.nama_kelas, jadwal_kelas.hari, tahun_ajaran.semester,jadwal_kelas.jam, mata_pelajaran.nama_mapel
    FROM kelas
    INNER JOIN jadwal_kelas ON kelas.kode_kelas = jadwal_kelas.kode_kelas
    INNER JOIN mata_pelajaran ON mata_pelajaran.id_mapel = jadwal_kelas.id_mapel
    INNER JOIN tahun_ajaran ON tahun_ajaran.id_tahun = jadwal_kelas.id_tahun
    INNER JOIN siswa ON kelas.kode_kelas = siswa.kelas
    WHERE siswa.NISN = '$nisn'");

    while ($row = mysqli_fetch_assoc($query)) {
        $result[] = $row;
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
