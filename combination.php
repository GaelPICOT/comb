<?php
interface Combination {
    public function compare(Combination $value): int;
    public function getWinningScore(): int;
    public function createFromDict($sourceDict);
}

trait NumericalCombination {
    private $value;
    private $base;
    private $elementNumber;

    public function getBase() {
        return $this->base;
    }

    public function getElementNumber() {
        return $this->elementNumber;
    }

    public function getWinningScore(): int {
        return $this->elementNumber;
    }

    public function __serialize() {
        return ["elementNumber" => $this->elementNumber, "base" => $this->base, "value" => $this->value];
    }

    public function  __unserialize($data) {
        $this->elementNumber = $data["elementNumber"];
        $this->base = $data["base"];
        $this->value = $data["value"];
    }
}

class IntCombination implements Combination {
    use NumericalCombination;
    public function __construct($elementNumber=6, $base=10, $value=NULL) {
        $this->elementNumber = $elementNumber;
        $this->base = $base;
        if($value==NULL) {
            $this->value = rand(0, ($base**$elementNumber)-1);
        } else {
            $this->value = $value;
        }
        
    }

    private function getDigit($digitNumber) {
        return intdiv($this->value, $this->base ** $digitNumber) % $this->base;
    }

    public function compare(Combination $other): int {
        $score = 0;
        for($i=0; $i<$this->elementNumber; $i++) {
            if($other->getDigit($i) == $this->getDigit($i)) {
                $score += 1;
            }
        }
        return $score;
    }
    
    public function createFromDict($sourceDict) {
        $i=0;
        $value = 0;
        while (array_key_exists("value".$i, $sourceDict)) {
            $value += $sourceDict["value".$i] * $this->base**$i;
            $i++;
        }
        return new IntCombination($this->elementNumber, $this->base, $value);
    }
}
