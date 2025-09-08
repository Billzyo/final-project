<?php
require_once 'init.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $stmt = $db->query('SELECT * FROM products');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)');
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock']
        ]);
        echo json_encode(['message' => 'Product created']);
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id');
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':id' => $data['id']
        ]);
        echo json_encode(['message' => 'Product updated']);
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute([':id' => $data['id']]);
        echo json_encode(['message' => 'Product deleted']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
