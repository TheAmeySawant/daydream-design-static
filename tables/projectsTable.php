<?php
// Include your database connection
include '../config.php';

// SQL to create projects table
$sql = "CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    property_type ENUM('Residential', 'Commercial', 'Hospitality', 'Retail') NOT NULL,
    visibility BOOLEAN NOT NULL DEFAULT TRUE
)";

// Execute the query
if (mysqli_query($connection, $sql)) {
    echo "Table 'projects' created successfully or already exists.";
} else {
    echo "Error creating table: " . mysqli_error($connection);
}

// Close connection
mysqli_close($connection);
?>
