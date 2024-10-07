<?php
require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = array();
    
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $nomor = mysqli_real_escape_string($conn, $data['nomor']);
        $nama = mysqli_real_escape_string($conn, $data['nama']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $id_mapel = mysqli_real_escape_string($conn, $data['id_mapel']); // Use id_mapel to update the mata_pelajaran table
        
        // Insert into guru table
        $insert_guru_query = "INSERT INTO guru (id_guru, Nomor_induk_guru, nama_guru, email) 
                              VALUES (null, '$nomor', '$nama', '$email')";
        
        if (mysqli_query($conn, $insert_guru_query)) {
            // Get the id_guru of the newly inserted row
            $id_guru = mysqli_insert_id($conn);
            
            // Update the mata_pelajaran table with the new id_guru
            $update_mapel_query = "UPDATE mata_pelajaran SET id_guru = '$id_guru' WHERE id_mapel = '$id_mapel'";
            
            if (mysqli_query($conn, $update_mapel_query)) {
                $response['success'] = true;
                $response['message'] = 'Data guru tersimpan';
                http_response_code(200); // Success
            } else {
                $response['error'] = true;
                $response['message'] = 'Error updating data in mata_pelajaran table: ' . mysqli_error($conn);
                http_response_code(500); // Internal Server Error
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Error inserting data into guru table: ' . mysqli_error($conn);
            http_response_code(500); // Internal Server Error
        }
    } else {
        // Error in JSON input
        $response['error'] = true;
        $response['message'] = 'Invalid JSON data';
        http_response_code(400); // Bad Request
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} else {
    // For GET requests or other methods, return an error response
    $response['error'] = true;
    $response['message'] = 'Invalid request method';
    http_response_code(405); // Method Not Allowed
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
