<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();

    $mapel = mysqli_real_escape_string($conn, $_GET['mapel']);
    $kode = mysqli_real_escape_string($conn, $_GET['kode']);
    if (isset($_GET['mapel2'])) {
        $mapel2 = mysqli_real_escape_string($conn, $_GET['mapel2']);
        $whereclause = "WHERE (mata_pelajaran.nama_mapel = '$mapel' OR mata_pelajaran.nama_mapel = '$mapel2')
        AND nilai_siswa.kode_kelas = '$kode'";
    } else {
        $whereclause = "WHERE mata_pelajaran.nama_mapel = '$mapel' AND nilai_siswa.kode_kelas = '$kode'";
    }

    $query = mysqli_query($conn, "SELECT nilai_siswa.id_nilai, siswa.nama_siswa, mata_pelajaran.nama_mapel,
    kelas.nama_kelas, nilai_siswa.nilai_uts, nilai_siswa.nilai_uas
    FROM nilai_siswa
    INNER JOIN mata_pelajaran ON nilai_siswa.id_mapel = mata_pelajaran.id_mapel
    INNER join guru ON mata_pelajaran.id_guru = guru.id_guru
    INNER JOIN kelas on nilai_siswa.kode_kelas = kelas.kode_kelas
    INNER JOIN siswa ON nilai_siswa.nisn = siswa.nisn $whereclause");

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
