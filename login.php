<?php
include 'database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['status' => 'failure', 'message' => 'Unknown error'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    if ($dData === null) {
        $response['message'] = 'Invalid JSON';
        echo json_encode($response);
        exit();
    }

    $username = $dData['emailuser'] ?? '';
    $password = $dData['password'] ?? '';

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username or password cannot be empty';
        echo json_encode($response);
        exit();
    }

    // Query database for user
    $sql = "SELECT * FROM login_details WHERE username=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $response['message'] = 'Database error: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows != 0) {
        $row = $result->fetch_assoc();
        if ($password != $row['password']) {
            $response['message'] = 'Invalid password!';
        } else {
            $response = ['status' => 'success', 'username' => $row['username']];
        }
    } else {
        $response['message'] = 'User not found!';
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>
