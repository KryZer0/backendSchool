<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $whereClause = '';
    
    // Check if nomor_induk is set in the GET request
    if (isset($_GET['nisn'])) {
        // Sanitize the input value
        $nomor_induk = mysqli_real_escape_string($conn, $_GET['nisn']);
        $whereClause = "WHERE nilai_siswa.nisn = '$nomor_induk'";
        $query = mysqli_query($conn, "SELECT nilai_siswa.nisn, kelas.nama_kelas AS subjects
        FROM kelas
        INNER JOIN nilai_siswa ON nilai_siswa.kode_kelas = kelas.kode_kelas
        $whereClause GROUP BY nilai_siswa.nisn, kelas.nama_kelas");
    } else if(isset($_GET['mapel']) && isset($_GET['mapel2'])) {
        $mapel = mysqli_real_escape_string($conn, $_GET['mapel']);
        $mapel2 = mysqli_real_escape_string($conn, $_GET['mapel2']);
        $whereClause = "WHERE mata_pelajaran.nama_mapel = '$mapel' OR mata_pelajaran.nama_mapel = '$mapel2'";
        $query = mysqli_query($conn, "SELECT  kelas.kode_kelas, kelas.nama_kelas AS subjects
        FROM kelas
        LEFT JOIN nilai_siswa ON nilai_siswa.kode_kelas = kelas.kode_kelas
        LEFT JOIN mata_pelajaran ON nilai_siswa.id_mapel = mata_pelajaran.id_mapel
        $whereClause GROUP BY kelas.nama_kelas");
    } else if(isset($_GET['mapel'])) {
        $mapel = mysqli_real_escape_string($conn, $_GET['mapel']);
        $whereClause = "WHERE mata_pelajaran.nama_mapel = '$mapel'";
        $query = mysqli_query($conn, "SELECT  kelas.kode_kelas, kelas.nama_kelas AS subjects
        FROM kelas
        LEFT JOIN nilai_siswa ON nilai_siswa.kode_kelas = kelas.kode_kelas
        LEFT JOIN mata_pelajaran ON nilai_siswa.id_mapel = mata_pelajaran.id_mapel
        $whereClause GROUP BY kelas.nama_kelas");
    } else if(isset($_GET['map'])){
        $map = mysqli_real_escape_string($conn, $_GET['map']);
        $whereClause = "WHERE mata_pelajaran.nama_mapel = '$map'";
        $query = mysqli_query($conn, "SELECT kelas.kode_kelas, kelas.nama_kelas AS subjects
        FROM kelas
        LEFT JOIN nilai_harian ON nilai_harian.kode_kelas = kelas.kode_kelas
        LEFT JOIN mata_pelajaran ON nilai_harian.id_mapel = mata_pelajaran.id_mapel
        $whereClause GROUP BY kelas.nama_kelas");
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
