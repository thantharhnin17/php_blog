<?php
    require '../config/config.php';

    $stmt = $pdo->prepare('DELETE FROM users WHERE id='.$_GET['id']);
    $stmt->execute();

    //delete user's comment
    $stmt1 = $pdo->prepare('DELETE FROM comments WHERE author_id='.$_GET['id']);
    $stmt1->execute();

    header('Location: user.php');
?>