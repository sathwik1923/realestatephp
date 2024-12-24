<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="display.css">
</head>
<body>
    <h1>Property Listings</h1>
    
</body>
</html>


<?php
include 'database.php'; // Include your database connection script

// Fetch property listings from the database
$sql = "SELECT * FROM listings";
$result = $conn->query($sql);


    echo '<table border="1">';
    echo '<tr><th>ID</th><th>Name</th><th>Description</th><th>Address</th><th>Beds</th><th>Bathrooms</th><th>Price</th><th>Images</th></tr>';
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td>' . $row['address'] . '</td>';
        echo '<td>' . $row['beds'] . '</td>';
        echo '<td>' . $row['bathrooms'] . '</td>';
        echo '<td>' . $row['price'] . '</td>';
        echo '<td>';
        $image = $row['images'];
        echo '<img src="' . $image . '" alt="Image" width="100">';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
// } else {
//     echo 'No listings found';
// }

$conn->close();
?>
