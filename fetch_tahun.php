<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = array();
    $whereClause = '';
    
    // Check if nomor_induk is set in the GET request
    // if (isset($_GET['nisn'])) {

    // } else {
        $query = mysqli_query($conn, "SELECT * FROM `tahun_ajaran` WHERE status = 'active'");
    // }

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
