<?php

class password {
    public $_pass;

    public function __construct($p) {
        $this->_pass = $p;
    }

    // 暗号化するところ
    public function encryption()
    {
       return hash('md5', $this->_pass);
    }
}

// 暗号化したい単語
$word = 'violin';

$pass = new password($word);

print $pass->encryption()."\n";
?>
