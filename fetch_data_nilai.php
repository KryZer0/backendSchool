<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_nilai'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id_nilai']);
    
    // Check if $id is a numeric value before proceeding with the query
    if (is_numeric($id)) {
        $result = array();
        $query = mysqli_query($conn, "SELECT nisn FROM nilai_siswa WHERE id_nilai = $id");
        
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