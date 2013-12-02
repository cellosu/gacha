<?php
$coin = $_POST['coin'];

    // データベースへの接続
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
    } catch(PDOException $e) {
        var_dump($e->getMessage());
        exit;
    }

        // 処理
    $sql = "update users set coin = coin + ".$coin;
    $stmt = $dbh->query($sql);
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
        print $user['coin']."\n";
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
      <title>Developer</title>
   </head>
   <body>
       <h2>所持金付与画面</h2>
      <form action="developer.php" method="post">
         付与するコイン数を入力してください<br>
         coin<br>
         <input type="text" name="coin" default="0"><br>
         <input type="submit" value="ok">
      </form>
   </body>
</html>
