<?php
trait FlagTrait {
    public $flagged = false;

    public function isFlagged(){
        return $this->isFlagged();
    }

    public function flag(){
        return $this->setFlagged(true);
    }

    public function unFlag(){
        return $this->setFlagged(false);
    }

    public function setFlagged(bool $flagged): Model{
        $this->flagged = $flagged;
        return $this;
    }

}