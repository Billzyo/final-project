<?php
require_once 'init.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $stmt = $db->query('SELECT * FROM sales');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('INSERT INTO sales (product_id, quantity, total) VALUES (:product_id, :quantity, :total)');
        $stmt->execute([
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
            ':total' => $data['total']
        ]);
        echo json_encode(['message' => 'Sale created']);
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('UPDATE sales SET product_id = :product_id, quantity = :quantity, total = :total WHERE id = :id');
        $stmt->execute([
            ':product_id' => $data['product_id'],
            ':quantity' => $data['quantity'],
            ':total' => $data['total'],
            ':id' => $data['id']
        ]);
        echo json_encode(['message' => 'Sale updated']);
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('DELETE FROM sales WHERE id = :id');
        $stmt->execute([':id' => $data['id']]);
        echo json_encode(['message' => 'Sale deleted']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
