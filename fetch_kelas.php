<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $whereClause = '';
    
    // Check if nomor_induk is set in the GET request
    if (isset($_GET['nisn'])) {
        // Sanitize the input value
        $query = mysqli_query($conn, "SELECT * FROM mata_pelajaran ORDER BY mata_pelajaran.id_mapel ASC");
    // Fetch tahun untuk bagian tambah kelas -admin
    } else if (isset($_GET['tahun'])){
        $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);
        $query = mysqli_query($conn,"SELECT * FROM kelas WHERE kode_kelas LIKE '%-" . $tahun . "'");
    } else if(isset($_GET['target'])){
        $thn1 = mysqli_real_escape_string($conn, $_GET['target']);
        $thn2 = $thn1 + 1;
        $query = mysqli_query($conn,"SELECT * FROM kelas WHERE kode_kelas LIKE '%-" . $thn2 ."/".$thn3. "'");
    } else if (isset($_GET['tujuan'])){
        $tujuan1 = mysqli_real_escape_string($conn, $_GET['tujuan']);
        $tujuan2 = $tujuan1 + 1;
        $query = mysqli_query($conn,"SELECT * FROM kelas WHERE kode_kelas LIKE '%-" . $tujuan1 ."/".$tujuan2. "'");
    } else if (isset($_GET['jadwal'])){
        // $query = mysqli_query($conn, "SELECT DISTINCT kelas.kode_kelas, kelas.nama_kelas
        // FROM kelas
        // RIGHT JOIN siswa ON kelas.kode_kelas = siswa.kelas
        // WHERE kelas.kode_kelas IS NOT NULL && kelas.nama_kelas IS NOT NULL
        // ORDER BY kelas.kode_kelas;");
    } else {
        $query = mysqli_query($conn, "SELECT DISTINCT kelas.kode_kelas, kelas.nama_kelas
        FROM kelas
        RIGHT JOIN siswa ON kelas.kode_kelas = siswa.kelas
        WHERE kelas.kode_kelas IS NOT NULL && kelas.nama_kelas IS NOT NULL
        ORDER BY kelas.kode_kelas;");
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
