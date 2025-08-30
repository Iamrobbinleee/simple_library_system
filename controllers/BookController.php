<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';
require_once '../models/Book.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
$resource = $request[0] ?? null;
$id = $request[1] ?? null;

$book = new Book();

switch ($method) {
    case 'GET':
        if ($resource === 'books') {
            if ($id) {
                $data = $book->getBookById($id);
                if ($data && isset($data['id'])) {
                    $data = [$data];
                }
                echo json_encode($data ?: []);
            }
            
        }
        break;

    case 'POST':
        if ($resource === 'books') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['name']) && isset($data['description']) && isset($data['user_id']) &&
            !empty(trim($data['name'])) &&
            !empty(trim($data['description'])) &&
            !empty($data['user_id'])) {

                $newId = $book->createBook($data);
                echo json_encode(['message' => 'Book created successfully', 'id' => $newId]);
            } else {
                echo json_encode(['error' => 'Missing required fields']);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>
