<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    $data = json_decode(file_get_contents('php://input'), true);
    if ($data !== null) {
        $username = $data['username'];
        $password = $data['password'];
        $password_hashed = md5($password);
        $username = str_replace(array('"', "'", ';', ':', '<', '>', '=', '--', '/*', '*/', '`', '%'), '', $username);

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if ($user["password"] == $password_hashed) {
                $response["id_privilege"] = $user["id_privilege"];
                $response['username'] = $user["username"];
                
                if ($user["NISN_SISWA"] == null && $user["Nomor_induk_guru"] == null) {
                    $response['nisn_siswa'] = "tidak ada";
                    $response["nomor_induk_guru"] = "tidak ada";
                } else if ($user["NISN_SISWA"] == null) {
                    $response["nomor_induk_guru"] = $user["Nomor_induk_guru"];
                    $stmt = $conn->prepare("SELECT id_guru FROM guru WHERE Nomor_induk_guru = ?");
                    $stmt->bind_param("s", $user["Nomor_induk_guru"]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $guru = $result->fetch_assoc();
                    if ($guru) {
                        $response['id_guru'] = $guru['id_guru'];
                        $stmt = $conn->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id_guru = ?");
                        $stmt->bind_param("i", $guru['id_guru']);  // Menggunakan $guru['id_guru'] bukan $user["id_guru"]
                        $stmt->execute();
                        $result2 = $stmt->get_result();
                        $mapel = array();
                        while ($row = $result2->fetch_assoc()) {
                            $mapel[] = $row['nama_mapel'];
                        }
                        if (!empty($mapel)) {
                            $response['mapel'] = implode(", ", $mapel);
                        } else {
                            $response['mapel'] = 'tidak ada';
                        }
                    } else {
                        $response['mapel'] = 'tidak ada';
                    }
                } else {
                    $response['nisn_siswa'] = $user["NISN_SISWA"];
                }
                http_response_code(200); // OK
            } else {
                // The password is incorrect
                $response['error'] = true;
                $response['message'] = 'Sepertinya Passwordnya salah !';
                http_response_code(401); // Unauthorized
            }
        } else {
            // The account does not exist
            $response['error'] = true;
            $response['message'] = 'Akun Tidak Ditemukan';
            http_response_code(404); // Not Found
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        
        $stmt->close();
    } else {
        $response['error'] = true;
        $response['message'] = 'Bad Request';
        http_response_code(400); // Bad Request
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
