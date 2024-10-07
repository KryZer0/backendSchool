<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    $username = mysqli_real_escape_string($conn, $_GET['username']);
    $result = array();
    $response = array();

    // Check if users.NISN_SISWA is null
    $checkNISN = mysqli_query($conn, "SELECT NISN_SISWA FROM users WHERE username = '$username'");
    $nisnData = mysqli_fetch_assoc($checkNISN);

    if ($nisnData['NISN_SISWA'] !== null) {
        // If users.NISN_SISWA is not null, perform the original query
        $query = mysqli_query($conn, "SELECT users.email, siswa.Nama_siswa FROM users
        INNER JOIN siswa ON users.NISN_SISWA = siswa.NISN
        WHERE users.username = '$username'");
        $option = 4;
    } else {
        // If users.NISN_SISWA is null, check users.Nomor_induk_guru
        $checkGuru = mysqli_query($conn, "SELECT Nomor_induk_guru FROM users WHERE username = '$username'");
        $guruData = mysqli_fetch_assoc($checkGuru);

        if ($guruData['Nomor_induk_guru'] !== null) {
            // If users.Nomor_induk_guru is not null, change the inner join to guru
            $query = mysqli_query($conn, "SELECT users.email, guru.Nama_guru FROM users
            INNER JOIN guru ON users.Nomor_induk_guru = guru.Nomor_induk_guru
            WHERE users.username = '$username'");
            $option = 2;
        } else {
            // If both are null, remove the inner join and add namauser=admin
            $query = mysqli_query($conn, "SELECT users.email FROM users WHERE users.username = '$username'");
            $option = 1;
        }
    }

    if ($query && $option == 4 || $option == 2) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
        $response['result'] = $result;
    }else if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $row['namauser'] = 'admin';
            $result[] = $row;
        }
        $response['result'] = $result;
    } else {
        $response['error'] = true;
        $response['message'] = 'Error executing the query';
        http_response_code(500);
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
}

header('Content-Type: application/json');
echo json_encode($response);
?>
