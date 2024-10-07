<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();

    $mapel = mysqli_real_escape_string($conn, urldecode($_GET['mapel']));
    $kls = mysqli_real_escape_string($conn, $_GET['kls']);
    if (isset($_GET['mapel2'])) {
        $mapel2 = mysqli_real_escape_string($conn, $_GET['mapel2']);
        $whereclause = "WHERE (mata_pelajaran.nama_mapel = '$mapel' OR mata_pelajaran.nama_mapel = '$mapel2')
        AND nilai_harian.kode_kelas = '$kls'";
    } else {
        $whereclause = "WHERE mata_pelajaran.nama_mapel = '$mapel' AND nilai_harian.kode_kelas = '$kls'";
    }

    $query = mysqli_query($conn, "SELECT nilai_harian.id_nilai_harian, siswa.NISN, siswa.Nama_siswa, mata_pelajaran.nama_mapel,
    kelas.nama_kelas, materi.id_materi, materi.nama_materi, nilai_harian.th1, nilai_harian.th2, nilai_harian.ph
    from nilai_harian
    INNER JOIN materi ON nilai_harian.id_materi = materi.id_materi
    INNER JOIN siswa ON nilai_harian.nisn = siswa.NISN
    INNER JOIN mata_pelajaran ON nilai_harian.id_mapel = mata_pelajaran.id_mapel
    INNER JOIN kelas ON nilai_harian.kode_kelas = kelas.kode_kelas $whereclause");

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
1