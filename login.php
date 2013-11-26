<?php

$user_id = $_POST["user_id"];
$password = $_POST["password"];


if(empty($user_id) || empty($password)){
    print"ログイン名・パスワードが入力されていません";
    exit();
}

//データベースに接続してuser情報取得する
if($user_id == "susaki" && $password == "1105"){
    $_SESSION['id'] = $login;
    $_SESSION['name'] = "洲崎";
    $_SESSION['maney'] = 3000;
    print "ようこそ" . $_SESSION['name'] . "さん！<br>";
    print "コイン : " . $_SESSION['maney'];
}
 else {
     print "ログイン・パスワードが適切ではありません";
     session_decode();
     exit();
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="ja">
        <head>
                <title>Top</title>
        </head>
                <p>ガチャ</p>
                <p>1回300コイン</p>
                <form action="gacha.php" method="post">
                        <input type="submit" value="ガチャを回す">
                </form>
        </body>
</html>