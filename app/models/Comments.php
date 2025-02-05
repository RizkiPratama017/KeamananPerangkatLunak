<?php

class Comments extends Database
{
    private $table = 'comments';

    public function getCommentsByPostId($post_id)
    {
        $this->query("SELECT * FROM $this->table WHERE post_id = :post_id ORDER BY created_at DESC");
        $this->bind(':post_id', $post_id);
        return $this->resultSet();
    }

    public function addComment($post_id, $comment)
    {
        $this->query("INSERT INTO $this->table (post_id, comment) VALUES (:post_id, :comment)");
        $this->bind(':post_id', $post_id);
        $this->bind(':comment', $comment);
        return $this->execute();
    }
}
