<?php

class Users extends Database
{
    private $table = 'users';

    public function getUserById($id)
    {
        $this->query("SELECT * FROM $this->table WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function createUser($username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->query("INSERT INTO $this->table (username, password) VALUES (:username, :password)");
        $this->bind(':username', $username);
        $this->bind(':password', $hashedPassword);
        return $this->execute();
    }
}
