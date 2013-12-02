<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

class Card {
    public $type;
    public $probability;
    public $comment;
    public $pass;
    public $name;

    public function __construct($t, $p, $c) {
        $this->type = $t;
        $this->probability = $p;
        $this->comment = $c;
        $this->pass = null;
        $this->name = null;
    }
}

class Gacha {
    public $card;

    public function __construct() {
        // データベースへの接続
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
        } catch(PDOException $e) {
            var_dump($e->getMessage());
            exit;
        }

        // クラスカードの初期化
        $sql = "select * from card";
        $stmt = $dbh->query($sql);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $cards) {
            $this->card_list[] = new Card($cards['type'], $cards['probability'], $cards['memo']);
        }

        // 切断
        $dbh = null;
    }

    public function start() {
        $probability_sum = 0;
        foreach ($this->card_list as $card) {
            $probability_sum += $card->probability;
        }

        $this->result = $this->getRandom($probability_sum);

        $current_probability_sum = 0;
        foreach ($this->card_list as $card) {
            $current_probability_sum += $card->probability;
            if ($this->result <= $current_probability_sum) {
                $this->card = $card;
                $this->getCard($this->card->type);
                break;
            }
        }
    }

    private function getRandom($probability_sum) {
        return rand(1, $probability_sum);
    }

    private function getCard($_type)
    {
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
        } catch(PDOException $e) {
            var_dump($e->getMessage());
            exit;
        }

        // $_typeによってランダムにカードを選択
        $sql = "select * from cards where Type = '".$_type."' order by rand() limit 1";
        $stmt = $dbh->query($sql);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $card) {
                $this->card->name = $card['card_name'];
                $this->card->pass = $card['pass'];
        }
        // 切断
        $dbh = null;
        //return $_name;
    }

    // データベースの更新
    public function insert(){
        // データベースへの接続
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
        } catch(PDOException $e) {
            var_dump($e->getMessage());
            exit;
        }

        // ユーザが引いたカードのログ
        $stmt = $dbh->prepare("insert into logs (user_name, get_card, coin, time) values(?, ?, ?, ?)");
        $stmt->execute(array($_SESSION['name'], $this->card->name, $_SESSION['coin'], date("Y-m-d H:i:s")));

       // コインの更新
       $sql = "update users set coin = ".$_SESSION['coin']." where user_id = ".$_SESSION['id'];
       $stmt = $dbh->query($sql);
       foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
           print $user['coin']."\n";
       }

        // 切断
        $dbh = null;
    }
}

// コインによる制限
if($_SESSION['coin'] < 300)
    print "コインがたりません";
else{
    $_SESSION['coin'] -= 300;
    $gacha = new Gacha();
    $gacha->start();
    $gacha->insert();
}

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
      <p>ようこそ<?php echo $_SESSION['name']; ?>さん！</p>
      <p>コイン : <?php echo $_SESSION['coin']; ?></p>
       <h2>結果</h2>
      <p><?php echo $gacha->card->name; ?></p>
      <img src="<?php echo $gacha->card->pass; ?>">
      <p><?php echo $gacha->card->comment; ?></p>
       <h2>ガチャ</h2>
       <form action="gacha.php" method="post">
          1回300コイン<br>
          <input type="submit" value="ガチャを回す">
      </form>
   </body>
</html>
