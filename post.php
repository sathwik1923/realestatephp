<?php
include 'database.php'; // Include your database connection script

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['status' => 'failure', 'message' => 'Unknown error'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $address = $_POST['address'] ?? '';
    $beds = $_POST['beds'] ?? '';
    $bathrooms = $_POST['bathrooms'] ?? '';
    $price = $_POST['price'] ?? '';
    $images = $_POST['images'] ?? '';
    $view= $_POST['viewall'];
    echo `<a href="http://localhost/karthik/real-estate/frontend/display-listing/display.php">display listing properties</a>`;

    // Perform basic validation
    if (empty($name) || empty($description) || empty($address) || empty($beds) || empty($bathrooms) || empty($price)) {
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit();
    }

    // Handling file uploads
    // $uploadedFiles = [];
    // if (!empty($images['name'][0])) {
    //     $uploadDir = 'uploads/';
    //     foreach ($images['tmp_name'] as $key => $tmpName) {
    //         $fileName = basename($images['name'][$key]);
    //         $targetFilePath = $uploadDir . $fileName;
    //         if (move_uploaded_file($tmpName, $targetFilePath)) {
    //             $uploadedFiles[] = $targetFilePath;
    //         } else {
    //             $response['message'] = 'File upload error';
    //             echo json_encode($response);
    //             exit();
    //         }
    //     }
    // }
    // $imagesJson = json_encode($uploadedFiles);
    // Insert data into the database
    $sql = "INSERT INTO listings (name, description, address, beds, bathrooms, price, images) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiids", $name, $description, $address, $beds, $bathrooms, $price, $images);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Listing created successfully','display-listing {url}' => 'http://localhost/karthik/real-estate/frontend/display-listing/display.php'];
    } else {
        $response['message'] = 'Database error: ' . $stmt->error;
    }
    if (isset($_POST['viewall'])) {
        $query = "INSERT INTO property ( name, description, address, beds, bathrooms, price, images) VALUES ('$name', '$description', '$address', $beds, $bathrooms, $price, '$images')";
    }
    

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>
