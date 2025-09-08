<?php
session_start();

// Ensure a POST request is made for logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Destroy the session and return a success message
    session_destroy();
    echo json_encode(['message' => 'Logout successful']);
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
