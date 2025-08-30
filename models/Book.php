<?php
session_start();
require_once '../config/database.php';

class Book {
    private $db;

    public function __construct() {
        global $connect;
        $this->db = $connect;
    }

    public function getBookById($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createBook($data) {
        $book = $this->db->prepare("INSERT INTO books (name, description, user_id, borrowed_by, assigned_by) VALUES (?, ?, ?, ?, ?)");
        $book->bind_param("ssiii", $data['name'], $data['description'], $data['user_id'], $data['borrowed_by'], $data['assigned_by']);
        $book->execute();
        return $book->insert_id;
    }

    public function updateBook($data) {
        $book = $this->db->prepare("UPDATE books SET name = ?, description = ? WHERE id = ?");
        $book->bind_param("ssi", $data['bookName'], $data['bookDescription'], $data['bookId']);
        return $book->execute();
    }

    public function deleteBook($data) {
        $book = $this->db->prepare("DELETE FROM books WHERE id = ?");
        $book->bind_param("i", $data['bookId']);
        return $book->execute();
    }
}
?>