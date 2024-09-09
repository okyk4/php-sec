<?php
require_once('config.php');

function connectPdo()
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }
}

function createTodoData($todoText)
{
    $dbh = connectPdo();
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR);
    $stmt->execute();
}

function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    return $dbh->query($sql)->fetchAll();
}

function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR);
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT);
    $stmt->execute();
}

function getTodoTextById($id)
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL AND id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
    return $data['content'];
}

function deleteTodoData($id)
{
    $dbh = connectPdo();
    date_default_timezone_set('Asia/Tokyo');
    $now = date('Y-m-d H:i:s');
    $sql = 'UPDATE todos SET deleted_at = "' . $now . '" WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $stmt->execute();
}