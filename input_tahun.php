<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();

    if ($data !== null) { // Check if JSON was successfully decoded
        $thn1 = $data['tahun'];
        $thn2 = $thn1 + 1;
        $semester = $data['semester'];
        $status = $data['status'];

        // Query to set the status to inactive where it is currently active
        $query2 = mysqli_query($conn, "UPDATE tahun_ajaran SET status = 'inactive' WHERE status = 'active'");

        // Check if query2 was successful
        if ($query2) {
            // Insert the new record
            $query1 = mysqli_query($conn, "INSERT INTO tahun_ajaran (id_tahun, tahun_ajar, semester, status) VALUES (null, '$thn1/$thn2', '$semester','$status')");

            // Check if query1 was successful
            if ($query1) {
                $response['success'] = true;
                $response['message'] = 'Materi Tersimpan';
                http_response_code(200); // Success 
            } else {
                $response['error'] = true;
                $response['message'] = 'Error saving the new record';
                http_response_code(500); // Internal Server Error
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Error updating the status';
            http_response_code(500); // Internal Server Error
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
