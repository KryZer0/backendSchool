<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $id2 = mysqli_real_escape_string($conn, $_GET['id_materi']);
    
    // Check if $id is a numeric value before proceeding with the query
    if (is_numeric($id)) {
        $result = array();
        $query = mysqli_query($conn, "SELECT nilai_harian.id_nilai_harian, siswa.NISN, siswa.Nama_siswa,
        mata_pelajaran.nama_mapel, materi.id_materi, materi.nama_materi,
        nilai_harian.th1, nilai_harian.th2, nilai_harian.ph
            from nilai_harian
            INNER JOIN materi ON nilai_harian.id_materi = materi.id_materi
            INNER JOIN siswa ON nilai_harian.nisn = siswa.NISN
            INNER JOIN mata_pelajaran ON nilai_harian.id_mapel = mata_pelajaran.id_mapel
            WHERE nilai_harian.id_nilai_harian = $id AND materi.nama_materi = '$id2'");
        
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
        $response['message'] = 'Invalid ID format';
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode($response);
}

?>