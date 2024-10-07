<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = array();
    
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $old_pass = $data['old_pass'];
        $new_pass = $data['pass'];
        $username = $data['user'];

        // Check if the old password is correct
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($db_old_pass);
            $stmt->fetch();

            if ($stmt->num_rows == 0) {
                $response['error'] = true;
                $response['message'] = 'User not found';
                http_response_code(404); // Not Found
            } elseif (md5($old_pass) !== $db_old_pass) {
                $response['error'] = true;
                $response['message'] = 'Old password is incorrect';
                http_response_code(401); // Unauthorized
            } else {
                // Update password
                $stmt->close();
                $new_pass_hashed = md5($new_pass);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                if ($stmt) {
                    $stmt->bind_param("ss", $new_pass_hashed, $username);
                    if ($stmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = 'Password updated successfully';
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
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Error preparing the query';
            http_response_code(500); // Internal Server Error
        }

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
