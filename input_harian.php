<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $nisn = $data['nisn'];
        $id_mapel = $data['id_mapel'];
        $materi = $data['materi'];
        $th1 = $data['th1'];
        $th2 = $data['th2'];
        $ph = $data['ph'];
        $kls = $data['kelas'];
        
        // Fetch guru's name based on id_mapel
        $guru_query = mysqli_query($conn, "SELECT g.nama_guru , mp.id_mapel
                                           FROM guru g 
                                           JOIN mata_pelajaran mp ON g.id_guru = mp.id_guru 
                                           WHERE mp.nama_mapel = '$id_mapel'");
        if ($guru_query && mysqli_num_rows($guru_query) > 0) {
            $guru_result = mysqli_fetch_assoc($guru_query);
            $guru = $guru_result['nama_guru'];
            $idmap = $guru_result['id_mapel'];
        } else {
            $response['error'] = true;
            $response['message'] = 'Guru tidak ditemukan untuk id_mapel tersebut.';
            http_response_code(404); // Not Found
            echo json_encode($response);
            exit();
        }

        // Fetch the current active school year
        $tahun_ajar_query = mysqli_query($conn, "SELECT id_tahun 
                                                 FROM tahun_ajaran
                                                 WHERE status = 'active'");
        if ($tahun_ajar_query && mysqli_num_rows($tahun_ajar_query) > 0) {
            $tahun_ajar_result = mysqli_fetch_assoc($tahun_ajar_query);
            $tahun_ajar = $tahun_ajar_result['id_tahun'];
        } else {
            $response['error'] = true;
            $response['message'] = 'Tidak ada tahun ajaran yang aktif.';
            http_response_code(404); // Not Found
            echo json_encode($response);
            exit();
        }

        // Fetch materi into materi table
        $query2 = mysqli_query($conn, "SELECT * FROM materi WHERE nama_materi = '$materi' AND id_mapel = '$idmap'");
        if ($query2 && mysqli_num_rows($query2) > 0) {
            $id_result = mysqli_fetch_assoc($query2); // Get the id_materi
            $id_materi = $id_result['id_materi'];
        } else {
            $response['error'] = true;
            $response['message'] = 'Materi tidak ditemukan untuk id_mapel tersebut.';
            http_response_code(404); // Not Found
            echo json_encode($response);
            exit();
        }

        $kelas_query = mysqli_query($conn, "SELECT siswa.kelas FROM siswa WHERE siswa.NISN = '$nisn'");
        if ($kelas_query && mysqli_num_rows($kelas_query) > 0) {
            $kelas_result = mysqli_fetch_assoc($kelas_query);
            $kode_kelas = $kelas_result['kelas'];
        
            // Check for existing record with the same kode_kelas and id_mapel
            $check_query = mysqli_query($conn, "SELECT * FROM nilai_harian WHERE nisn = '$nisn' AND id_mapel = '$idmap' AND kode_kelas = '$kode_kelas' AND id_materi = '$id_materi';");
            if (mysqli_num_rows($check_query) > 0) {
                // Record already exists
                $response['error'] = true;
                $response['message'] = 'Terdapat Duplikasi pada nilai siswa';
                http_response_code(409); // Conflict
            } else {
                // No duplicate found, proceed with the insert
                $query = mysqli_query($conn, "INSERT INTO nilai_harian (id_tahun, nisn, kode_kelas, id_mapel, id_materi, th1, th2, ph, guru_pengajar) 
                VALUES ('$tahun_ajar', '$nisn', '$kls', '$idmap', '$id_materi', '$th1', '$th2', '$ph', '$guru')");
                if ($query) {
                    $response['success'] = true;
                    $response['message'] = 'Data Saved';
                    http_response_code(200); // Success
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Database error: Could not save data.';
                    http_response_code(500); // Internal Server Error
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'No class found for the given NISN.';
            http_response_code(404); // Not Found
        }
        echo json_encode($response);
    } else {
        // Error JSON input
        $response['error'] = true;
        $response['message'] = 'Invalid JSON data';
        http_response_code(400); // Bad Request
        header('Content-Type: application/json');
        echo json_encode($response);
    }

} else {
    // For GET requests or other methods, return an error response
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
