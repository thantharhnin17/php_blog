<?php
    require '../config/config.php';

    $stmt = $pdo->prepare('DELETE FROM posts WHERE id='.$_GET['id']);
    $stmt->execute();

    //delete post's comment
    $stmt1 = $pdo->prepare('DELETE FROM comments WHERE post_id='.$_GET['id']);
    $stmt1->execute();

    header('Location: index.php');
?>