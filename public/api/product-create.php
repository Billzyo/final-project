<?php
require 'init.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Decode JSON input
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            throw new Exception("Invalid JSON input");
        }

        $productModel = new Product();

        // Validate input data
        $errors = $productModel->validate($data);
        if (!empty($errors)) {
            http_response_code(400); // Bad Request
            echo json_encode([
                'status' => 'error',
                'message' => 'Validation errors occurred',
                'errors' => $errors
            ]);
            exit;
        }

        // Generate barcode and optional filename if applicable
        if (!isset($data['barcode'])) {
            $data['barcode'] = $productModel->generate_barcode();
        }

        if (!empty($data['image']) && isset($data['image']['tmp_name'])) {
            $ext = pathinfo($data['image']['name'], PATHINFO_EXTENSION);
            $filename = $productModel->generate_filename($ext);
            $uploadDir = 'uploads/'; // Ensure this directory is writable
            $uploadPath = $uploadDir . $filename;

            if (!move_uploaded_file($data['image']['tmp_name'], $uploadPath)) {
                throw new Exception("Failed to upload the image");
            }

            $data['image'] = $uploadPath;
        }

        // Insert the validated data
        $productModel->insert($data);

        http_response_code(201); // Created
        echo json_encode([
            'status' => 'success',
            'message' => 'Product created successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
