<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $query = mysqli_query($conn,"SELECT username, id_privilege, email, NISN_SISWA, Nomor_induk_guru FROM users");
    while($row = mysqli_fetch_assoc($query)){
        $result[] = $row;
    }
echo json_encode(array('result'=>$result));
} else {
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>