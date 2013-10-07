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
    private $n;
    private $r;
    private $sr;
    
    public function __construct() {
        $this->n = new Card("Normal", 89, "Nカードをゲットしました");
        $this->r = new Card("Rare", 10, "Rカードをゲットしました");
        $this->sr = new Card("SuperRare", 1, "SRカードをゲットしました！おめでとう！！");
    }
    
    public function Start() {

        $this->result = $this->getRandom();
        if($this->result < $this->n->probability)
            $this->card = $this->n->comment;
        else if ($this->result < ($this->n->probability + $this->r->probability)
                && $this->result >= $this->n->probability)
            $this->card = $this->r->comment;
        else
            $this->card = $this->sr->comment;
    }

    private function getRandom() {
        return rand(1, 100);
    }
}

$gacha = new Gacha(); 
$gacha->Start();
print $gacha->result."\n";
print $gacha->card."\n";
