<?php

try {
    $dbh = new PDO('mysql:host=pokelabo; dbname=blog_app;','pokelabo', 'knightknight');
} catch(PDOException $e) {
    var_dump($e->getMessage());
    exit;
}

echo "success!";

// 切断

$dbh = null;