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
    $sql = "select * from users";
    $stmt = $dbh->query($sql);
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
        $c = $coin + $user['coin'];
        $stmt = $dbh->prepare("update users set coin = coin");
        $stmt->execute(array("coin" >= $c));
    }
        // 切断
        $dbh = null;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="ja">
        <head>
                <title>Developer</title>
        </head>
        <body>
                <h1>所持金付与画面</h1>
                <p></p>
                <form action="Developper.php" method="post">
                    付与するコイン数を入力してください<br>
                    <input type="text" name="coin" default="0"><br>
                    <input type="submit" value="ok">
                </form>
                <br><br>
        </body>
