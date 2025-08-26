<?php
// Include your database connection
include '../config.php';

// SQL to create projects table
$sql = "CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tag_name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tag_name (tag_name)
)";

// Execute the query
if (mysqli_query($connection, $sql)) {
    echo "Table 'tags' created successfully or already exists.";
} else {
    echo "Error creating table: " . mysqli_error($connection);
}


// // Create the cleanup event
$createEventSql = "
            CREATE EVENT IF NOT EXISTS cleanup_unused_tags
            ON SCHEDULE EVERY 1 DAY 
            STARTS CURRENT_TIMESTAMP + INTERVAL 1 DAY
            ON COMPLETION PRESERVE
            COMMENT 'Daily cleanup of unused tags that are not associated with any project'
            DO
            DELETE FROM tags WHERE id NOT IN (SELECT DISTINCT tag_id FROM project_tags)";

if (!mysqli_query($connection, $createEventSql)) {
    throw new Exception("Failed to create cleanup event: " . mysqli_error($connection));
}else {
    echo "\nCleanup event created successfully or already exists.";
}

// Close connection
mysqli_close($connection);
?>