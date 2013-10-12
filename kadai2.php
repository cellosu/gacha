<?php

class Card {
    public $type;
    public $probability;
    public $comment;

    public function __construct($t, $p, $c) {
        $this->type = $t;
        $this->probability = $p;
        $this->comment = $c;
    }
}

class Gacha {
    public $card;
    public $result;

    private $card_list;

    public function __construct() {
        $this->card_list[] = new Card("Normal", 89, "Nカードをゲットしました");
        $this->card_list[] = new Card("Rare", 10, "Rカードをゲットしました");
        $this->card_list[] = new Card("SuperRare", 1, "SRカードをゲットしました！おめでとう！！");
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
                break;
            }
        }
    }

    private function getRandom($probability_sum) {
        return rand(1, $probability_sum);
    }

    public function printResult() {
        print $this->card->comment . "\n";
    }
}

$gacha = new Gacha();
$gacha->start();
$gacha->printResult();
