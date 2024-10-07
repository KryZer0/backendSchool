<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $old_user = $data['old_user'];
        $user = $data['user'];
        $email = $data['email'];
        $stmt = $conn->prepare("UPDATE users 
        SET username = ?, email = ?
        WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("sss", $user, $email, $old_user);
        } else {
            $response['error'] = true;
            $response['message'] = 'Error preparing the query';
            http_response_code(500); // Internal Server Error
        }

        if ($stmt && $stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Data Updated successfully';
            http_response_code(200); // Success
        } else {
            $response['error'] = true;
            $response['message'] = 'Error executing the query';
            http_response_code(500); // Internal Server Error
        }
        $stmt->close();

        header('Content-Type: application/json');
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
