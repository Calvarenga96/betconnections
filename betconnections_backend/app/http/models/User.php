<?php

namespace App\Http\Models;

use App\Helpers\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database('localhost', 'betconnections', 'root', '');
    }

    public function findAll()
    {
        $stmt = $this->db->getConnection()->query('SELECT * FROM users');
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($name, $email, $password)
    {
        $stmt = $this->db->getConnection()->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();
    }

    public function update($id, $name, $email, $password)
    {
        $stmt = $this->db->getConnection()->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->getConnection()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
