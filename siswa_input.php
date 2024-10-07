<?php
require_once('connection.php');
$response = array();
$result = array();

if (isset($_GET['kls'])) {
    $kls = mysqli_real_escape_string($conn, $_GET['kls']);
    $stmt = $conn->prepare("SELECT siswa.Nama_siswa, NISN FROM siswa 
                            INNER JOIN kelas ON siswa.kelas = kelas.kode_kelas
                            WHERE siswa.kelas = ?
                            ORDER BY siswa.Nama_siswa ASC");

    if ($stmt) {
        $stmt->bind_param("s", $kls);

        if ($stmt->execute()) {
            $query = $stmt->get_result(); // Use get_result() to fetch the result set
            while ($row = $query->fetch_assoc()) {
                $result[] = $row;
            }
            $response['result'] = $result;
            http_response_code(200); // Success
        } else {
            $response['error'] = true;
            $response['message'] = 'Error executing the query';
            http_response_code(500); // Internal Server Error
        }
        $stmt->close();
    } else {
        $response['error'] = true;
        $response['message'] = 'Error preparing the query';
        http_response_code(500); // Internal Server Error
    }
} else {
    $query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY siswa.Nama_siswa ASC");
    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
        $response['result'] = $result;
        http_response_code(200); // Success
    } else {
        $response['error'] = true;
        $response['message'] = 'Error executing the query';
        http_response_code(500); // Internal Server Error
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
