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

    $username = $dData['username'] ?? '';
    $email = $dData['email'] ?? '';
    $password = $dData['password'] ?? '';
    $confirmPassword = $dData['confirmPassword'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit();
    }

    if ($password !== $confirmPassword) {
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit();
    }

    // Check if the email is already registered
    $sql = "SELECT * FROM login_details WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'Email is already registered';
        echo json_encode($response);
        exit();
    }

    // Insert new user into the database
    $sql = "INSERT INTO login_details (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'User registered successfully'];
    } else {
        $response['message'] = 'Database error: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>
