<?php
session_start();

$id = $_POST["user_id"];
$password = $_POST["password"];

if(empty($id) || empty($password)){
    print"ログイン名かパスワードが入力されていません";
    session_destroy();
    exit();
}

// データベースへの接続
try {
    $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
} catch(PDOException $e) {
    var_dump($e->getMessage());
    exit;
}

//データベースに接続してuser情報取得する
    $sql = "select * from users";
    $stmt = $dbh->query($sql);
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
        if($id == $user['user_id'] && $password == $user['password']){
            $_SESSION['name'] = $user['user_name'];
            $_SESSION['coin'] = $user['coin'];
            print "ようこそ" . $_SESSION['name'] . "さん！<br>";
            print "コイン : " . $_SESSION['coin'];
            break;
        } 
        else {
             print "ログイン・パスワードが適切ではありません";
             session_destroy();
             exit();
        }
    }

    // 切断
    $dbh = null;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="ja">
        <head>
                <title>Top</title>
        </head>
                <h1>ガチャ</h1>
                <p>1回300コイン</p>
                <form action="gacha.php" method="post">
                        <input type="submit" value="ガチャを回す">
                </form>
        </body>
</html>
