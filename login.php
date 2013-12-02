<?php

session_start();

$_SESSION['id'] = $_POST["user_id"];
$_SESSION['password'] = $_POST["password"];

// id または passが入力されていなかった場合の処理
if(empty($_SESSION['id']) || empty($_SESSION['password'])){
    print"ログイン名・パスワードが入力されていません";
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
    $sql = "select * from users where user_id = ".$_SESSION['id'];
    $stmt = $dbh->query($sql);
// id と pass が適切かどうか
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
        if($_SESSION['password'] == $user['password']){
            $_SESSION['name'] = $user['user_name'];
            $_SESSION['coin'] = $user['coin'];
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
      <meta http-equiv="Content-Type" content="text/html; UTF-8">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <link rel="stylesheet" type="text/css" href="style.css">
      <title>Login</title>
   </head>
   <body>
      <p>ようこそ <?php echo $_SESSION['name']?> さん！</p>
      <p>コイン : <?php echo $_SESSION['coin']?></p>
       <h2>ガチャ</h2>
       <form action="gacha.php" method="post">
          1回300コイン<br>
          <input type="submit" value="ガチャを回す">
      </form>
   </body>
</html>
