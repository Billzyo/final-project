<?php
require_once 'init.php';

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $stmt = $db->query('SELECT * FROM users');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
        $stmt->execute([
            ':username' => $data['username'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':email' => $data['email']
        ]);
        echo json_encode(['message' => 'User created successfully']);
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('UPDATE users SET username = :username, email = :email WHERE id = :id');
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':id' => $data['id']
        ]);
        echo json_encode(['message' => 'User updated successfully']);
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $data);
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $data['id']]);
        echo json_encode(['message' => 'User deleted successfully']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
