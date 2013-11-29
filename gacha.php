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

        // 処理
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

        // 処理
        $sql = "select * from cards where Type = '".$_type."' order by rand() limit 1";
        $sql = "select * from cards where Type = 'Normal' order by rand() limit 1";
        $stmt = $dbh->query($sql);
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $card) {
                $this->card->name = $card['card_name'];
                $this->card->pass = $card['pass'];
        }
        // 切断
        $dbh = null;
        //return $_name;
    }

    public function insert(){

        // データベースへの接続
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=task;','gacha', 'tasktask');
        } catch(PDOException $e) {
            var_dump($e->getMessage());
            exit;
        }

        // 処理
        $stmt = $dbh->prepare("insert into logs (user_name, get_card, coin, time) values(?, ?, ?, ?)");
        $stmt->execute(array($_SESSION['name'], $this->result, $_SESSION['coin'], date("Y-m-d H:i:s")));

        echo date("Y-m-d H:i:s");

        // 切断
        $dbh = null;
    }
}

print "ようこそ" . $_SESSION['name'] . "さん！<br>";
print "コイン : " . $_SESSION['coin'] . "<br>";
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
                <title>Task</title>
        </head>
        <body>
                <h1>ガチャ</h1>
                <p>1回300コイン</p>
                <p></p>
                <form action="gacha.php" method="post">
                        <input type="submit" value="ガチャを回す">
                </form>
                <br><br>
                <p><?php echo $gacha->card->name; ?></p>
                <img src="<?php echo $gacha->card->pass; ?>">
                <p><?php echo $gacha->card->comment; ?></p>
        </body>
</html>
