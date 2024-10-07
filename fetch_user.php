<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    $nisn = mysqli_real_escape_string($conn, $_GET['username']);
    $result = array();
    $query = mysqli_query($conn,"SELECT * FROM users WHERE username='$nisn'");

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
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>