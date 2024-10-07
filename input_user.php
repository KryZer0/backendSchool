<?php
require_once('connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = array();
    if ($data !== null) { // Check if JSON was successfully decoded
        // Get the user's input
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

        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, id_privilege, email, NISN_SISWA, Nomor_induk_guru) 
        VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssisss", $user, $hashed_pass, $priv_id, $email, $nomor1, $nomor2);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Data Saved';
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
