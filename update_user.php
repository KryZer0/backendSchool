<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
        $old_user = mysqli_real_escape_string($conn, $_GET['old_user']);
        $user = $data['user'];
        $pass = $data['pass'];
        $hashed_pass = md5($pass);
        $email = $data['email'];
        $priv = $data['privilege'];
        $nomor = $data['nomor'];

        switch ($priv) {
            case 'guru':
                $nomor1 = null;
                $nomor2 = $nomor;
                $priv_id = 2;
                break;
            case 'siswa':
                $nomor1 = $nomor;
                $nomor2 = null;
                $priv_id = 4;
                break;    
            default:
                $nomor1 = null;
                $nomor2 = null;
                $priv_id = 1;
                break;
        }

        if (!empty($pass)) {
            // Query with password update
            $stmt = $conn->prepare("UPDATE users 
            SET username = ?, password = ?, id_privilege = ?, email = ?, NISN_SISWA = ?, Nomor_induk_guru = ? 
            WHERE username = ?");

            if ($stmt) {
                $stmt->bind_param("ssissss", $user, $hashed_pass, $priv_id, $email, $nomor1, $nomor2, $old_user);
            } else {
                $response['error'] = true;
                $response['message'] = 'Error preparing the query';
                http_response_code(500); // Internal Server Error
            }
        } else {
            // Query without password update
            $stmt = $conn->prepare("UPDATE users 
            SET username = ?, id_privilege = ?, email = ?, NISN_SISWA = ?, Nomor_induk_guru = ? 
            WHERE username = ?");

            if ($stmt) {
                $stmt->bind_param("sissss", $user, $priv_id, $email, $nomor1, $nomor2, $old_user);
            } else {
                $response['error'] = true;
                $response['message'] = 'Error preparing the query';
                http_response_code(500); // Internal Server Error
            }
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
