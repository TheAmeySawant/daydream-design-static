<?php
// create_tokens_table.php
include '../config.php';

// SQL to create tokens table with auto-deletion after 30 days
$sql = "CREATE TABLE IF NOT EXISTS tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT (DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 30 DAY)),
    INDEX idx_token (token),
    INDEX idx_expires (expires_at)
)";

if (mysqli_query($connection, $sql)) {
    echo "Table 'tokens' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . mysqli_error($connection);
}

// Create an event to auto-delete expired tokens (requires EVENT scheduler to be enabled)
$eventSql = "CREATE EVENT IF NOT EXISTS delete_expired_tokens
ON SCHEDULE EVERY 1 DAY
DO
DELETE FROM tokens WHERE expires_at < NOW()";

if (mysqli_query($connection, $eventSql)) {
    echo "Auto-deletion event created successfully.<br>";
} else {
    echo "Error creating event: " . mysqli_error($connection);
}

mysqli_close($connection);
?>
