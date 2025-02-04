<?php

class Posts extends Database
{
    private $table = 'posts';

    public function getAllPosts()
    {
        $this->query("SELECT * FROM $this->table ORDER BY created_at DESC");
        return $this->resultSet();
    }

    public function getPostById($id)
    {
        $this->query("SELECT * FROM $this->table WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function createPost($title, $content, $author)
    {
        $this->query("INSERT INTO $this->table (title, content, author) VALUES (:title, :content, :author)");
        $this->bind(':title', $title);
        $this->bind(':content', $content);
        $this->bind(':author', $author);
        return $this->execute();
    }
}
