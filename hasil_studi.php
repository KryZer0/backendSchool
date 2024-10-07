<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nisn'])) {
    $result = array();

    $nisn = mysqli_real_escape_string($conn, $_GET['nisn']);
    $kls = mysqli_real_escape_string($conn, $_GET['kls']);

    $query = mysqli_query($conn, "SELECT siswa.Nama_siswa, mata_pelajaran.nama_mapel, 
    kelas.nama_kelas, nilai_siswa.nilai_uts, nilai_siswa.nilai_uas 
    FROM nilai_siswa
    INNER JOIN siswa on nilai_siswa.nisn = siswa.NISN
    INNER JOIN mata_pelajaran ON nilai_siswa.id_mapel = mata_pelajaran.id_mapel
    INNER JOIN kelas ON nilai_siswa.kode_kelas = kelas.kode_kelas
    WHERE nilai_siswa.nisn = '$nisn' AND kelas.nama_kelas = '$kls'");

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